@extends('layouts.app')

@section('content')
<div style="padding:24px;">

  {{-- HERO --}}
  @php $hero = $popularBooks->first(); @endphp
  @if($hero)
  <div class="hero hero-dark">
    <div class="hero-bg-text">{{ strtoupper(explode(' ', $hero->author)[count(explode(' ', $hero->author))-1]) }}</div>
    <img src="{{ $hero->cover_url }}" class="book-cover" alt="{{ $hero->title }}"
         onerror="this.style.background='linear-gradient(135deg,#1a1a1a,#444)';this.removeAttribute('src')">
    <div class="hero-info">
      <h1>{{ $hero->title }}</h1>
      <div class="author">{{ $hero->author }}</div>
      <div class="desc">{{ Str::limit($hero->description, 180) }}</div>
      <a href="{{ route('book.show', $hero->id) }}" class="explore-btn">Explore</a>
    </div>
  </div>
  @else
  <div class="hero hero-dark">
    <div class="hero-bg-text">SIKLUS</div>
    <div class="hero-info">
      <h1>Selamat datang di Siklus</h1>
      <div class="author">Platform peminjaman buku P2P mahasiswa</div>
      <div class="desc">Temukan buku yang ingin kamu baca, atau bagikan koleksimu ke komunitas.</div>
    </div>
  </div>
  @endif

  {{-- POPULAR BOOKS --}}
  <div class="section">
    <div class="section-header">
      <div class="section-title">Popular Books</div>
    </div>
    <div class="book-grid">
      @forelse($popularBooks as $book)
      <div class="book-card">
        <a href="{{ route('book.show', $book->id) }}" style="text-decoration:none;color:inherit;">
          <img src="{{ $book->cover_url }}" class="cover" alt="{{ $book->title }}"
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
      @empty
      <p style="color:var(--gray);font-size:14px;">Belum ada buku tersedia.</p>
      @endforelse
    </div>
  </div>

  {{-- RECOMMENDED FOR YOU --}}
  <div class="section">
    <div class="section-header">
      <div class="section-title">Recommended For You</div>
    </div>
    <div class="book-grid">
      @forelse($recommendedBooks as $book)
      <div class="book-card">
        <a href="{{ route('book.show', $book->id) }}" style="text-decoration:none;color:inherit;">
          <img src="{{ $book->cover_url }}" class="cover" alt="{{ $book->title }}"
               onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
          <div class="card-body">
            <div class="card-title">{{ $book->title }}</div>
            <div class="card-author">{{ $book->author }}</div>
            <div class="card-footer">
              <span style="font-size:11px;color:var(--yellow);font-weight:600;">⭐ {{ number_format($book->rating,1) }}</span>
              <span class="arrow-btn">&#8594;</span>
            </div>
          </div>
        </a>
      </div>
      @empty
      <p style="color:var(--gray);font-size:14px;">Belum ada rekomendasi.</p>
      @endforelse
    </div>
  </div>

</div>
@endsection
