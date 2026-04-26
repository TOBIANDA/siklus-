@extends('layouts.app')

@section('content')
<style>
/* ===== BOOK STATUS BADGES ===== */
.book-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 700;
    padding: 4px 10px; border-radius: 20px;
    margin-bottom: 8px;
}
.status-available { background: #D1FAE5; color: #065F46; }
.status-on-loan   { background: #FEF3C7; color: #92400E; }
.status-returned  { background: #E0E7FF; color: #3730A3; }

/* ===== MODAL OVERLAY (CSS :target trick) ===== */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.5); z-index: 200;
    align-items: center; justify-content: center;
}
.modal-overlay:target { display: flex; }

.modal-box {
    background: var(--white); border-radius: 20px;
    padding: 32px; position: relative;
    width: 480px; max-width: 95vw;
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 24px 60px rgba(0,0,0,.2);
    animation: popIn .22s cubic-bezier(.34,1.56,.64,1);
}
@keyframes popIn {
    from { transform: scale(.92); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}
.modal-close {
    position: absolute; top: 16px; right: 16px;
    width: 32px; height: 32px; display: flex;
    align-items: center; justify-content: center;
    border-radius: 8px; color: var(--gray);
    text-decoration: none; font-size: 18px;
    background: var(--gray-light); transition: background .15s;
}
.modal-close:hover { background: var(--gray-border); color: var(--dark); }

/* ===== FORM ===== */
.lent-form { display: flex; flex-direction: column; gap: 14px; }
.lent-form label {
    font-size: 11px; font-weight: 700; color: var(--gray);
    margin-bottom: 4px; display: block;
    text-transform: uppercase; letter-spacing: .06em;
}
.lent-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--gray-border);
    background: var(--gray-light); border-radius: 10px;
    font-family: 'DM Sans', sans-serif; font-size: 14px;
    outline: none; box-sizing: border-box;
    transition: border-color .15s, background .15s;
}
.lent-input:focus { border-color: var(--blue); background: #fff; }
.lent-textarea { resize: vertical; min-height: 80px; }
.lent-input-row { display: flex; gap: 12px; }
.lent-input-row > div { flex: 1; }

.lent-btn-row { display: flex; gap: 10px; margin-top: 4px; }
.lent-submit { flex: 1; padding: 12px; background: var(--blue); color: var(--white); border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .15s; }
.lent-submit:hover { background: var(--blue-dark); }
.lent-cancel { padding: 12px 18px; background: var(--gray-light); color: var(--gray); border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; text-decoration: none; display: flex; align-items: center; transition: background .15s; }
.lent-cancel:hover { background: var(--gray-border); }
.lent-delete-btn { flex: 1; padding: 12px; background: #EF4444; color: var(--white); border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .15s; }
.lent-delete-btn:hover { background: #DC2626; }

/* ===== CATALOG CARDS ===== */
.catalog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
    margin-top: 20px;
}
.catalog-card {
    background: var(--white); border: 1px solid var(--gray-border);
    border-radius: 14px; overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.catalog-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.1); }
.catalog-cover {
    width: 100%; height: 180px; object-fit: cover;
    background: linear-gradient(135deg, #1a3a5c, #2563EB);
    display: block;
}
.catalog-body { padding: 14px; }
.catalog-title { font-size: 14px; font-weight: 700; margin-bottom: 3px; }
.catalog-author { font-size: 12px; color: var(--gray); margin-bottom: 8px; }
.catalog-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; }
.catalog-category {
    font-size: 11px; font-weight: 700; padding: 3px 8px;
    border-radius: 6px; background: var(--blue-light); color: var(--blue);
}
.catalog-location { font-size: 11px; color: var(--gray); }
.catalog-actions { display: flex; gap: 8px; border-top: 1px solid var(--gray-border); padding-top: 10px; margin-top: 2px; }
.edit-btn {
    flex: 1; text-align: center; padding: 7px; font-size: 12px;
    font-weight: 600; background: var(--blue-light); color: var(--blue);
    border-radius: 8px; text-decoration: none; transition: background .15s;
}
.edit-btn:hover { background: #DBEAFE; }
.del-btn {
    flex: 1; text-align: center; padding: 7px; font-size: 12px;
    font-weight: 600; background: #FEE2E2; color: #DC2626;
    border-radius: 8px; text-decoration: none; transition: background .15s;
}
.del-btn:hover { background: #FECACA; }

.empty-state {
    text-align: center; padding: 60px 20px; color: var(--gray);
}
.empty-state .el { font-size: 48px; margin-bottom: 12px; }
.empty-state p { font-size: 15px; font-weight: 600; }
.empty-state small { font-size: 13px; }

/* ===== ALERT ===== */
.alert-success {
    background: #D1FAE5; color: #065F46; border-radius: 10px;
    padding: 12px 18px; font-size: 14px; font-weight: 600;
    margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
}
</style>

<div style="padding:24px;">

    {{-- TOP BAR --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <div class="borrow-tabs">
                <a href="{{ route('borrow') }}" class="borrow-tab" style="text-decoration:none;">Borrowed Books</a>
                <span class="borrow-tab active" style="cursor:default;">My Books</span>
            </div>
            <p style="font-size:12px; color:var(--gray); margin-top:6px;">Buku yang kamu bagikan ke komunitas Siklus</p>
        </div>
        <a href="#modal-create" class="add-btn" style="text-decoration:none;" title="Upload buku baru">+</a>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
    <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- STATS STRIP --}}
    <div class="borrow-stats" style="margin-bottom:28px;">
        <div class="borrow-stat">
            <div class="si"><img src="{{ asset('images/icon_closed_book.png') }}" alt="" style="width:28px;height:28px;object-fit:contain;"></div>
            <div class="sl">{{ $stats['books_loaned'] }} Books Loaned</div>
        </div>
        <div class="borrow-stat">
            <div class="si"><img src="{{ asset('images/icon_books_open.png') }}" alt="" style="width:28px;height:28px;object-fit:contain;"></div>
            <div class="sl">{{ $stats['on_loan'] }} On Loan</div>
        </div>
        <div class="borrow-stat">
            <div class="si"><img src="{{ asset('images/icon_clipboard.png') }}" alt="" style="width:28px;height:28px;object-fit:contain;"></div>
            <div class="sl">{{ $stats['pending'] }} Pending</div>
        </div>
    </div>

    {{-- ON LOAN SECTION --}}
    @php $onLoanCount = count(array_filter($items, fn($item) => $item['book_status'] === 'on_loan')); @endphp
    @if($onLoanCount > 0)
    <div class="bsection-title">On Loan</div>
    <div class="borrow-cards">
      @foreach($items as $item)
        @if($item['book_status'] === 'on_loan')
        <div class="borrow-card">
          <div class="bc-cover">
            <img src="{{ asset('images/' . $item['cover']) }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.outerHTML='&#128218;'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">&#128197; {{ $item['borrow_date'] ?? 'N/A' }}</div>
            <div class="date-row">&#128198; {{ $item['return_date'] ?? 'N/A' }}</div>
            <div class="msg-lender">
              <div class="lender-i">
                <div class="lender-av"></div>
                <span style="color:var(--gray);text-decoration:none;font-size:12px;">{{ $item['borrower_name'] ?? 'Borrower' }}</span>
              </div>
              <span style="font-size:16px;color:var(--gray);cursor:pointer">&#9992;</span>
            </div>
          </div>
          <span class="status-b s-onread">On Loan</span>
        </div>
        @endif
      @endforeach
    </div>
    @endif

    {{-- PENDING LOAN REQUEST SECTION --}}
    @php $pendingCount = count(array_filter($items, fn($item) => $item['pending_count'] > 0)); @endphp
    @if($pendingCount > 0)
    <div class="bsection-title">Pending Loan Request</div>
    <div class="borrow-cards">
      @foreach($items as $item)
        @if($item['pending_count'] > 0)
        <div class="borrow-card">
          <div class="bc-cover">
            <img src="{{ asset('images/' . $item['cover']) }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.outerHTML='&#128218;'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">📚 {{ $item['pending_count'] }} pending request(s)</div>
            <div class="msg-lender">
              <a href="{{ route('messages') }}" style="color:var(--blue);text-decoration:none;font-size:12px;font-weight:600;">View Requests →</a>
              <span style="font-size:16px;color:var(--gray);cursor:pointer">&#9992;</span>
            </div>
          </div>
          <span class="status-b s-appeal">Pending</span>
        </div>
        @endif
      @endforeach
    </div>
    @endif

    {{-- FINISHED LOANED SECTION --}}
    @php $finishedCount = count(array_filter($items, fn($item) => $item['book_status'] !== 'on_loan' && $item['pending_count'] === 0)); @endphp
    @if($finishedCount > 0)
    <div class="bsection-title">Finished Loaned</div>
    <div class="borrow-cards">
      @foreach($items as $item)
        @if($item['book_status'] !== 'on_loan' && $item['pending_count'] === 0)
        <div class="borrow-card">
          <div class="bc-cover">
            <img src="{{ asset('images/' . $item['cover']) }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.outerHTML='&#128218;'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">✅ Available</div>
            <div class="msg-lender">
              <span style="color:var(--gray);text-decoration:none;font-size:12px;">Ready to share</span>
              <span style="font-size:16px;color:var(--gray);cursor:pointer">&#9992;</span>
            </div>
          </div>
          <span class="status-b s-finish">Available</span>
        </div>
        @endif
      @endforeach
    </div>
    @endif

</div>

{{-- ====================================================================
     MODAL: CREATE
==================================================================== --}}
<div id="modal-create" class="modal-overlay">
    <a href="#" style="position:absolute;inset:0;z-index:0;display:block;"></a>
    <div class="modal-box" style="z-index:1;">
        <a href="#" class="modal-close" title="Tutup">&#10005;</a>
        <div style="font-size:20px; font-weight:700; margin-bottom:20px; text-align:center;">📖 Upload Buku ke Katalog</div>

        <form action="{{ route('lent.store') }}" method="POST" enctype="multipart/form-data" class="lent-form">
            @csrf
            <div>
                <label>Judul Buku</label>
                <input type="text" name="title" class="lent-input" placeholder="The Little Prince" required>
            </div>
            <div>
                <label>Penulis</label>
                <input type="text" name="author" class="lent-input" placeholder="Antoine de Saint-Exupéry" required>
            </div>
            <div class="lent-input-row">
                <div>
                    <label>Kategori</label>
                    <select name="category" class="lent-input" required>
                        <option value="">-- Pilih --</option>
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Komik">Komik</option>
                        <option value="Biografi">Biografi</option>
                        <option value="Pengembangan Diri">Pengembangan Diri</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>
                <div>
                    <label>Lokasi COD</label>
                    <input type="text" name="location" class="lent-input" placeholder="Malang, Dinoyo">
                </div>
            </div>
            <div>
                <label>Deskripsi / Sinopsis</label>
                <textarea name="description" class="lent-input lent-textarea" placeholder="Ceritakan sedikit tentang buku ini..."></textarea>
            </div>
            <div>
                <label>Cover Buku (opsional)</label>
                <input type="file" name="cover" class="lent-input" accept="image/*">
            </div>
            <div class="lent-btn-row">
                <a href="#" class="lent-cancel">Batal</a>
                <button type="submit" class="lent-submit">Upload Buku</button>
            </div>
        </form>
    </div>
</div>

{{-- ====================================================================
     MODAL: EDIT & DELETE per book
==================================================================== --}}
@foreach($books as $book)

{{-- EDIT --}}
<div id="modal-edit-{{ $book->id }}" class="modal-overlay">
    <a href="#" style="position:absolute;inset:0;z-index:0;display:block;"></a>
    <div class="modal-box" style="z-index:1;">
        <a href="#" class="modal-close" title="Tutup">&#10005;</a>
        <div style="font-size:20px; font-weight:700; margin-bottom:20px; text-align:center;">✏️ Edit Buku</div>

        <form action="{{ route('lent.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="lent-form">
            @csrf @method('PUT')
            <div>
                <label>Judul Buku</label>
                <input type="text" name="title" class="lent-input" value="{{ $book->title }}" required>
            </div>
            <div>
                <label>Penulis</label>
                <input type="text" name="author" class="lent-input" value="{{ $book->author }}" required>
            </div>
            <div class="lent-input-row">
                <div>
                    <label>Kategori</label>
                    <select name="category" class="lent-input" required>
                        @foreach(['Fiksi','Non-Fiksi','Akademik','Komik','Biografi','Pengembangan Diri','Umum'] as $cat)
                        <option value="{{ $cat }}" {{ $book->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Lokasi COD</label>
                    <input type="text" name="location" class="lent-input" value="{{ $book->location }}">
                </div>
            </div>
            <div>
                <label>Deskripsi / Sinopsis</label>
                <textarea name="description" class="lent-input lent-textarea">{{ $book->description }}</textarea>
            </div>
            <div>
                <label>Ganti Cover (opsional)</label>
                <input type="file" name="cover" class="lent-input" accept="image/*">
            </div>
            <div class="lent-btn-row">
                <a href="#" class="lent-cancel">Batal</a>
                <button type="submit" class="lent-submit">Perbarui</button>
            </div>
        </form>
    </div>
</div>

{{-- DELETE --}}
<div id="modal-del-{{ $book->id }}" class="modal-overlay">
    <a href="#" style="position:absolute;inset:0;z-index:0;display:block;"></a>
    <div class="modal-box" style="width:380px; z-index:1;">
        <a href="#" class="modal-close" title="Tutup">&#10005;</a>
        <div style="font-size:20px; font-weight:700; margin-bottom:16px; text-align:center; color:#DC2626;">🗑 Hapus Buku</div>
        <p style="font-size:15px; color:#374151; text-align:center; line-height:1.6; margin-bottom:24px;">
            Yakin hapus <strong>{{ $book->title }}</strong> dari katalog?<br>
            <span style="font-size:12px; color:var(--gray);">Buku tidak akan bisa lagi ditemukan di Home atau Search.</span>
        </p>
        <form action="{{ route('lent.destroy', $book->id) }}" method="POST">
            @csrf @method('DELETE')
            <div class="lent-btn-row">
                <a href="#" class="lent-cancel">Batal</a>
                <button type="submit" class="lent-delete-btn">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@endforeach

@endsection