@extends('layouts.app')

@section('content')
<div style="padding:24px;">

  <!-- HERO -->
  <div class="hero hero-navy" style="margin-bottom:28px;">
    <div class="hero-bg-text" style="font-family:'Caveat',cursive;font-size:80px;opacity:.12;">{{ $book['short_title'] }}</div>
    <img src="{{ $book['cover'] }}" class="book-cover" alt="{{ $book['title'] }}"
         onerror="this.style.background='linear-gradient(135deg,#0F172A,#2563EB)';this.removeAttribute('src')">
    <div class="hero-info">
      <h1>{{ $book['title'] }}</h1>
      <div class="author">{{ $book['author'] }}</div>
      <div class="hero-meta">
        <span>&#128196; {{ $book['pages'] }} Page</span>
        <span>&#127760; {{ $book['language'] }}</span>
        <span>&#128218; {{ $book['genre'] }}</span>
        <span>&#11088; {{ $book['rating'] }} ({{ $book['rating_count'] }})</span>
      </div>
    </div>
  </div>

  <div class="book-layout">
    <!-- MAIN CONTENT -->
    <div class="book-main">
      <div class="desc-tabs">
        <div class="desc-tab active" onclick="setDescTab(this)">Description</div>
        <div class="desc-tab" onclick="setDescTab(this)">Review</div>
      </div>
      <div class="book-desc">
        {!! $book['description'] !!}
      </div>

      <div style="margin-bottom:14px;">
        <span style="font-size:18px;font-weight:700;border-bottom:2px solid var(--dark);padding-bottom:4px;">Featured Reviews</span>
      </div>
      <div class="reviews-grid">
        @foreach($reviews as $review)
        <div class="review-card">
          <div class="reviewer">
            <div class="rev-av">
              <img src="{{ asset('images/' . $review['avatar']) }}" alt="{{ $review['name'] }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
            </div>
            <div>
              <div class="rev-name">{{ $review['name'] }}</div>
              <div class="rev-role">{{ $review['role'] }}</div>
            </div>
          </div>
          <div class="stars">{{ str_repeat('★', $review['stars']) }}</div>
          <div class="rev-text">{{ $review['text'] }}</div>
        </div>
        @endforeach
      </div>

      <!-- BASED ON PREFERENCES -->
      <div class="section" style="margin-top:28px;">
        <div style="margin-bottom:14px;"><span class="section-title">Based on your preferences</span></div>
        <div class="book-grid">
          @foreach($relatedBooks as $related)
          <div class="book-card">
            <a href="{{ route('book.show', $related['id']) }}">
              <img src="{{ $related['cover'] }}" class="cover" alt="{{ $related['title'] }}"
                   onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
              <div class="card-body">
                <div class="card-title">{{ $related['title'] }}</div>
                <div class="card-author">{{ $related['author'] }}</div>
                <div class="card-footer">
                  <button class="arrow-btn">&#8594;</button>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- LENDERS SIDEBAR -->
    <div class="book-side">
      @foreach($lenders as $lender)
      <div class="lender-card">
        <div class="lc-top">
          <div class="lc-av">
            <img src="{{ asset('images/' . $lender['avatar']) }}" alt="{{ $lender['name'] }}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
          </div>
          <div>
            <div class="lc-name">{{ $lender['name'] }}</div>
            <div class="lc-loc">📍 {{ $lender['location'] }}</div>
          </div>
        </div>
        <a href="{{ route('messages.show', $lender['id']) }}" class="borrow-btn">Borrow from {{ $lender['name'] }}</a>
      </div>
      @endforeach
    </div>
  </div>

</div>
@endsection
