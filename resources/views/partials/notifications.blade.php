@php
    $notifRequests = \App\Models\BorrowRequest::with('book')
        ->whereHas('book', function ($q) {
            if (auth()->check()) {
                $q->where('user_id', auth()->id());
            }
        })
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();
@endphp

@if($notifRequests->isEmpty())
<div style="padding:24px; text-align:center; color:var(--gray);">
    <div style="font-size:13px; font-weight:600; margin-bottom:4px;">Belum ada notifikasi</div>
    <div style="font-size:12px;">Permintaan peminjaman akan muncul di sini</div>
</div>
@else
<p class="notif-date-label">Terbaru</p>
@foreach($notifRequests as $req)
<a href="{{ route('messages.show', urlencode($req->email)) }}"
   class="notif-item {{ !$req->read_by_owner ? 'unread' : '' }}"
   style="text-decoration:none; color:inherit;">
    <div class="notif-av">
        <span style="font-size:15px; font-weight:700; color:white; line-height:1;">
            {{ strtoupper(substr($req->borrower_name ?? $req->full_name ?? '?', 0, 1)) }}
        </span>
    </div>
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
        <button class="notif-action-btn">Lihat Permintaan</button>
    </div>
</a>
@endforeach
@endif