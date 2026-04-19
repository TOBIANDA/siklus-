@extends('layouts.app')

@section('content')
<div style="padding:24px;">

  <!-- HERO -->
  <div class="hero hero-dark">
    <div class="hero-bg-text">FROMM</div>
    <img src="{{ asset('images/cover_art_of_loving.webp') }}"
         class="book-cover" alt="The Art of Loving"
         onerror="this.style.background='linear-gradient(135deg,#1a1a1a,#444)';this.removeAttribute('src')">
    <div class="hero-info">
      <h1>The Art of Loving</h1>
      <div class="author">Erich Fromm</div>
      <div class="desc">A classic psychology and philosophy book that explores love not as a passive feeling, but as an active skill that must be learned and practiced.<br><br>Fromm argues that most people misunderstand love as something you "fall into," when in reality it requires effort, discipline, patience, and self-awareness.</div>
      <a href="{{ route('book.show', 1) }}" class="explore-btn">Explore</a>
    </div>
  </div>

  <!-- POPULAR BOOKS -->
  <div class="section">
    <div class="section-header">
      <div class="section-title">Popular Books</div>
    </div>
    <div class="book-grid">
      @foreach($popularBooks as $book)
      <div class="book-card">
        <a href="{{ route('book.show', $book['id']) }}">
          <img src="{{ $book['cover'] }}" class="cover" alt="{{ $book['title'] }}"
               onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
          <div class="card-body">
            <div class="card-title">{{ $book['title'] }}</div>
            <div class="card-author">{{ $book['author'] }}</div>
            <div class="card-footer">
              <button class="arrow-btn">&#8594;</button>
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>

  <!-- RECOMMENDED FOR YOU -->
  <div class="section">
    <div class="section-header">
      <div class="section-title">Recommended For You</div>
    </div>
    <div class="book-grid">
      @foreach($recommendedBooks as $book)
      <div class="book-card">
        <a href="{{ route('book.show', $book['id']) }}">
          <img src="{{ $book['cover'] }}" class="cover" alt="{{ $book['title'] }}"
               onerror="this.style.background='linear-gradient(135deg,#1a3a5c,#2563EB)';this.removeAttribute('src')">
          <div class="card-body">
            <div class="card-title">{{ $book['title'] }}</div>
            <div class="card-author">{{ $book['author'] }}</div>
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
@endsection
