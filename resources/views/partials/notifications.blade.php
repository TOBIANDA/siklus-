@php
    $notifRequests = \App\Models\BorrowRequest::with('book')
        ->whereHas('book', function ($q) {
            if (auth()->check()) {
                $q->where('user_id', auth()->id());
            }
        })
        ->where('dismissed_by_owner', false)
        ->orderByDesc('created_at')
        ->limit(10)
        ->get();
@endphp

<style>
.notif-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--gray-border, #E5E7EB);
    position: relative;
    transition: background .15s;
    text-decoration: none;
    color: inherit;
}
.notif-item:hover { background: var(--gray-light, #F9FAFB); }
.notif-item.unread { background: #EFF6FF; }
.notif-item.unread:hover { background: #DBEAFE; }

.notif-dismiss-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: none;
    background: transparent;
    color: #9CA3AF;
    font-size: 14px;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .15s, background .15s, color .15s;
    padding: 0;
}
.notif-item:hover .notif-dismiss-btn { opacity: 1; }
.notif-dismiss-btn:hover {
    background: #FEE2E2;
    color: #EF4444;
}

.notif-av {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366F1, #8B5CF6);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.notif-content { flex: 1; min-width: 0; padding-right: 20px; }
.notif-name { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
.notif-text { font-size: 12px; color: var(--gray, #6B7280); margin-bottom: 6px; }
.notif-book {
    display: flex; align-items: center; gap: 8px;
    background: var(--gray-light, #F3F4F6);
    border-radius: 8px; padding: 6px 8px;
    margin-bottom: 6px;
}
.notif-book-thumb {
    width: 28px; height: 36px; object-fit: cover;
    border-radius: 4px; background: #D1D5DB; flex-shrink: 0;
}
.notif-book-title { font-size: 11px; font-weight: 700; }
.notif-book-author { font-size: 11px; color: var(--gray, #6B7280); }
.notif-meta { display: flex; align-items: center; gap: 6px; }
.notif-time { font-size: 11px; color: var(--gray, #9CA3AF); }
.notif-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #EF4444; flex-shrink: 0;
}
.notif-action-btn {
    font-size: 11px; font-weight: 600; color: var(--blue, #2563EB);
    background: none; border: none; cursor: pointer;
    padding: 0; text-decoration: underline;
    font-family: 'DM Sans', sans-serif;
}
.notif-date-label {
    font-size: 11px; font-weight: 700; color: var(--gray, #6B7280);
    text-transform: uppercase; letter-spacing: .06em;
    padding: 10px 16px 4px;
}

/* Notif being removed */
.notif-item.removing {
    opacity: 0;
    transform: translateX(30px);
    transition: opacity .25s ease, transform .25s ease;
    pointer-events: none;
}
</style>

@if($notifRequests->isEmpty())
<div style="padding:40px 24px; text-align:center; color:var(--gray);">
    <div style="font-size:32px; margin-bottom:10px;">🔔</div>
    <div style="font-size:13px; font-weight:600; margin-bottom:4px;">Belum ada notifikasi</div>
    <div style="font-size:12px;">Permintaan peminjaman akan muncul di sini</div>
</div>
@else
<p class="notif-date-label">Terbaru</p>
@foreach($notifRequests as $req)
<div class="notif-item {{ !$req->read_by_owner ? 'unread' : '' }}" id="notif-item-{{ $req->id }}">

    {{-- Tombol hapus/dismiss --}}
    <button
        class="notif-dismiss-btn"
        title="Hapus notifikasi"
        onclick="dismissNotif({{ $req->id }}, '{{ route('borrow.dismiss', $req->id) }}', this)"
    >&#10005;</button>

    {{-- Avatar --}}
    <a href="{{ route('messages.show', urlencode($req->email)) }}"
       style="display:flex;align-items:flex-start;gap:12px;flex:1;text-decoration:none;color:inherit;">
        <div class="notif-av">
            <span style="font-size:15px; font-weight:700; color:white; line-height:1;">
                {{ strtoupper(substr($req->borrower_name ?? $req->full_name ?? '?', 0, 1)) }}
            </span>
        </div>

        {{-- Konten --}}
        <div class="notif-content">
            <div class="notif-name">{{ $req->borrower_name ?? $req->full_name ?? 'Anonim' }}</div>
            <div class="notif-text">Ingin meminjam buku milikmu</div>
            @if($req->book)
            <div class="notif-book">
                <img src="{{ $req->book->cover_url }}" class="notif-book-thumb"
                     onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
                <div>
                    <div class="notif-book-title">{{ $req->book->title }}</div>
                    <div class="notif-book-author">{{ $req->book->author }}</div>
                </div>
            </div>
            @endif
            <div class="notif-meta">
                <span class="notif-time">{{ $req->created_at->diffForHumans() }}</span>
                @if(!$req->read_by_owner)<div class="notif-dot"></div>@endif
            </div>
            <button class="notif-action-btn">Lihat Permintaan →</button>
        </div>
    </a>
</div>
@endforeach
@endif

<script>
function dismissNotif(id, url, btnEl) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrf) return;

    const item = document.getElementById('notif-item-' + id);
    if (!item) return;

    // Animasi keluar
    item.classList.add('removing');

    fetch(url, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN'    : csrf,
            'Accept'          : 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Hapus elemen setelah animasi selesai
            setTimeout(() => {
                item.remove();

                // Update badge merah di ikon lonceng
                const badge = document.querySelector('.btn-notif-toggle .notif-badge, #notifBadge');
                if (data.unread_count === 0) {
                    // Hapus titik merah
                    document.querySelectorAll('.btn-notif-toggle span[style*="background:#EF4444"]').forEach(el => el.remove());
                }

                // Kalau panel kosong, tampilkan pesan kosong
                const remaining = document.querySelectorAll('.notif-item:not(.removing)');
                if (remaining.length === 0) {
                    const scrollArea = document.querySelector('.notif-scroll-area');
                    if (scrollArea) {
                        scrollArea.innerHTML = `
                            <div style="padding:40px 24px;text-align:center;color:var(--gray);">
                                <div style="font-size:32px;margin-bottom:10px;">🔔</div>
                                <div style="font-size:13px;font-weight:600;margin-bottom:4px;">Belum ada notifikasi</div>
                                <div style="font-size:12px;">Permintaan peminjaman akan muncul di sini</div>
                            </div>`;
                    }
                }
            }, 260);
        } else {
            // Batalkan animasi jika gagal
            item.classList.remove('removing');
            if (window.showToast) showToast('Gagal menghapus notifikasi', true);
        }
    })
    .catch(() => {
        item.classList.remove('removing');
        if (window.showToast) showToast('Gagal terhubung ke server', true);
    });
}
</script>