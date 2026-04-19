@extends('layouts.app')

@section('content')
<style>
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
.chat-bubble {
    max-width: 65%; padding: 12px 16px; border-radius: 16px;
    font-size: 14px; line-height: 1.5;
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
    width: 40px; height: 40px; background: var(--blue); border: none;
    border-radius: 50%; cursor: pointer; display: flex;
    align-items: center; justify-content: center; color: white; font-size: 16px;
}

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
</style>

<div class="inbox-wrap">

    {{-- ============ SIDEBAR ============ --}}
    <div class="inbox-sidebar">
        <div class="inbox-sidebar-header">
            <h2>Messages</h2>
            <div class="inbox-sidebar-sub">Permintaan peminjaman buku kamu</div>
        </div>
        <div class="inbox-list">
            @forelse($requests as $req)
            @php
                $isActive  = isset($activeRequest) && $activeRequest->email === $req->email;
                $isUnread  = !$req->read_by_owner;
                $initials  = strtoupper(substr($req->borrower_name ?? $req->full_name, 0, 1));
            @endphp
            <a href="{{ route('messages.show', urlencode($req->email)) }}"
               class="inbox-thread {{ $isActive ? 'active' : '' }} {{ $isUnread && !$isActive ? 'unread' : '' }}">
                <div class="inbox-avatar">{{ $initials }}</div>
                <div class="inbox-thread-info">
                    <div class="inbox-thread-name">{{ $req->borrower_name ?? $req->full_name }}</div>
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
                <div class="ie-icon">💬</div>
                <p>Belum ada pesan</p>
                <small>Permintaan peminjaman buku kamu akan muncul di sini</small>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ============ DETAIL / CHAT AREA ============ --}}
    <div class="inbox-detail">

        @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        @if(isset($activeRequest) && $activeRequest)
        @php
            $req       = $activeRequest;
            $initials  = strtoupper(substr($req->borrower_name ?? $req->full_name, 0, 1));
            $book      = $req->book;
        @endphp

        {{-- Header --}}
        <div class="inbox-detail-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="inbox-avatar" style="width:40px;height:40px;font-size:14px;">{{ $initials }}</div>
                <div>
                    <div class="inbox-detail-title">{{ $req->borrower_name ?? $req->full_name }}</div>
                    <div class="inbox-detail-sub">{{ $req->email }} • {{ $req->phone }}</div>
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
                    <div class="req-borrower-av">{{ $initials }}</div>
                    <div>
                        <div class="req-borrower-name">{{ $req->borrower_name ?? $req->full_name }}</div>
                        <div class="req-borrower-sub">{{ $req->email }} • {{ $req->phone }}</div>
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
            </div>

            {{-- Simulated chat bubbles for context --}}
            <div class="chat-bubble-row">
                <div class="inbox-avatar" style="width:32px;height:32px;font-size:12px;flex-shrink:0;">{{ $initials }}</div>
                <div>
                    <div class="chat-bubble recv">
                        Halo! Saya tertarik meminjam buku "{{ $book?->title }}". Apakah masih tersedia?
                    </div>
                    <div class="chat-time">{{ $req->created_at->format('H:i') }}</div>
                </div>
            </div>

            @if($req->message)
            <div class="chat-bubble-row">
                <div class="inbox-avatar" style="width:32px;height:32px;font-size:12px;flex-shrink:0;">{{ $initials }}</div>
                <div>
                    <div class="chat-bubble recv">{{ $req->message }}</div>
                    <div class="chat-time">{{ $req->created_at->addSeconds(30)->format('H:i') }}</div>
                </div>
            </div>
            @endif

            @if($req->status === 'approved')
            <div class="chat-bubble-row self">
                <div>
                    <div class="chat-bubble sent">Halo! Permintaanmu sudah saya setujui. Kita bisa COD di lokasi yang kamu sebutkan ya! 📚</div>
                    <div class="chat-time r">{{ $req->updated_at->format('H:i') }}</div>
                </div>
            </div>
            @elseif($req->status === 'rejected')
            <div class="chat-bubble-row self">
                <div>
                    <div class="chat-bubble sent" style="background:#EF4444;">Maaf, permintaan peminjaman ini tidak bisa saya setujui saat ini.</div>
                    <div class="chat-time r">{{ $req->updated_at->format('H:i') }}</div>
                </div>
            </div>
            @endif

        </div>

        {{-- Input area --}}
        <div class="inbox-input-area">
            <button class="act-btn">&#10133;</button>
            <input class="inbox-input" type="text" placeholder="Tulis pesan...">
            <button class="inbox-send-btn">&#9658;</button>
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
