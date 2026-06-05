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
      <a href="{{ route('book.show', $hero->id) }}" class="explore-btn">{{ __('common.explore') }}</a>
    </div>
  </div>
  @else
  <div class="hero hero-dark">
    <div class="hero-bg-text">SIKLUS</div>
    <div class="hero-info">
      <h1>{{ __('home.welcome') }}</h1>
      <div class="author">{{ __('home.subtitle') }}</div>
      <div class="desc">{{ __('home.description') }}</div>
    </div>
  </div>
  @endif

  {{-- POPULAR BOOKS --}}
  <div class="section">
    <div class="section-header">
      <div class="section-title">{{ __('home.popular_books') }}</div>
    </div>
    <div class="book-grid">
      @forelse($popularBooks as $book)
      <div class="book-card">
          <img src="{{ $book->cover_url }}" class="cover" alt="{{ $book->title }}"
               onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
          <div class="card-body">
            <div class="card-title">{{ $book->title }}</div>
            <div class="card-author">{{ $book->author }}</div>
            <div class="card-footer">
              <span style="font-size:10px;color:rgba(255,255,255,.8);font-weight:600;background:rgba(37,99,235,.7);padding:2px 6px;border-radius:4px;">{{ $book->category }}</span>
            </div>
          </div>
          <a href="{{ route('book.show', $book->id) }}" class="book-link" aria-label="{{ $book->title }}"></a>
      </div>
      @empty
      <p style="color:var(--gray);font-size:14px;">{{ __('home.no_books_available') }}</p>
      @endforelse
    </div>
  </div>

  {{-- RECOMMENDED FOR YOU --}}
  <div class="section">
    <div class="section-header">
      <div class="section-title">{{ __('home.recommended_for_you') }}</div>
    </div>
    <div class="book-grid">
      @forelse($recommendedBooks as $book)
      <div class="book-card">
          <img src="{{ $book->cover_url }}" class="cover" alt="{{ $book->title }}"
               onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
          <div class="card-body">
            <div class="card-title">{{ $book->title }}</div>
            <div class="card-author">{{ $book->author }}</div>
            <div class="card-footer">
              <span style="font-size:10px;color:#FDE68A;font-weight:700;display:flex;align-items:center;gap:3px;">
                <img src="{{ asset('images/star.png') }}" alt="Rating" style="width:11px;height:11px;object-fit:contain;"> {{ number_format($book->rating,1) }}
              </span>
            </div>
          </div>
          <a href="{{ route('book.show', $book->id) }}" class="book-link" aria-label="{{ $book->title }}"></a>
      </div>
      @empty
      <p style="color:var(--gray);font-size:14px;">{{ __('home.no_recommendations') }}</p>
      @endforelse
    </div>
  </div>

</div>

@endsection
