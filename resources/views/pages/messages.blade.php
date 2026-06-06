@extends('layouts.app')

@section('content')
<meta name="user-id" content="{{ auth()->id() }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
/* Reset layout padding for this page to allow full height */
.page {
    padding: 0 !important;
    overflow: hidden !important;
}

/* ===== MESSAGES LAYOUT ===== */
.inbox-wrap {
    display: flex;
    height: calc(100vh - 61px);
    padding: 0;
    gap: 0;
}

/* --- Sidebar / thread list --- */
.inbox-sidebar {
    width: 300px;
    flex-shrink: 0;
    border-right: 1px solid var(--gray-border);
    background: var(--white);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.inbox-sidebar-header {
    padding: 20px 20px 12px;
    border-bottom: 1px solid var(--gray-border);
    flex-shrink: 0;
}
.inbox-sidebar-header h2 { font-size: 20px; font-weight: 700; }
.inbox-sidebar-sub { font-size: 12px; color: var(--gray); margin-top: 3px; }
.inbox-list { flex: 1; overflow-y: auto; }

.inbox-thread {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--gray-border);
    cursor: pointer; text-decoration: none; color: inherit;
    transition: background .15s; position: relative;
}
.inbox-thread:hover { background: var(--gray-light); }
.inbox-thread.active { background: var(--blue-light); }
.inbox-thread.unread { background: #F0F7FF; }
.inbox-thread.unread .inbox-thread-name { font-weight: 800; }

.inbox-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0; overflow: hidden;
    font-weight: 700; color: white; font-size: 16px;
}
.inbox-avatar img { width: 100%; height: 100%; object-fit: cover; }

.inbox-thread-info { flex: 1; min-width: 0; }
.inbox-thread-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.inbox-thread-preview { font-size: 12px; color: var(--gray); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
.inbox-thread-time { font-size: 10px; color: var(--gray); flex-shrink: 0; margin-top: 2px; }
.inbox-unread-dot {
    width: 8px; height: 8px; background: var(--blue); border-radius: 50%;
    position: absolute; top: 16px; right: 14px;
}

.inbox-empty {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 12px; color: var(--gray); padding: 40px;
    text-align: center;
}
.inbox-empty .ie-icon { font-size: 48px; }
.inbox-empty p { font-size: 14px; font-weight: 600; }
.inbox-empty small { font-size: 12px; }

/* --- Chat / Detail area --- */
.inbox-detail {
    flex: 1; display: flex; flex-direction: column;
    background: #F9FAFB; overflow: hidden;
}
.inbox-detail-header {
    padding: 16px 24px; background: var(--white);
    border-bottom: 1px solid var(--gray-border);
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.inbox-detail-title { font-size: 16px; font-weight: 700; }
.inbox-detail-sub { font-size: 12px; color: var(--gray); margin-top: 2px; }

.inbox-detail-body { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 16px; }

/* --- REQUEST CARD --- */
.req-card {
    background: var(--white); border: 1px solid var(--gray-border);
    border-radius: 16px; padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.req-card-header {
    display: flex; align-items: center; gap: 12px; margin-bottom: 16px;
}
.req-borrower-av {
    width: 48px; height: 48px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 700; color: white; flex-shrink: 0;
}
.req-borrower-name { font-size: 15px; font-weight: 700; }
.req-borrower-sub { font-size: 12px; color: var(--gray); }

.req-book-row {
    display: flex; gap: 12px; align-items: center;
    background: var(--gray-light); border-radius: 12px;
    padding: 12px; margin-bottom: 16px;
}
.req-book-cover {
    width: 48px; height: 64px; border-radius: 6px;
    object-fit: cover;
    background: linear-gradient(135deg,#1a3a5c,#2563EB);
    flex-shrink: 0;
}
.req-book-title { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
.req-book-author { font-size: 12px; color: var(--gray); }

.req-info-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px; margin-bottom: 16px;
}
.req-info-item label {
    font-size: 10px; font-weight: 700; color: var(--gray);
    text-transform: uppercase; letter-spacing: .06em;
    display: block; margin-bottom: 3px;
}
.req-info-item span {
    font-size: 13px; font-weight: 600; color: var(--dark);
}

.req-message-box {
    background: #EFF6FF; border-radius: 10px; padding: 12px 14px;
    font-size: 13px; color: #1e40af; line-height: 1.6;
    margin-bottom: 16px; border-left: 3px solid var(--blue);
}

.req-status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; padding: 4px 10px;
    border-radius: 20px; margin-bottom: 16px;
}
.req-status-pending  { background: #FEF3C7; color: #92400E; }
.req-status-approved { background: #D1FAE5; color: #065F46; }
.req-status-rejected { background: #FEE2E2; color: #991B1B; }
.req-status-returned { background: #E0E7FF; color: #3730A3; }

.req-actions { display: flex; gap: 10px; }
.btn-approve {
    flex: 1; padding: 11px; background: var(--blue); color: white;
    border: none; border-radius: 10px; font-size: 14px; font-weight: 600;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    transition: background .15s;
}
.btn-approve:hover { background: var(--blue-dark); }
.btn-reject {
    flex: 1; padding: 11px; background: #FEE2E2; color: #DC2626;
    border: none; border-radius: 10px; font-size: 14px; font-weight: 600;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    transition: background .15s;
}
.btn-reject:hover { background: #FECACA; }
.btn-returned {
    flex: 1; padding: 11px; background: #D1FAE5; color: #065F46;
    border: none; border-radius: 10px; font-size: 14px; font-weight: 600;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    transition: background .15s;
}
.btn-returned:hover { background: #A7F3D0; }

/* --- Chat bubble for messages --- */
.chat-bubble-row { display: flex; gap: 10px; align-items: flex-start; }
.chat-bubble-row.self { flex-direction: row-reverse; }
.chat-bubble-wrapper {
    display: flex; flex-direction: column;
    max-width: 65%; min-width: 0;
}
.chat-bubble-row.self .chat-bubble-wrapper { align-items: flex-end; }
.chat-bubble {
    padding: 12px 16px; border-radius: 16px;
    font-size: 14px; line-height: 1.5;
    word-break: break-word;
    width: fit-content; max-width: 100%;
}
.chat-bubble.recv {
    background: var(--white); border: 1px solid var(--gray-border);
    border-radius: 4px 16px 16px 16px;
}
.chat-bubble.sent {
    background: var(--blue); color: white;
    border-radius: 16px 4px 16px 16px;
}
.chat-time { font-size: 11px; color: var(--gray); margin-top: 4px; }
.chat-time.r { text-align: right; }

/* --- Input area --- */
.inbox-input-area {
    padding: 14px 20px; background: var(--white);
    border-top: 1px solid var(--gray-border);
    display: flex; align-items: center; gap: 10px;
    flex-shrink: 0;
}
.inbox-input {
    flex: 1; background: var(--gray-light); border: none;
    border-radius: 24px; padding: 10px 16px;
    font-family: 'DM Sans', sans-serif; font-size: 14px; outline: none;
}
.inbox-send-btn {
    width: 40px; height: 40px; background: none; border: none;
    border-radius: 0; cursor: pointer; display: flex;
    align-items: center; justify-content: center; padding: 4px;
    transition: opacity .15s;
}
.inbox-send-btn:hover { opacity: 0.75; }

/* Alert */
.alert-success {
    background: #D1FAE5; color: #065F46; border-radius: 10px;
    padding: 10px 16px; font-size: 13px; font-weight: 600;
    margin: 12px 20px 0; display: flex; align-items: center; gap: 8px;
    flex-shrink: 0;
}
.no-detail {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 12px; color: var(--gray); text-align: center;
}

/* ===== MESSAGES DARK MODE ===== */
[data-theme="dark"] .inbox-sidebar {
    background: #1E2433; border-color: #334155;
}
[data-theme="dark"] .inbox-sidebar-header {
    border-color: #334155;
}
[data-theme="dark"] .inbox-sidebar-header h2 { color: #F1F5F9; }
[data-theme="dark"] .inbox-sidebar-sub { color: #64748B; }
[data-theme="dark"] .inbox-thread {
    border-color: #334155; color: #F1F5F9;
}
[data-theme="dark"] .inbox-thread:hover { background: #2A3045; }
[data-theme="dark"] .inbox-thread.active { background: rgba(37,99,235,.15); }
[data-theme="dark"] .inbox-thread.unread { background: rgba(37,99,235,.08); }
[data-theme="dark"] .inbox-thread-name { color: #F1F5F9; }
[data-theme="dark"] .inbox-thread-preview { color: #64748B; }
[data-theme="dark"] .inbox-thread-time { color: #64748B; }

[data-theme="dark"] .inbox-detail {
    background: #0F1623;
}
[data-theme="dark"] .inbox-detail-header {
    background: #1E2433; border-color: #334155;
}
[data-theme="dark"] .inbox-detail-title { color: #F1F5F9; }
[data-theme="dark"] .inbox-detail-sub { color: #64748B; }

/* Request card */
[data-theme="dark"] .req-card {
    background: #1E2433;
    border-color: #334155;
    box-shadow: 0 4px 20px rgba(0,0,0,.3);
}
[data-theme="dark"] .req-borrower-name { color: #F1F5F9; }
[data-theme="dark"] .req-borrower-sub { color: #94A3B8; }
[data-theme="dark"] .req-book-row {
    background: #2A3045; border: 1px solid #334155;
}
[data-theme="dark"] .req-book-title { color: #F1F5F9; }
[data-theme="dark"] .req-book-author { color: #94A3B8; }
[data-theme="dark"] .req-info-item label { color: #64748B; }
[data-theme="dark"] .req-info-item span { color: #F1F5F9; }
[data-theme="dark"] .req-message-box {
    background: rgba(37,99,235,.12);
    border-left-color: #60A5FA;
    color: #93C5FD;
}

/* Status badges in dark */
[data-theme="dark"] .req-status-pending  { background: rgba(251,191,36,.15); color: #FCD34D; }
[data-theme="dark"] .req-status-approved { background: rgba(16,185,129,.15); color: #6EE7B7; }
[data-theme="dark"] .req-status-rejected { background: rgba(239,68,68,.15);  color: #FCA5A5; }
[data-theme="dark"] .req-status-returned { background: rgba(99,102,241,.15); color: #A5B4FC; }

/* Action buttons in dark */
[data-theme="dark"] .btn-reject  { background: rgba(220,38,38,.15); color: #FCA5A5; }
[data-theme="dark"] .btn-reject:hover { background: rgba(220,38,38,.25); }
[data-theme="dark"] .btn-returned { background: rgba(16,185,129,.15); color: #6EE7B7; }
[data-theme="dark"] .btn-returned:hover { background: rgba(16,185,129,.25); }

/* Chat bubbles */
[data-theme="dark"] .chat-bubble.recv {
    background: #2A3045; border-color: #334155; color: #F1F5F9;
}
[data-theme="dark"] .chat-time { color: #64748B; }

/* Input area */
[data-theme="dark"] .inbox-input-area {
    background: #1E2433; border-color: #334155;
}
[data-theme="dark"] .inbox-input {
    background: #2A3045; color: #F1F5F9;
}
[data-theme="dark"] .inbox-input::placeholder { color: #64748B; }
[data-theme="dark"] .act-btn { color: #64748B; }

/* No detail panel */
[data-theme="dark"] .no-detail p { color: #F1F5F9; }
[data-theme="dark"] .no-detail small { color: #64748B; }
[data-theme="dark"] .inbox-empty p { color: #F1F5F9; }
[data-theme="dark"] .inbox-empty small { color: #64748B; }
</style>

<div class="inbox-wrap {{ isset($activeRequest) && $activeRequest ? 'has-active' : '' }}">

    {{-- ============ SIDEBAR ============ --}}
    <div class="inbox-sidebar">
        <div class="inbox-sidebar-header">
            <h2>{{ __('messages.title') }}</h2>
            <div class="inbox-sidebar-sub">{{ __('messages.message_requests') }}</div>
        </div>
        <div class="inbox-list">
            @forelse($requests as $req)
            @php
                $myId = auth()->id();
                $isOwner = $req->book->user_id === $myId;
                $partnerName = $isOwner ? ($req->borrower_name ?? $req->full_name ?? 'Anonim') : $req->book->owner_name;
                $partnerEmail = $isOwner ? $req->email : ($req->book->user->email ?? 'unknown');
                
                $isActive  = isset($activeRequest) && (
                    $isOwner ? ($activeRequest->email === $req->email) : (($activeRequest->book->user->email ?? '') === $partnerEmail)
                );
                
                $isUnread  = $isOwner && !$req->read_by_owner;
                $initials  = strtoupper(substr($partnerName, 0, 1));
            @endphp
            <a href="{{ route('messages.show', urlencode($partnerEmail)) }}"
               class="inbox-thread {{ $isActive ? 'active' : '' }} {{ $isUnread && !$isActive ? 'unread' : '' }}">
                <div class="inbox-avatar">{{ $initials }}</div>
                <div class="inbox-thread-info">
                    <div class="inbox-thread-name">
                        {{ $partnerName }}
                        @if(!$isOwner) <span style="font-size:10px;background:var(--gray-light);padding:2px 6px;border-radius:4px;color:var(--gray);margin-left:4px;">Pemilik</span> @endif
                    </div>
                    <div class="inbox-thread-preview">{{ $req->book?->title ?? 'Buku tidak ditemukan' }}</div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                    <span class="inbox-thread-time">{{ $req->created_at->diffForHumans() }}</span>
                    @if($isUnread && !$isActive)
                        <span class="inbox-unread-dot" style="position:static;"></span>
                    @endif
                </div>
            </a>
            @empty
            <div class="inbox-empty">
                <p>Belum ada pesan</p>
                <small>Permintaan peminjaman buku kamu akan muncul di sini</small>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ============ DETAIL / CHAT AREA ============ --}}
    <div class="inbox-detail">

        @if(session('success'))
        <div class="alert-success" style="background:#D1FAE5;color:#065F46;display:flex;align-items:center;gap:8px;">
    <span style="color:#10B981;font-weight:700;font-size:16px;">✓</span>
    {{ session('success') }}
  </div>
        @endif

        @if(isset($activeRequest) && $activeRequest)
        @php
            $req      = $activeRequest;
            $myId     = auth()->id();
            $isOwner  = $req->book->user_id === $myId;
            
            $borrower = $req->borrower_name ?? $req->full_name ?? 'Anonim';
            $partnerName  = $isOwner ? $borrower : $req->book->owner_name;
            $partnerEmail = $isOwner ? $req->email : ($req->book->user->email ?? '');
            $partnerPhone = $isOwner ? $req->phone : '-';
            
            $initials = strtoupper(substr($partnerName, 0, 1));
            $book     = $req->book;
        @endphp

        {{-- Header --}}
        <div class="inbox-detail-header">
            {{-- Tombol Back (mobile only) --}}
            <a href="{{ route('messages') }}" class="inbox-detail-back" style="display:none;" title="Kembali">←</a>
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="inbox-avatar" style="width:40px;height:40px;font-size:14px;">{{ $initials }}</div>
                <div>
                    <div class="inbox-detail-title">
                        {{ $partnerName }}
                        @if(!$isOwner) <span style="font-size:10px;background:var(--gray-light);padding:2px 6px;border-radius:4px;color:var(--gray);margin-left:4px;">Pemilik Buku</span> @endif
                    </div>
                    <div class="inbox-detail-sub">{{ $partnerEmail }}</div>
                </div>
            </div>
            <div>
                @if($req->status === 'pending')
                    <span class="req-status-badge req-status-pending">Menunggu Konfirmasi</span>
                @elseif($req->status === 'approved')
                    <span class="req-status-badge req-status-approved">Disetujui</span>
                @elseif($req->status === 'rejected')
                    <span class="req-status-badge req-status-rejected">Ditolak</span>
                @elseif($req->status === 'returned')
                    <span class="req-status-badge req-status-returned">Dikembalikan</span>
                @endif
            </div>
        </div>

        {{-- Body --}}
        <div class="inbox-detail-body">

            {{-- REQUEST CARD --}}
            <div class="req-card">
                {{-- Borrower info --}}
                <div class="req-card-header">
                    <div class="req-borrower-av" style="font-size:12px;width:32px;height:32px;">{{ strtoupper(substr($borrower, 0, 1)) }}</div>
                    <div>
                        <div class="req-borrower-name">{{ $borrower }}</div>
                        <div class="req-borrower-sub">Peminjam &bull; {{ $req->email }}</div>
                    </div>
                </div>

                {{-- Book row --}}
                @if($book)
                <div class="req-book-row">
                    <img src="{{ $book->cover_url }}" class="req-book-cover"
                         onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
                    <div>
                        <div class="req-book-title">{{ $book->title }}</div>
                        <div class="req-book-author">{{ $book->author }}</div>
                        @if($book->location)
                        <div style="font-size:11px;color:var(--gray);margin-top:4px;">📍 {{ $book->location }}</div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Date info --}}
                <div class="req-info-grid">
                    <div class="req-info-item">
                        <label>Tanggal Pinjam</label>
                        <span>{{ $req->borrow_date->format('d M Y') }}</span>
                    </div>
                    <div class="req-info-item">
                        <label>Tanggal Kembali</label>
                        <span>{{ $req->return_date->format('d M Y') }}</span>
                    </div>
                    <div class="req-info-item">
                        <label>Durasi</label>
                        <span>{{ $req->borrow_date->diffInDays($req->return_date) }} hari</span>
                    </div>
                    <div class="req-info-item">
                        <label>Dikirim</label>
                        <span>{{ $req->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                {{-- Message from borrower --}}
                @if($req->message)
                <div class="req-message-box">
                    <em>"{{ $req->message }}"</em>
                </div>
                @endif

                {{-- Action buttons --}}
                @if($isOwner)
                    @if($req->status === 'pending')
                    <div class="req-actions">
                        <form action="{{ route('borrow.approve', $req->id) }}" method="POST" style="flex:1;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-approve" style="width:100%;">Setujui</button>
                        </form>
                        <form action="{{ route('borrow.reject', $req->id) }}" method="POST" style="flex:1;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-reject" style="width:100%;">Tolak</button>
                        </form>
                    </div>
                    @elseif($req->status === 'approved')
                    <div class="req-actions">
                        <form action="{{ route('borrow.returned', $req->id) }}" method="POST" style="flex:1;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-returned" style="width:100%;">Tandai Sudah Dikembalikan</button>
                        </form>
                    </div>
                    <p style="font-size:12px;color:var(--gray);text-align:center;margin-top:10px;">
                        Tekan tombol ini setelah buku diterima kembali
                    </p>
                    @elseif($req->status === 'rejected')
                    <p style="font-size:13px;color:var(--gray);text-align:center;padding:12px;background:var(--gray-light);border-radius:10px;">
                        Permintaan ini sudah ditolak
                    </p>
                    @elseif($req->status === 'returned')
                    <p style="font-size:13px;color:#065F46;text-align:center;padding:12px;background:#D1FAE5;border-radius:10px;">
                        Buku sudah dikembalikan. Terima kasih!
                    </p>
                    @endif
                @else
                    {{-- Actions seen by Borrower --}}
                    @if($req->status === 'pending')
                    <p style="font-size:13px;color:var(--yellow);text-align:center;padding:12px;background:#FEF3C7;border-radius:10px;">
                        Pemilik sedang mempertimbangkan permintaanmu
                    </p>
                    @elseif($req->status === 'approved')
                    <p style="font-size:13px;color:var(--blue);text-align:center;padding:12px;background:var(--blue-light);border-radius:10px;font-weight:600;">
                        Permintaan disetujui! Silakan hubungi pemilik
                    </p>
                    @elseif($req->status === 'rejected')
                    <p style="font-size:13px;color:var(--gray);text-align:center;padding:12px;background:var(--gray-light);border-radius:10px;">
                        Maaf, pemilik menolak permintaan ini
                    </p>
                    @elseif($req->status === 'returned')
                    <p style="font-size:13px;color:#065F46;text-align:center;padding:12px;background:#D1FAE5;border-radius:10px;">
                        Buku telah sukses kamu kembalikan!
                    </p>
                    @endif
                @endif
            </div>

            {{-- Simulated chat bubbles for context --}}
            @if($req->message)
            <div class="chat-bubble-row {{ !$isOwner ? 'self' : '' }}">
                @if($isOwner)<div class="inbox-avatar" style="width:32px;height:32px;font-size:12px;flex-shrink:0;">{{ strtoupper(substr($borrower, 0, 1)) }}</div>@endif
                <div class="chat-bubble-wrapper">
                    <div class="chat-bubble {{ !$isOwner ? 'sent' : 'recv' }}">{{ $req->message }}</div>
                    <div class="chat-time {{ !$isOwner ? 'r' : '' }}">{{ $req->created_at->addSeconds(30)->format('H:i') }}</div>
                </div>
            </div>
            @endif

            @if($req->status === 'approved')
            <div class="chat-bubble-row {{ $isOwner ? 'self' : '' }}">
                @if(!$isOwner)<div class="inbox-avatar" style="width:32px;height:32px;font-size:12px;flex-shrink:0;">{{ strtoupper(substr($req->book->owner_name, 0, 1)) }}</div>@endif
                <div class="chat-bubble-wrapper">
                    <div class=\"chat-bubble {{ $isOwner ? 'sent' : 'recv' }}\">Halo! Permintaanmu sudah saya setujui. Kita bisa COD di lokasi yang kamu sebutkan ya!</div>
                    <div class="chat-time {{ $isOwner ? 'r' : '' }}">{{ $req->updated_at->format('H:i') }}</div>
                </div>
            </div>
            @elseif($req->status === 'rejected')
            <div class="chat-bubble-row {{ $isOwner ? 'self' : '' }}">
                @if(!$isOwner)<div class="inbox-avatar" style="width:32px;height:32px;font-size:12px;flex-shrink:0;">{{ strtoupper(substr($req->book->owner_name, 0, 1)) }}</div>@endif
                <div class="chat-bubble-wrapper">
                    <div class="chat-bubble {{ $isOwner ? 'sent' : 'recv' }}" style="background:#EF4444;color:white;">Maaf, permintaan peminjaman ini tidak bisa saya setujui saat ini.</div>
                    <div class="chat-time {{ $isOwner ? 'r' : '' }}">{{ $req->updated_at->format('H:i') }}</div>
                </div>
            </div>
            @endif

        </div>

        {{-- Input area --}}
        <div class="inbox-input-area">
            <button class="act-btn" style="background:none;border:none;cursor:pointer;padding:4px;display:flex;align-items:center;justify-content:center;">
                <img src="{{ asset('images/Container (2).png') }}" alt="Add" style="width:24px;height:24px;object-fit:contain;">
            </button>
            <input class="inbox-input" type="text" placeholder="Tulis pesan...">
            <button class="inbox-send-btn">
                <img src="{{ asset('images/Button.png') }}" alt="Send" style="width:20px;height:20px;object-fit:contain;">
            </button>
        </div>
        <div class="secured" style="text-align:center;font-size:10px;color:var(--gray);padding:6px;letter-spacing:.08em;text-transform:uppercase;">
            Secured by Siklus Community Protocol
        </div>

        @else
        {{-- No thread selected --}}
        <div class="no-detail">
            <div style="font-size:56px;width:64px;height:64px;background:var(--gray-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;"><span style="font-size:28px;color:var(--gray);">—</span></div>
            <p style="font-size:16px;font-weight:600;color:var(--dark);">Pilih percakapan</p>
            <p style="font-size:14px;color:var(--gray);">Klik salah satu permintaan di sebelah kiri untuk melihat detail</p>
        </div>
        @endif

    </div>
</div>

@endsection

@vite(['resources/js/app.js'])

@if(isset($activeRequest) && $activeRequest)
<script>
  @php
    $myId = auth()->id();
    $isOwner = $activeRequest->book->user_id === $myId;
    if ($isOwner) {
        // I am the book owner → recipient is the borrower
        $recipientId = $activeRequest->user_id 
            ?? \App\Models\User::where('email', $activeRequest->email)->value('id');
    } else {
        // I am the borrower → recipient is the book owner
        $recipientId = $activeRequest->book->user_id;
    }
  @endphp
  
  window.chatConfig = {
    currentUserId: {{ auth()->id() }},
    recipientId: {{ $recipientId ?? 'null' }}
  };
</script>
@endif
