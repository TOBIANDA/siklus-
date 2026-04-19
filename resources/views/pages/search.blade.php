@extends('layouts.app')

@section('content')
<div style="padding:32px;">

  <div style="margin-bottom:24px;">
    <h1 style="font-size:22px;font-weight:700;">
        @if($query)
            Hasil pencarian: <span style="color:var(--blue);">"{{ $query }}"</span>
        @else
            Cari Buku
        @endif
    </h1>
    @if($query)
    <p style="color:var(--gray);font-size:14px;margin-top:4px;">
        {{ $results->count() }} buku ditemukan
    </p>
    @endif
  </div>

  @if($query)
    @if($results->count() > 0)
      <div class="book-grid" style="flex-wrap:wrap;">
        @foreach($results as $book)
          <div class="book-card">
            <a href="{{ route('book.show', $book->id) }}" style="text-decoration:none;color:inherit;">
              <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="cover"
                   onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
              <div class="card-body">
                <div class="card-title">{{ $book->title }}</div>
                <div class="card-author">{{ $book->author }}</div>
                <div class="card-footer">
                  <span style="font-size:11px;color:var(--blue);font-weight:600;">{{ $book->category }}</span>
                  <span class="arrow-btn">&#8594;</span>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    @else
      <div style="text-align:center;padding:80px 0;color:var(--gray);">
        <div style="font-size:56px;margin-bottom:16px;">📚</div>
        <p style="font-size:16px;font-weight:600;">Buku tidak ditemukan</p>
        <p style="font-size:14px;margin-top:6px;">Coba kata kunci lain, atau ubah kategori</p>
      </div>
    @endif
  @else
    <div style="text-align:center;padding:80px 0;color:var(--gray);">
      <div style="font-size:56px;margin-bottom:16px;">🔍</div>
      <p style="font-size:16px;font-weight:600;">Ketik judul, penulis, atau kategori buku</p>
      <p style="font-size:14px;margin-top:6px;">Contoh: "Bumi Manusia", "James Clear", "Fiksi"</p>
    </div>
  @endif

</div>
@endsection