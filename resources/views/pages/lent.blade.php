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
.modal-overlay:target, .modal-overlay.show { display: flex; }

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

/* ===== INLINE VALIDATION ===== */
.lent-input.field-error {
    border-color: #EF4444 !important;
    background: #FFF5F5 !important;
    box-shadow: 0 0 0 3px rgba(239,68,68,.12) !important;
    animation: lent-shake .3s ease;
}
@keyframes lent-shake {
    0%,100% { transform: translateX(0); }
    25%     { transform: translateX(-5px); }
    75%     { transform: translateX(5px); }
}
.lent-field-error-msg {
    font-size: 12px; color: #DC2626; font-weight: 600;
    margin-top: 4px; display: none;
    align-items: center; gap: 4px;
}
.lent-field-error-msg.show { display: flex; }
</style>

<div style="padding:24px;">

    {{-- TOP BAR --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#2563EB,#1D4ED8);border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(37,99,235,.3);">
                <span style="font-size:16px;">📚</span>
            </div>
            <span style="font-size:20px;font-weight:800;color:var(--dark);">{{ __('lent.lent_books') }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="borrow-tabs">
                <a href="{{ route('borrow') }}" class="borrow-tab">{{ __('lent.borrowed_books') }}</a>
                <span class="borrow-tab active" style="cursor:default;">{{ __('lent.lent_books') }}</span>
            </div>
            <a href="{{ route('lent.create') }}" class="add-btn" style="text-decoration:none;" title="{{ __('lent.upload_new_book') }}">+</a>
        </div>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
    <div style="background:#D1FAE5;color:#065F46;border-radius:10px;padding:12px 18px;font-size:14px;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:8px;border-left:4px solid #10B981;">
        <span style="color:#10B981;font-weight:700;">✓</span> {{ session('success') }}
    </div>
    @endif

    {{-- STATS --}}
    <div class="borrow-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:28px;">
        <div class="borrow-stat">
            <div class="borrow-stat-left">
                <div class="borrow-stat-label">{{ __('lent.books_loaned') }}</div>
                <div class="borrow-stat-value">{{ $stats['books_loaned'] }}</div>
            </div>
            <div class="borrow-stat-icon" style="background:#EFF6FF;">
                <img src="{{ asset('images/solar_book-broken.png') }}" alt="Books Loaned" style="width:24px;height:24px;object-fit:contain;">
            </div>
        </div>
        <div class="borrow-stat">
            <div class="borrow-stat-left">
                <div class="borrow-stat-label">{{ __('lent.on_loan') }}</div>
                <div class="borrow-stat-value">{{ $stats['on_loan'] }}</div>
            </div>
            <div class="borrow-stat-icon" style="background:#FFF7ED;">
                <img src="{{ asset('images/Group 207.png') }}" alt="On Loan" style="width:24px;height:24px;object-fit:contain;">
            </div>
        </div>
        <div class="borrow-stat">
            <div class="borrow-stat-left">
                <div class="borrow-stat-label">Pending Request</div>
                <div class="borrow-stat-value">{{ $stats['pending'] }}</div>
            </div>
            <div class="borrow-stat-icon" style="background:#ECFDF5;">
                <img src="{{ asset('images/Group 206.png') }}" alt="Pending" style="width:24px;height:24px;object-fit:contain;">
            </div>
        </div>
    </div>

    {{-- ON LOAN SECTION --}}
    @php $onLoanCount = count(array_filter($items, fn($item) => $item['book_status'] === 'on_loan')); @endphp
    @if($onLoanCount > 0)
    <div class="bsection-title">{{ __('lent.on_loan') }}</div>
    <div class="borrow-cards" id="lent-section-onloan">
      @foreach($items as $item)
        @if($item['book_status'] === 'on_loan')
        <div class="borrow-card" data-item-id="{{ $item['id'] ?? '' }}" data-book-id="{{ $item['book_id'] ?? '' }}">
          <div class="bc-cover">
            <img src="{{ asset('images/' . $item['cover']) }}" alt="{{ $item['title'] }}"
                 onerror="this.outerHTML='📚'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">📅 {{ $item['borrow_date'] ?? 'N/A' }}</div>
            <div class="date-row">🔁 {{ $item['return_date'] ?? 'N/A' }}</div>
            <div class="msg-lender">
              <div class="lender-i">
                <div class="lender-av"></div>
                <span>{{ $item['borrower_name'] ?? 'Borrower' }}</span>
              </div>
              <a href="{{ route('messages') }}" class="lender-msg-btn" title="Pesan">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              </a>
            </div>
          </div>
          {{-- STATUS PICKER (lender dapat ubah manual) --}}
          <div class="status-picker-wrap">
            <button type="button"
                    class="status-b s-onread status-picker-trigger"
                    data-book-id="{{ $item['book_id'] ?? $item['id'] }}"
                    data-request-id="{{ $item['request_id'] ?? '' }}"
                    aria-haspopup="listbox"
                    aria-expanded="false"
                    title="Ubah status">
              <span class="status-picker-label">{{ __('lent.on_loan') }}</span>
              <span class="status-picker-chevron" aria-hidden="true">▾</span>
            </button>
            <div class="status-picker-menu" role="listbox" hidden>
              <button type="button" class="status-opt s-onread" data-status="on_loan" role="option">{{ __('lent.on_loan') }}</button>
              <button type="button" class="status-opt s-available" data-status="available" role="option">Tersedia</button>
            </div>
          </div>
        </div>
        @endif
      @endforeach
    </div>
    @endif

    {{-- PENDING LOAN REQUEST SECTION --}}
    @php $pendingCount = count(array_filter($items, fn($item) => $item['pending_count'] > 0)); @endphp
    @if($pendingCount > 0)
    <div class="bsection-title">{{ __('lent.pending_requests') }}</div>
    <div class="borrow-cards">
      @foreach($items as $item)
        @if($item['pending_count'] > 0)
        <div class="borrow-card">
          <div class="bc-cover">
            <img src="{{ asset('images/' . $item['cover']) }}" alt="{{ $item['title'] }}"
                 onerror="this.outerHTML='📚'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">🔔 {{ $item['pending_count'] }} {{ __('lent.pending_request_count') }}</div>
            <div class="msg-lender">
              <a href="{{ route('messages') }}" style="color:var(--blue);text-decoration:none;font-size:11px;font-weight:600;">{{ __('lent.view_requests') }} →</a>
            </div>
          </div>
          <span class="status-b s-appeal">{{ __('messages.pending') }}</span>
        </div>
        @endif
      @endforeach
    </div>
    @endif

    {{-- FINISHED LOANED SECTION --}}
    @php $finishedCount = count(array_filter($items, fn($item) => $item['book_status'] !== 'on_loan' && $item['pending_count'] === 0)); @endphp
    @if($finishedCount > 0)
    <div class="bsection-title">{{ __('lent.finished_loaned') }}</div>
    <div class="borrow-cards" id="lent-section-finished">
      @foreach($items as $item)
        @if($item['book_status'] !== 'on_loan' && $item['pending_count'] === 0)
        <div class="borrow-card" data-item-id="{{ $item['id'] ?? '' }}" data-book-id="{{ $item['book_id'] ?? '' }}">
          <div class="bc-cover">
            <img src="{{ asset('images/' . $item['cover']) }}" alt="{{ $item['title'] }}"
                 onerror="this.outerHTML='📚'">
          </div>
          <div class="bc-info">
            <div class="bc-title">{{ $item['title'] }}</div>
            <div class="bc-author">{{ $item['author'] }}</div>
            <div class="date-row">✓ {{ __('lent.available') }}</div>
            <div class="msg-lender">
              <span style="color:var(--gray);font-size:11px;">{{ __('lent.ready_to_share') }}</span>
            </div>
          </div>
          <div class="status-picker-wrap">
            <button type="button"
                    class="status-b s-available status-picker-trigger"
                    data-book-id="{{ $item['book_id'] ?? $item['id'] }}"
                    data-request-id="{{ $item['request_id'] ?? '' }}"
                    aria-haspopup="listbox"
                    aria-expanded="false"
                    title="Ubah status">
              <span class="status-picker-label">{{ __('lent.available') }}</span>
              <span class="status-picker-chevron" aria-hidden="true">▾</span>
            </button>
            <div class="status-picker-menu" role="listbox" hidden>
              <button type="button" class="status-opt s-onread" data-status="on_loan" role="option">{{ __('lent.on_loan') }}</button>
              <button type="button" class="status-opt s-available" data-status="available" role="option">Tersedia</button>
            </div>
          </div>
        </div>
        @endif
      @endforeach
    </div>
    @endif

    {{-- ===== KATALOG SEMUA BUKU SAYA ===== --}}
    <div style="margin-top:32px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <div class="bsection-title" style="margin-bottom:0;">Katalog Buku Saya</div>
            <span style="font-size:12px;color:var(--gray);">{{ $books->count() }} buku terdaftar</span>
        </div>

        @if($books->isEmpty())
        <div class="empty-state">
            <p>Belum ada buku di katalog</p>
            <small>Klik tombol <strong>+</strong> di atas untuk menambahkan buku pertamamu</small>
        </div>
        @else
        <div class="catalog-grid" id="catalogGrid">
            @foreach($books as $book)
            <div class="catalog-card" id="catalog-card-{{ $book->id }}">
                <img src="{{ asset('images/' . ($book->cover ?? '')) }}"
                     alt="{{ $book->title }}"
                     class="catalog-cover"
                     onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
                <div class="catalog-body">
                    <div class="catalog-title">{{ $book->title }}</div>
                    <div class="catalog-author">{{ $book->author }}</div>
                    <div class="catalog-meta">
                        <span class="catalog-category" style="display:flex;align-items:center;gap:4px;">
                            <img src="{{ asset('images/chart-column-stacked.png') }}" alt="Genre" style="width:11px;height:11px;object-fit:contain;">
                            {{ $book->category }}
                        </span>
                        @if($book->location)
                        <span class="catalog-location">{{ $book->location }}</span>
                        @endif
                    </div>
                    <div style="margin-bottom:10px;">
                        <div style="font-size:11px;font-weight:700;color:var(--gray);margin-bottom:4px;text-transform:uppercase;letter-spacing:.06em;">Status</div>
                        <span class="book-status-badge {{ $book->book_status_class }}">{{ $book->book_status_label }}</span>
                    </div>
                    <div class="catalog-actions">
                        <a href="#modal-edit-{{ $book->id }}" class="edit-btn">Edit</a>
                        <a href="#modal-del-{{ $book->id }}" class="del-btn">Hapus</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- ====================================================================
     MODAL: CREATE
==================================================================== --}}
<div id="modal-create" class="modal-overlay">
    <a href="#" style="position:absolute;inset:0;z-index:0;display:block;"></a>
    <div class="modal-box" style="z-index:1;">
        <a href="#" class="modal-close" title="{{ __('common.close') }}">×</a>
        <div style="font-size:20px; font-weight:700; margin-bottom:20px; text-align:center;">{{ __('lent.upload_new_book') }}</div>

        <form action="{{ route('lent.store') }}" method="POST" enctype="multipart/form-data" class="lent-form" id="form-create-book">
            @csrf
            <div>
                <label>{{ __('lent.title') }}</label>
                <input type="text" name="title" id="create-title" class="lent-input" placeholder="The Little Prince">
                <div class="lent-field-error-msg" id="create-title-err">Judul buku wajib diisi</div>
            </div>
            <div>
                <label>{{ __('lent.author') }}</label>
                <input type="text" name="author" id="create-author" class="lent-input" placeholder="Antoine de Saint-Exupéry">
                <div class="lent-field-error-msg" id="create-author-err">Nama penulis wajib diisi</div>
            </div>
            <div class="lent-input-row">
                <div>
                    <label>{{ __('lent.category') }}</label>
                    <select name="category" id="create-category" class="lent-input">
                        <option value="">-- {{ __('common.select') }} --</option>
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Komik">Komik</option>
                        <option value="Biografi">Biografi</option>
                        <option value="Pengembangan Diri">Pengembangan Diri</option>
                        <option value="Umum">Umum</option>
                    </select>
                    <div class="lent-field-error-msg" id="create-category-err">Kategori wajib dipilih</div>
                </div>
                <div>
                    <label>{{ __('lent.location') }}</label>
                    <input type="text" name="location" class="lent-input" placeholder="Malang, Dinoyo">
                </div>
            </div>
            <div>
                <label>{{ __('lent.description') }}</label>
                <textarea name="description" class="lent-input lent-textarea" placeholder="{{ __('lent.description_placeholder') }}" maxlength="500"></textarea>
            </div>
            <div>
                <label>{{ __('lent.cover_optional') }}</label>
                <input type="file" name="cover" class="lent-input" accept="image/jpeg,image/png,image/webp,image/jpg">
            </div>
            <div class="lent-btn-row">
                <a href="#" class="lent-cancel">{{ __('common.cancel') }}</a>
                <button type="submit" class="lent-submit">{{ __('lent.upload_new_book') }}</button>
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
                    <label>Lokasi</label>
                    <input type="text" name="location" class="lent-input" value="{{ $book->location }}">
                </div>
            </div>
            <div>
                <label>Deskripsi / Sinopsis</label>
                <textarea name="description" class="lent-input lent-textarea" maxlength="500">{{ $book->description }}</textarea>
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

{{-- ================================================================
     AJAX SUBMIT — Form Tambah Buku (fetch, no page reload)
================================================================ --}}
<script>
(function () {
    'use strict';

    const form       = document.getElementById('form-create-book');
    const catalogGrid = document.getElementById('catalogGrid');
    const csrfToken  = document.querySelector('meta[name="csrf-token"]').content;

    if (!form) return;

    // Check if we need to open the modal automatically from session
    @if(session('open_add'))
        window.location.hash = 'modal-create';
    @endif

    // ===== Validation helpers =====
    function showErr(inputEl, msgEl, msg) {
        if (inputEl) inputEl.classList.add('field-error');
        if (msgEl) { msgEl.textContent = msg; msgEl.classList.add('show'); }
    }
    function clearErr(inputEl, msgEl) {
        if (inputEl) inputEl.classList.remove('field-error');
        if (msgEl) msgEl.classList.remove('show');
    }

    // Clear errors on input
    [['create-title','create-title-err'],['create-author','create-author-err'],['create-category','create-category-err']]
        .forEach(([id, errId]) => {
            const el = document.getElementById(id);
            const errEl = document.getElementById(errId);
            if (el) {
                el.addEventListener('input',  () => clearErr(el, errEl));
                el.addEventListener('change', () => clearErr(el, errEl));
            }
        });

    function validateCreateForm() {
        let valid = true;
        const title    = document.getElementById('create-title');
        const author   = document.getElementById('create-author');
        const category = document.getElementById('create-category');
        const titleErr    = document.getElementById('create-title-err');
        const authorErr   = document.getElementById('create-author-err');
        const categoryErr = document.getElementById('create-category-err');

        if (!title || !title.value.trim()) {
            showErr(title, titleErr, 'Judul buku wajib diisi'); valid = false;
        } else { clearErr(title, titleErr); }

        if (!author || !author.value.trim()) {
            showErr(author, authorErr, 'Nama penulis wajib diisi'); valid = false;
        } else { clearErr(author, authorErr); }

        if (!category || !category.value) {
            showErr(category, categoryErr, 'Kategori wajib dipilih'); valid = false;
        } else { clearErr(category, categoryErr); }

        return valid;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Client-side validation
        if (!validateCreateForm()) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        const origText  = submitBtn.textContent;
        submitBtn.disabled    = true;
        submitBtn.textContent = 'Menyimpan...';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method : 'POST',
                headers: {
                    'Accept'          : 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN'    : csrfToken,
                },
                body: formData,
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // ✅ Tutup modal & reset form
                window.location.hash = '#';
                form.reset();

                // ✅ Inject card baru ke catalog grid tanpa reload
                if (catalogGrid) {
                    // Hapus empty-state jika ada
                    const emptyState = document.querySelector('.empty-state');
                    if (emptyState) emptyState.remove();

                    const book    = data.book;
                    const cardHtml = `
                    <div class="catalog-card" id="catalog-card-${book.id}" style="animation:popIn .3s ease;">
                        <img src="${escHtml(book.cover_url)}"
                             alt="${escHtml(book.title)}"
                             class="catalog-cover"
                             onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
                        <div class="catalog-body">
                            <div class="catalog-title">${escHtml(book.title)}</div>
                            <div class="catalog-author">${escHtml(book.author)}</div>
                            <div class="catalog-meta">
                                <span class="catalog-category">${escHtml(book.category)}</span>
                                ${book.location ? `<span class="catalog-location">📍 ${escHtml(book.location)}</span>` : ''}
                            </div>
                            <div style="margin-bottom:10px;">
                                <span class="book-status-badge status-available">Tersedia</span>
                            </div>
                            <div class="catalog-actions">
                                <span class="edit-btn" style="cursor:default;opacity:.5;">✏️ Edit</span>
                                <span class="del-btn"  style="cursor:default;opacity:.5;">🗑 Hapus</span>
                            </div>
                        </div>
                    </div>`;

                    catalogGrid.insertAdjacentHTML('afterbegin', cardHtml);
                }

                // ✅ Toast sukses
                if (window.showToast) showToast(data.message);

            } else {
                // Show server validation errors
                if (data.errors) {
                    const title    = document.getElementById('create-title');
                    const author   = document.getElementById('create-author');
                    const category = document.getElementById('create-category');
                    if (data.errors.title)    showErr(title,    document.getElementById('create-title-err'),    data.errors.title[0]);
                    if (data.errors.author)   showErr(author,   document.getElementById('create-author-err'),   data.errors.author[0]);
                    if (data.errors.category) showErr(category, document.getElementById('create-category-err'), data.errors.category[0]);
                }
                const errors = data.errors
                    ? Object.values(data.errors).flat().join(', ')
                    : (data.message || 'Terjadi kesalahan.');
                if (window.showToast) showToast(errors, true);
            }

        } catch (err) {
            console.error(err);
            if (window.showToast) showToast('Gagal terhubung ke server.', true);
        } finally {
            submitBtn.disabled    = false;
            submitBtn.textContent = origText;
        }
    });

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
})();
</script>

{{-- ================================================================
     STATUS PICKER — On Loan cards (Lender dapat ubah manual)
================================================================ --}}
<script>
(function () {
    'use strict';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    function closeAllPickers(except) {
        document.querySelectorAll('.status-picker-wrap.open').forEach(wrap => {
            if (wrap !== except) {
                wrap.classList.remove('open');
                const menu = wrap.querySelector('.status-picker-menu');
                const trigger = wrap.querySelector('.status-picker-trigger');
                if (menu) menu.hidden = true;
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('.status-picker-trigger');
        const option  = e.target.closest('.status-opt');

        if (trigger) {
            e.stopPropagation();
            const wrap = trigger.closest('.status-picker-wrap');
            const menu = wrap.querySelector('.status-picker-menu');
            const isOpen = wrap.classList.contains('open');
            closeAllPickers();
            if (!isOpen) {
                wrap.classList.add('open');
                menu.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
            }
            return;
        }

        if (option) {
            e.stopPropagation();
            const wrap    = option.closest('.status-picker-wrap');
            const trigger = wrap.querySelector('.status-picker-trigger');
            const bookId  = trigger.dataset.bookId;
            const newStatus = option.dataset.status;
            updateBookStatus(bookId, newStatus, wrap, option);
            closeAllPickers();
            return;
        }

        closeAllPickers();
    });

    const statusMap = {
        on_loan:   { cls: 's-onread',    label: 'Sedang Dipinjam' },
        available: { cls: 's-available', label: 'Tersedia' },
    };

    async function updateBookStatus(bookId, newStatus, wrap, optionEl) {
        const trigger = wrap.querySelector('.status-picker-trigger');
        const card    = wrap.closest('.borrow-card');

        try {
            const response = await fetch(`/lent/${bookId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ book_status: newStatus }),
            });

            const data = await response.json();
            if (!response.ok) {
                if (window.showToast) showToast(data.message || 'Gagal mengubah status.', true);
                return;
            }

            // Update tampilan badge
            const mapped = statusMap[newStatus] || { cls: 's-finish', label: newStatus };
            trigger.className = `status-b ${mapped.cls} status-picker-trigger`;
            trigger.querySelector('.status-picker-label').textContent = mapped.label;

            if (newStatus === 'available') {
                const finishedSection = document.getElementById('lent-section-finished');
                if (!finishedSection) {
                    location.reload();
                    return;
                }
                if (card.parentElement !== finishedSection) {
                    finishedSection.prepend(card);
                }
            } else if (newStatus === 'on_loan') {
                const onLoanSection = document.getElementById('lent-section-onloan');
                if (!onLoanSection) {
                    location.reload();
                    return;
                }
                if (card.parentElement !== onLoanSection) {
                    onLoanSection.prepend(card);
                }
                location.reload();
                return;
            }

            if (window.showToast) showToast('Status buku diperbarui.');
        } catch (err) {
            console.error(err);
            if (window.showToast) showToast('Gagal terhubung ke server.', true);
        }
    }
})();
</script>

@endsection