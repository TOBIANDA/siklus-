@extends('layouts.app')

@section('content')

<style>
/* ===== CSS-ONLY MODAL (:target trick) ===== */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.48);
  z-index: 200;
  align-items: center;
  justify-content: center;
}
.modal-overlay:target { display: flex; }

.modal-box {
  background: white;
  border-radius: 20px;
  padding: 32px;
  position: relative;
  max-width: 95vw;
  box-shadow: 0 24px 60px rgba(0,0,0,.2);
  animation: popIn .22s cubic-bezier(.34,1.56,.64,1);
}
@keyframes popIn {
  from { transform: scale(.92); opacity: 0; }
  to   { transform: scale(1);   opacity: 1; }
}
.modal-close {
  position: absolute; top: 16px; right: 16px;
  width: 32px; height: 32px;
  display: flex; align-items: center; justify-content: center;
  border-radius: 8px; color: #6B7280;
  text-decoration: none; font-size: 20px; line-height: 1;
  background: #F3F4F6;
}
.modal-close:hover { background: #E5E7EB; color: #111827; }

.form-input {
  width: 100%;
  padding: 12px 14px;
  border: 1.5px solid #E5E7EB;
  background: #F3F4F6;
  border-radius: 10px;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
  transition: border-color .15s, background .15s;
}
.form-input:focus { border-color: var(--blue); background: #fff; }
.form-label {
  font-size: 11px; font-weight: 700; color: var(--gray);
  text-transform: uppercase; letter-spacing: .06em;
  display: block; margin-bottom: 5px;
}

/* ===== FLASH ===== */
.alert-success {
  background: #D1FAE5; color: #065F46; border-radius: 10px;
  padding: 12px 18px; font-size: 14px; font-weight: 600;
  margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
}
</style>

<div style="padding:24px;">

  {{-- FLASH --}}
  @if(session('success'))
  <div class="alert-success" style="background:#D1FAE5;color:#065F46;display:flex;align-items:center;gap:8px;">
    <span style="color:#10B981;font-weight:700;font-size:16px;">✓</span>
    {{ session('success') }}
  </div>
  @endif

  {{-- HERO --}}
  <div class="hero hero-navy" style="margin-bottom:28px;">
    <div class="hero-bg-text" style="font-family:'Caveat',cursive;font-size:80px;opacity:.12;">
      {{ explode(' ', $book->title)[0] }}
    </div>
    <img src="{{ $book->cover_url }}"
         class="book-cover" alt="{{ $book->title }}"
         onerror="this.style.background='linear-gradient(135deg,#0F172A,#2563EB)';this.removeAttribute('src')">
    <div class="hero-info">
      <h1>{{ $book->title }}</h1>
      <div class="author">{{ $book->author }}</div>
      <div class="hero-meta">
        <span>
          <img src="{{ asset('images/chart-column-stacked.png') }}" alt="Kategori" style="width:13px;height:13px;object-fit:contain;vertical-align:middle;">
          <span style="margin-left:4px;">{{ $book->category }}</span>
        </span>
        <span>
          <img src="{{ asset('images/star.png') }}" alt="Rating" style="width:13px;height:13px;object-fit:contain;vertical-align:middle;">
          <span style="margin-left:4px;">{{ number_format($book->rating, 1) }} {{ __('book.rating') }}</span>
        </span>
        <span>
          <span style="color:var(--blue);font-weight:700;">DIPINJAM</span>
          <span style="margin-left:4px;">{{ $book->borrow_count }}x</span>
        </span>
        @if($book->location)
        <span>
          <span style="color:var(--blue);font-weight:700;">LOKASI</span>
          <span style="margin-left:4px;">{{ $book->location }}</span>
        </span>
        @endif
      </div>
    </div>
  </div>

  <div class="book-layout">

    {{-- MAIN --}}
    <div class="book-main">
      <div class="desc-tabs">
        <a class="desc-tab {{ request('view', 'desc') === 'desc' ? 'active' : '' }}"
           href="{{ route('book.show', $book->id) }}?view=desc"
           style="text-decoration:none;color:inherit;">{{ __('book.description') }}</a>
        <a class="desc-tab {{ request('view') === 'review' ? 'active' : '' }}"
           href="{{ route('book.show', $book->id) }}?view=review"
           style="text-decoration:none;color:inherit;">{{ __('book.review') }}</a>
      </div>

      @if(request('view', 'desc') === 'desc')
      <div class="book-desc">
        @if($book->description)
          @foreach(explode("\n", $book->description) as $para)
          <p>{{ $para }}</p>
          @endforeach
        @else
          <p style="color:var(--gray);">{{ __('book.no_description') }}</p>
        @endif
      </div>
      @else
      <div class="reviews-grid" style="margin-bottom:28px;">
        @foreach(range(1,2) as $i)
        <div class="review-card">
          <div class="reviewer">
            <div class="rev-av">
              <img src="{{ asset('images/avatar_tobby.jpg') }}" alt="Reviewer"
                   style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
            </div>
            <div>
              <div class="rev-name">Tobby</div>
              <div class="rev-role">Undergraduate Student @ UB</div>
            </div>
          </div>
          <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
          <div class="rev-text">Buku yang sangat bermanfaat dan mudah dipahami. Pemilik buku juga sangat responsif!</div>
        </div>
        @endforeach
      </div>
      @endif

      {{-- SIMILAR BOOKS --}}
      <div class="section" style="margin-top:28px;">
        <div style="margin-bottom:14px;"><span class="section-title">{{ __('book.similar_books') }}</span></div>
        <div class="book-grid">
          @foreach(\App\Models\Book::where('category', $book->category)->where('id', '!=', $book->id)->limit(4)->get() as $similar)
          <div class="book-card">
              <img src="{{ $similar->cover_url }}" class="cover" alt="{{ $similar->title }}"
                   onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
              <div class="card-body">
                <div class="card-title">{{ $similar->title }}</div>
                <div class="card-author">{{ $similar->author }}</div>
                <div class="card-footer">
                  <span style="font-size:10px;color:rgba(255,255,255,.8);font-weight:600;background:rgba(37,99,235,.7);padding:2px 6px;border-radius:4px;">{{ $similar->category }}</span>
                </div>
              </div>
              <a href="{{ route('book.show', $similar->id) }}" aria-label="{{ $similar->title }}"></a>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- LENDER SIDEBAR --}}
    <div class="book-side">
      <div class="lender-card">
        <div class="lc-top">
          <div class="lc-av">
            <img src="{{ asset('images/' . ($book->owner_avatar ?? 'avatar_user.png')) }}"
                 style="width:100%;height:100%;object-fit:cover;border-radius:50%;"
                 onerror="this.style.display='none'">
          </div>
          <div>
            <div class="lc-name">{{ $book->owner_name }}</div>
            @if($book->location)
            <div class="lc-loc">&#128205; {{ $book->location }}</div>
            @endif
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
          <img src="{{ asset('images/star.png') }}" alt="Rating" style="width:13px;height:13px;object-fit:contain;">
          <span style="font-size:13px;font-weight:600;">{{ number_format($book->rating, 1) }} / 5.0</span>
          <span style="font-size:12px;color:var(--gray);">• {{ $book->borrow_count }}x dipinjam</span>
        </div>
        {{-- Tombol borrow membuka modal --}}
        <a href="#modal-borrow" class="borrow-btn" style="text-decoration:none;display:block;text-align:center;">
          {{ __('book.borrow_from') }} {{ $book->owner_name }}
        </a>
      </div>
    </div>

  </div>
</div>



<div id="modal-borrow" class="modal-overlay">
  {{-- Backdrop --}}
  <a href="#" style="position:absolute;inset:0;z-index:0;display:block;"></a>

  <div class="modal-box" style="width:440px;z-index:1;">
    <a href="#" class="modal-close" title="Tutup">&#10005;</a>

    <h2 style="font-size:20px;font-weight:700;text-align:center;margin-bottom:4px;">{{ $book->title }}</h2>
    <p style="text-align:center;color:#6B7280;font-size:13px;margin-bottom:20px;">{{ $book->author }}</p>

    {{-- Book cover preview --}}
    <div style="border-radius:12px;overflow:hidden;margin-bottom:20px;height:150px;
                background:#0F172A;display:flex;align-items:center;justify-content:center;">
      <img src="{{ $book->cover_url }}"
           style="width:100%;height:100%;object-fit:cover;"
           onerror="this.style.display='none'">
    </div>

    <form action="{{ route('borrow.request', $book->id) }}" method="POST"
          style="display:flex;flex-direction:column;gap:14px;">
      @csrf

      <div>
        <label class="form-label">Pesan untuk Pemilik (opsional)</label>
        <textarea name="message" class="form-input" rows="2"
          style="resize:none;"
          placeholder="Contoh: Bisa COD di Dinoyo sekitar jam 5 sore..."></textarea>
      </div>

      {{-- Tanggal pinjam & kembali --}}
      <div style="display:flex;align-items:flex-end;gap:10px;">
        <div style="flex:1;">
          <label class="form-label">Tanggal Pinjam</label>
          <div style="display:flex;align-items:center;gap:8px;background:#F3F4F6;border:1.5px solid #E5E7EB;border-radius:10px;padding:10px 12px;">
            <input type="date" name="borrow_date" id="borrowDate"
              style="border:none;background:none;outline:none;font-family:'DM Sans',sans-serif;font-size:13px;flex:1;color:#374151;"
              min="{{ date('Y-m-d') }}" required>
          </div>
        </div>
        <span style="color:#6B7280;font-weight:700;font-size:18px;padding-bottom:12px;">–</span>
        <div style="flex:1;">
          <label class="form-label">Tanggal Kembali</label>
          <div style="display:flex;align-items:center;gap:8px;background:#F3F4F6;border:1.5px solid #E5E7EB;border-radius:10px;padding:10px 12px;">
            <input type="date" name="return_date" id="returnDate"
              style="border:none;background:none;outline:none;font-family:'DM Sans',sans-serif;font-size:13px;flex:1;color:#374151;"
              min="{{ date('Y-m-d') }}" required>
          </div>
        </div>
      </div>
      <div id="dateError" style="color:#EF4444;font-size:13px;font-weight:600;display:none;margin-top:-8px;">
        ⚠ Tanggal kembali harus lebih besar atau sama dengan tanggal pinjam
      </div>

      <button type="submit" id="submitBtn"
        style="width:100%;padding:14px;background:var(--blue);color:white;border:none;
               border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;
               font-family:'DM Sans',sans-serif;margin-top:4px;transition:background .15s;"
        onmouseover="this.style.background='var(--blue-dark)'"
        onmouseout="this.style.background='var(--blue)'">
        Ajukan Peminjaman
      </button>
    </form>
  </div>
</div>

<script>
  const borrowDateInput = document.getElementById('borrowDate');
  const returnDateInput = document.getElementById('returnDate');
  const dateErrorDiv = document.getElementById('dateError');
  const submitBtn = document.getElementById('submitBtn');
  const form = borrowDateInput.closest('form');

  // Sync return_date minimum with borrow_date
  borrowDateInput.addEventListener('change', function() {
    const borrowDate = this.value;
    if (borrowDate) {
      returnDateInput.min = borrowDate;
      // Reset return_date if it's now invalid
      if (returnDateInput.value && returnDateInput.value < borrowDate) {
        returnDateInput.value = borrowDate;
        validateDates();
      }
    }
  });

  // Validate on return_date change
  returnDateInput.addEventListener('change', validateDates);

  function validateDates() {
    const borrowDate = borrowDateInput.value;
    const returnDate = returnDateInput.value;

    if (borrowDate && returnDate && returnDate < borrowDate) {
      dateErrorDiv.style.display = 'block';
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.6';
      submitBtn.style.cursor = 'not-allowed';
    } else {
      dateErrorDiv.style.display = 'none';
      submitBtn.disabled = false;
      submitBtn.style.opacity = '1';
      submitBtn.style.cursor = 'pointer';
    }
  }

  // Validate on form submit
  form.addEventListener('submit', function(e) {
    const borrowDate = borrowDateInput.value;
    const returnDate = returnDateInput.value;

    if (!borrowDate || !returnDate || returnDate < borrowDate) {
      e.preventDefault();
      dateErrorDiv.style.display = 'block';
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.6';
    }
  });

  // Initialize min date for return_date
  if (borrowDateInput.value) {
    returnDateInput.min = borrowDateInput.value;
  }
</script>

@endsection