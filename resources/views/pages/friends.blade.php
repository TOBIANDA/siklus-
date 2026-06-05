@extends('layouts.app')

@section('content')
<style>
/* ===== FRIENDS PAGE ===== */
.friends-page { padding: 24px; max-width: 1200px; }

/* === HEADER === */
.friends-page-header {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 28px;
}
.friends-back-btn {
    width: 36px; height: 36px; flex-shrink: 0;
    background: var(--blue); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; text-decoration: none; font-size: 18px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, .35);
    transition: transform .15s, box-shadow .15s;
}
.friends-back-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 6px 16px rgba(37, 99, 235, .45);
    color: #fff; text-decoration: none;
}
.friends-title {
    font-size: 22px; font-weight: 800;
    color: var(--dark); margin: 0;
}

/* === STATS === */
.friends-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 28px;
}
.friends-stat-card {
    background: var(--white);
    border: 1px solid var(--gray-border);
    border-radius: 16px;
    padding: 18px 22px;
    display: flex; align-items: center; justify-content: space-between;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    transition: box-shadow .2s, transform .2s;
    min-height: 84px;
}
.friends-stat-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,.09);
    transform: translateY(-2px);
}
.friends-stat-label {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: var(--gray); margin-bottom: 6px;
}
.friends-stat-value {
    font-size: 32px; font-weight: 800; color: var(--dark); line-height: 1;
}
.friends-stat-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

/* === SECTION TITLE === */
.fsection-title {
    font-size: 14px; font-weight: 700; color: var(--dark);
    margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
}
.fsection-count {
    font-size: 11px; font-weight: 700;
    background: var(--blue-light); color: var(--blue);
    padding: 2px 8px; border-radius: 20px;
}

/* === 2-COL GRID === */
.friends-list-grid,
.pending-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
    margin-bottom: 32px;
}
@media (min-width: 768px) {
    .friends-list-grid,
    .pending-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* === FRIEND CARD === */
.friend-card,
.pending-card {
    background: var(--white);
    border: 1px solid var(--gray-border);
    border-radius: 14px;
    padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    transition: box-shadow .2s, transform .2s;
    position: relative;
    min-width: 0;
    min-height: 78px;
}
.friend-card:hover,
.pending-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,.09);
    transform: translateY(-2px);
}
.friend-avatar {
    width: 54px; height: 54px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
    background: var(--gray-light);
}
.friend-info { flex: 1; min-width: 0; }
.friend-name,
.pending-name {
    font-size: 15px; font-weight: 700; color: var(--dark);
    margin-bottom: 4px; line-height: 1.25;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.friend-occupation,
.pending-sub {
    font-size: 12px; color: var(--gray); line-height: 1.4;
    margin-bottom: 6px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
}
.friend-status {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: #10B981;
}
.friend-status-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #10B981;
    flex-shrink: 0;
}
.friend-status.is-away { color: #F59E0B; }
.friend-status.is-away .friend-status-dot { background: #F59E0B; }
.friend-status.is-pending { color: #94A3B8; }
.friend-status.is-pending .friend-status-dot { background: #94A3B8; }

.friend-card-actions {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    flex-shrink: 0; align-self: center;
}
.friend-action-btn {
    width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #2563EB, #1D4ED8);
    color: white; display: flex; align-items: center; justify-content: center;
    text-decoration: none; font-size: 17px; border: none; cursor: pointer;
    transition: transform .2s, box-shadow .2s;
    box-shadow: 0 4px 12px rgba(37, 99, 235, .35);
}
.friend-action-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 18px rgba(37, 99, 235, .5);
    color: #fff; text-decoration: none;
}
.friend-remove-btn {
    width: 24px; height: 24px; border-radius: 50%;
    background: transparent; color: var(--gray-border);
    border: none; cursor: pointer; font-size: 12px;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .15s, background .15s, color .15s;
}
.friend-card:hover .friend-remove-btn { opacity: 1; }
.friend-remove-btn:hover { background: #FEE2E2; color: #DC2626; }

/* === PENDING ACTIONS === */
.pending-actions {
    display: flex; flex-direction: column; gap: 6px;
    flex-shrink: 0; align-self: center;
}
@media (max-width: 520px) {
    .pending-card { flex-wrap: wrap; }
    .pending-actions { width: 100%; flex-direction: row; justify-content: flex-end; }
}
.btn-accept {
    padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 700;
    background: linear-gradient(135deg, #2563EB, #1D4ED8); color: white;
    border: none; cursor: pointer; transition: transform .15s, box-shadow .15s;
    white-space: nowrap;
}
.btn-accept:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.35); }
.btn-decline {
    padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 700;
    background: var(--gray-light); color: var(--gray);
    border: none; cursor: pointer; transition: background .15s;
    white-space: nowrap;
}
.btn-decline:hover { background: #FEE2E2; color: #DC2626; }

/* === SEARCH BAR === */
.friends-search-wrap { margin-bottom: 24px; }
.friends-search-input {
    width: 100%; padding: 12px 18px;
    border: 1.5px solid var(--gray-border); border-radius: 12px;
    font-size: 14px; font-family: 'Lato', sans-serif;
    background: var(--gray-light); color: var(--dark); outline: none;
    box-sizing: border-box; transition: border-color .15s, background .15s;
}
.friends-search-input::placeholder { color: var(--gray); }
.friends-search-input:focus { border-color: var(--blue); background: var(--white); }
.friends-search-results {
    margin-top: 10px; display: flex; flex-direction: column; gap: 8px;
}
.search-user-card {
    background: var(--white); border: 1px solid var(--gray-border);
    border-radius: 12px; padding: 12px 16px;
    display: flex; align-items: center; gap: 12px;
    transition: box-shadow .2s;
}
.search-user-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.search-user-name { font-size: 14px; font-weight: 700; color: var(--dark); }
.search-user-sub  { font-size: 12px; color: var(--gray); }

/* === EMPTY STATE === */
.friends-empty {
    text-align: center; padding: 56px 20px; color: var(--gray);
}
.friends-empty .el { font-size: 56px; margin-bottom: 14px; }
.friends-empty p { font-size: 16px; font-weight: 700; color: var(--dark); }
.friends-empty small { font-size: 13px; color: var(--gray); }

/* === ALERTS === */
.alert-success-friends {
    background: #D1FAE5; color: #065F46; border-radius: 10px;
    padding: 12px 18px; font-size: 14px; font-weight: 600;
    margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
    border-left: 4px solid #10B981;
}
.alert-error-friends {
    background: #FEE2E2; color: #991B1B; border-radius: 10px;
    padding: 12px 18px; font-size: 14px; font-weight: 600;
    margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
    border-left: 4px solid #EF4444;
}

/* ===== DARK MODE ===== */
[data-theme="dark"] .friend-card,
[data-theme="dark"] .friends-stat-card,
[data-theme="dark"] .pending-card,
[data-theme="dark"] .search-user-card {
    background: #1E2433; border-color: #334155;
    box-shadow: 0 2px 10px rgba(0,0,0,.25);
}
[data-theme="dark"] .friend-card:hover,
[data-theme="dark"] .pending-card:hover,
[data-theme="dark"] .friends-stat-card:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,.4);
}
[data-theme="dark"] .friends-title,
[data-theme="dark"] .fsection-title,
[data-theme="dark"] .friend-name,
[data-theme="dark"] .pending-name,
[data-theme="dark"] .friends-stat-value { color: #F1F5F9; }
[data-theme="dark"] .friend-occupation,
[data-theme="dark"] .pending-sub,
[data-theme="dark"] .friends-stat-label { color: #94A3B8; }
[data-theme="dark"] .friends-empty p { color: #F1F5F9; }
[data-theme="dark"] .friends-empty small { color: #94A3B8; }
[data-theme="dark"] .friends-search-input {
    background: #2A3045; border-color: #334155; color: #F1F5F9;
}
[data-theme="dark"] .friends-search-input::placeholder { color: #64748B; }
[data-theme="dark"] .friends-search-input:focus { background: #1E2433; border-color: #60A5FA; }
[data-theme="dark"] .btn-decline { background: #2A3045; color: #94A3B8; }
[data-theme="dark"] .btn-decline:hover { background: rgba(239,68,68,.15); color: #F87171; }
[data-theme="dark"] .friend-remove-btn { color: #475569; }
[data-theme="dark"] .friend-remove-btn:hover { background: rgba(239,68,68,.15); color: #F87171; }
[data-theme="dark"] .friends-stat-icon { background: #1E3A5F !important; }
[data-theme="dark"] .friends-stats { align-items: stretch; }
[data-theme="dark"] .search-user-name { color: #F1F5F9; }
[data-theme="dark"] .search-user-sub { color: #94A3B8; }
</style>

<div class="friends-page">

    {{-- HEADER --}}
    <div class="friends-page-header">
        <a href="{{ route('home') }}" class="friends-back-btn" title="{{ __('common.back') }}">←</a>
        <h1 class="friends-title">My Friends</h1>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
    <div class="alert-success-friends"><span style="color:#10B981;font-weight:800;">✓</span> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-error-friends"><span style="color:#DC2626;font-weight:800;">✕</span> {{ session('error') }}</div>
    @endif

    {{-- STATS --}}
    <div class="friends-stats">
        <div class="friends-stat-card">
            <div>
                <div class="friends-stat-label">BOOKS LISTED</div>
                <div class="friends-stat-value">{{ $totalBooksListed }}</div>
            </div>
            <div class="friends-stat-icon" style="background:#EFF6FF;">
                <img src="{{ asset('images/Group 159.png') }}" alt="Books Listed" style="width:22px;height:22px;object-fit:contain;">
            </div>
        </div>
        <div class="friends-stat-card">
            <div>
                <div class="friends-stat-label">EXCHANGES</div>
                <div class="friends-stat-value">{{ $totalExchanges }}</div>
            </div>
            <div class="friends-stat-icon" style="background:#ECFDF5;">
                <img src="{{ asset('images/refresh-cw.png') }}" alt="Exchanges" style="width:22px;height:22px;object-fit:contain;">
            </div>
        </div>
    </div>

    {{-- MY FRIENDS LIST --}}
    @if($friends->isEmpty() && $pendingReceived->isEmpty() && $pendingSent->isEmpty())
    <div class="friends-empty">
        <div class="el">👤</div>
        <p>Belum ada teman</p>
        <small>Cari pengguna di bawah untuk mulai berteman dan berbagi buku bersama</small>
    </div>
    @elseif($friends->isNotEmpty())
    <div class="fsection-title">
        <span style="color:var(--blue);">👥</span> Teman Saya
        <span class="fsection-count">{{ $friends->count() }}</span>
    </div>
    <div class="friends-list-grid">
        @foreach($friends as $friend)
        @php
            $friendRecord = \App\Models\Friend::where(function($q) use ($friend) {
                $q->where('user_id', auth()->id())->where('friend_id', $friend->id);
            })->orWhere(function($q) use ($friend) {
                $q->where('user_id', $friend->id)->where('friend_id', auth()->id());
            })->first();
            $isOnline = ($friend->id % 3) !== 0;
        @endphp
        <div class="friend-card">
            <img src="{{ $friend->avatar_url }}" alt="{{ $friend->name }}"
                 class="friend-avatar"
                 onerror="this.src='{{ asset('images/avatar_user.png') }}'">
            <div class="friend-info">
                <div class="friend-name">{{ $friend->name }}</div>
                <div class="friend-occupation">{{ $friend->occupation ?? 'Pengguna Siklus' }}</div>
                <div class="friend-status {{ $isOnline ? '' : 'is-away' }}">
                    <span class="friend-status-dot"></span>
                    {{ $isOnline ? 'Online' : 'Offline' }}
                </div>
            </div>
            <div class="friend-card-actions">
                <a href="{{ route('messages.show', $friend->id) }}" class="friend-action-btn" title="Kirim Pesan">✈</a>
                @if($friendRecord)
                <form action="{{ route('friends.destroy', $friendRecord->id) }}" method="POST"
                      onsubmit="return confirm('Hapus {{ $friend->name }} dari daftar teman?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="friend-remove-btn" title="Hapus teman">✕</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- PENDING REQUESTS (Received) --}}
    @if($pendingReceived->count() > 0)
    <div class="fsection-title">
        <span style="color:var(--blue);">⊕</span> Permintaan Masuk
        <span class="fsection-count">{{ $pendingReceived->count() }}</span>
    </div>
    <div class="pending-grid">
        @foreach($pendingReceived as $req)
        <div class="pending-card">
            <img src="{{ $req->user->avatar_url }}" alt="{{ $req->user->name }}"
                 class="friend-avatar"
                 onerror="this.src='{{ asset('images/avatar_user.png') }}'">
            <div class="friend-info">
                <div class="pending-name">{{ $req->user->name }}</div>
                <div class="pending-sub">{{ $req->user->occupation ?? 'Pengguna Siklus' }}</div>
                <div class="friend-status is-pending">
                    <span class="friend-status-dot"></span>
                    Permintaan masuk
                </div>
            </div>
            <div class="pending-actions">
                <form action="{{ route('friends.accept', $req->id) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-accept">Terima</button>
                </form>
                <form action="{{ route('friends.reject', $req->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-decline">Tolak</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- PENDING REQUESTS (Sent) --}}
    @if($pendingSent->count() > 0)
    <div class="fsection-title">
        <span style="color:var(--blue);">⧖</span> Permintaan Terkirim
        <span class="fsection-count">{{ $pendingSent->count() }}</span>
    </div>
    <div class="pending-grid" style="margin-bottom:32px;">
        @foreach($pendingSent as $req)
        <div class="pending-card">
            <img src="{{ $req->friend->avatar_url }}" alt="{{ $req->friend->name }}"
                 class="friend-avatar"
                 onerror="this.src='{{ asset('images/avatar_user.png') }}'">
            <div class="friend-info">
                <div class="pending-name">{{ $req->friend->name }}</div>
                <div class="pending-sub">{{ $req->friend->occupation ?? 'Pengguna Siklus' }}</div>
                <div class="friend-status is-away">
                    <span class="friend-status-dot"></span>
                    Menunggu konfirmasi
                </div>
            </div>
            <div class="pending-actions">
                <form action="{{ route('friends.reject', $req->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-decline">Batalkan</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- FIND FRIENDS --}}
    <div class="fsection-title" style="margin-top:8px;">
        <span style="color:var(--blue);">⌕</span> Cari Teman
    </div>
    <div class="friends-search-wrap">
        <input type="text"
               id="friendSearchInput"
               class="friends-search-input"
               placeholder="Ketik nama pengguna untuk mencari...">
        <div class="friends-search-results" id="friendSearchResults"></div>
    </div>

</div>

<script>
(function() {
    'use strict';

    const input   = document.getElementById('friendSearchInput');
    const results = document.getElementById('friendSearchResults');
    let debounceTimer;

    if (!input) return;

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();

        if (q.length < 2) {
            results.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => searchUsers(q), 300);
    });

    async function searchUsers(q) {
        try {
            const res  = await fetch(`/friends/search?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (!data.users || data.users.length === 0) {
                results.innerHTML = `<div style="font-size:13px;color:var(--gray);padding:12px 4px;">Tidak ada pengguna ditemukan.</div>`;
                return;
            }

            results.innerHTML = data.users.map(u => `
                <div class="search-user-card">
                    <img src="${escHtml(u.avatar_url)}" alt="${escHtml(u.name)}"
                         class="friend-avatar" style="width:52px;height:52px;"
                         onerror="this.src='{{ asset('images/avatar_user.png') }}'">
                    <div style="flex:1;min-width:0;">
                        <div class="search-user-name">${escHtml(u.name)}</div>
                        <div class="search-user-sub">${escHtml(u.occupation || 'Pengguna Siklus')}</div>
                    </div>
                    ${u.friend_status === 'accepted'
                        ? `<span style="font-size:12px;font-weight:700;color:#10B981;white-space:nowrap;">✓ Teman</span>`
                        : u.friend_status === 'pending'
                        ? `<span style="font-size:12px;font-weight:700;color:var(--gray);white-space:nowrap;">⏳ Tertunda</span>`
                        : `<form action="/friends" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="friend_id" value="${u.id}">
                            <button type="submit" class="btn-accept">+ Tambah</button>
                           </form>`
                    }
                </div>
            `).join('');

        } catch (e) {
            results.innerHTML = `<div style="font-size:13px;color:var(--gray);padding:12px 4px;">Gagal mencari pengguna.</div>`;
        }
    }

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
})();
</script>
@endsection
