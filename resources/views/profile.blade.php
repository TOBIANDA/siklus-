@extends('layouts.app')

@section('content')
<div style="padding:24px;">

  <!-- PROFILE HERO -->
  <div class="profile-hero">
    <div class="profile-bg">PROFILE</div>
    <div class="profile-avatar">
      <img src="{{ asset('images/avatar_user.png') }}" alt="User" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
    </div>
    <div class="profile-info">
      <h2>{{ $user['name'] }} <span class="verified-badge">&#10004; VERIFIED</span></h2>
      <div class="level">{{ $user['level'] }}</div>
      <div class="bio">{{ $user['bio'] }}</div>
      <div class="profile-actions">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        <button class="btn btn-outline">Share</button>
      </div>
    </div>
  </div>

  <!-- STATS -->
  <div class="stats-grid">
    <div class="stat-card">
      <div>
        <label>BOOKS LISTED</label>
        <div class="value">{{ $user['books_listed'] }}</div>
      </div>
      <div class="stat-icon">
        <img src="{{ asset('images/icon_books_listed.png') }}" alt="Books Listed" style="width:22px;height:22px;object-fit:contain;" onerror="this.outerHTML='&#128203;'">
      </div>
    </div>
    <div class="stat-card">
      <div>
        <label>EXCHANGES</label>
        <div class="value">{{ $user['exchanges'] }}</div>
      </div>
      <div class="stat-icon" style="color:var(--green)">
        <img src="{{ asset('images/icon_exchange.png') }}" alt="Exchanges" style="width:22px;height:22px;object-fit:contain;" onerror="this.outerHTML='&#128260;'">
      </div>
    </div>
    <div class="stat-card">
      <div>
        <label>RATING</label>
        <div class="value">{{ $user['rating'] }}</div>
      </div>
      <div class="stat-icon" style="color:var(--yellow)">
        <img src="{{ asset('images/icon_star.png') }}" alt="Rating" style="width:22px;height:22px;object-fit:contain;" onerror="this.outerHTML='&#11088;'">
      </div>
    </div>
  </div>

  <!-- CURATED COLLECTION -->
  <div class="section">
    <div class="section-header">
      <div class="section-title">Curated Collection</div>
      <div class="section-tabs">
        <div class="section-tab active" onclick="setSecTab(this)">ACADEMIC</div>
        <div class="section-tab" onclick="setSecTab(this)">POLITICS</div>
      </div>
    </div>
    <div class="book-grid">
      @foreach($collection as $book)
      <div class="book-card">
        <a href="{{ route('book.show', $book['id']) }}">
          <img src="{{ asset('images/' . $book['cover']) }}" class="cover" alt="{{ $book['title'] }}"
               onerror="this.style.background='linear-gradient(135deg,#1a1a1a,#444)';this.removeAttribute('src')">
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

  <!-- BADGES -->
  <div class="section">
    <div class="section-title" style="margin-bottom:16px">Expertise &amp; Badges</div>
    <div class="badges-grid">
      @foreach($badges as $badge)
      <div class="badge-item">
        <div class="badge-icon" style="background:{{ $badge['bg'] }}">{{ $badge['icon'] }}</div>
        <div>
          <div class="badge-name">{{ $badge['name'] }}</div>
          <div class="badge-desc">{{ $badge['desc'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

</div>
@endsection
