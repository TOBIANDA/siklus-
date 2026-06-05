@extends('layouts.app')

@section('content')
<div style="padding:24px;">

  {{-- SUCCESS/ERROR ALERTS --}}
  @if(session('success'))
    <div class="alert alert-success" style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:12px 20px;border-radius:8px;margin-bottom:20px;font-size:14px;">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-error" style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:12px 20px;border-radius:8px;margin-bottom:20px;font-size:14px;">{{ session('error') }}</div>
  @endif

  <!-- PROFILE HERO -->
  <div class="profile-hero">
    <div class="profile-bg">PROFILE</div>

    {{-- Avatar dengan tombol ganti foto --}}
    <div style="position:relative;flex-shrink:0;display:inline-block;">
      <div class="profile-avatar">
        <img src="{{ $user->avatar_url }}"
             alt="{{ $user->name }}"
             style="width:100%;height:100%;object-fit:cover;"
             onerror="this.src='{{ asset('images/avatar_user.png') }}'">
      </div>
      <a href="#modal-upload"
         style="position:absolute;bottom:6px;right:-10px;width:34px;height:34px;
                background:var(--blue);border:3px solid white;border-radius:50%;
                display:flex;align-items:center;justify-content:center;
                color:white;font-size:15px;line-height:1;text-decoration:none;
                box-shadow:0 2px 8px rgba(0,0,0,.2);" title="Ganti Foto">&#9998;</a>
    </div>

    <div class="profile-info">
      <h2>{{ $user->name }} <span class="verified-badge">&#10004; VERIFIED</span></h2>
      <div class="level">{{ $user->level ?? 'Reader Level 1' }}</div>
      <div class="bio">{{ $user->bio ?? 'Pengguna aktif Siklus. Suka berbagi buku dan menemukan bacaan baru.' }}</div>
      <div class="profile-actions" style="align-items:center;">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
          &#9881; Settings
        </a>
        <button class="btn btn-outline">Share</button>
      </div>
    </div>
  </div>

  <!-- STATS -->
  <div class="stats-grid">
    <div class="stat-card">
      <div>
        <label>BOOKS LISTED</label>
        <div class="value">{{ $booksCount }}</div>
      </div>
      <div class="stat-icon">
        <img src="{{ asset('images/icon_books_listed.png') }}" alt="Books Listed" style="width:22px;height:22px;object-fit:contain;" onerror="this.outerHTML='&#128203;'">
      </div>
    </div>
    <div class="stat-card">
      <div>
        <label>EXCHANGES</label>
        <div class="value">{{ $user->exchanges ?? 0 }}</div>
      </div>
      <div class="stat-icon" style="color:var(--green)">
        <img src="{{ asset('images/icon_exchange.png') }}" alt="Exchanges" style="width:22px;height:22px;object-fit:contain;" onerror="this.outerHTML='&#128260;'">
      </div>
    </div>
    <div class="stat-card">
      <div>
        <label>RATING</label>
        <div class="value">{{ number_format($user->rating ?? 0, 1) }}</div>
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
      @forelse($collection as $book)
      <div class="book-card">
        <a href="{{ route('book.show', $book['id']) }}" style="text-decoration:none;color:inherit;">
          <img src="{{ $book['cover_url'] ?? asset('images/' . ($book['cover'] ?? 'icon_closed_book.png')) }}"
               class="cover" alt="{{ $book['title'] }}"
               onerror="this.style.background='linear-gradient(135deg,#1a1a1a,#444)';this.removeAttribute('src')">
          <div class="card-body">
            <div class="card-title">{{ $book['title'] }}</div>
            <div class="card-author">{{ $book['author'] }}</div>
            <div class="card-footer"><span class="arrow-btn">&#8594;</span></div>
          </div>
        </a>
      </div>
      @empty
      <p style="color:var(--gray);font-size:14px;">Belum ada buku di koleksi. <a href="{{ route('lent.create') }}" style="color:var(--blue);">Upload buku pertamamu!</a></p>
      @endforelse
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

{{-- ========== MODAL UPLOAD FOTO PROFIL ========== --}}
<div id="modal-upload" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;align-items:center;justify-content:center;">
  <a href="#" style="position:absolute;inset:0;z-index:0;display:block;"></a>
  <div style="background:white;border-radius:16px;padding:32px;position:relative;width:420px;max-width:95vw;z-index:1;box-shadow:0 24px 60px rgba(0,0,0,.2);animation:popIn .22s cubic-bezier(.34,1.56,.64,1);">
    <a href="#" style="position:absolute;top:16px;right:16px;width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:8px;color:#6B7280;text-decoration:none;font-size:20px;background:#F3F4F6;" title="Tutup">&#10005;</a>

    <h3 style="font-size:18px;font-weight:700;margin-bottom:20px;">Ganti Foto Profil</h3>

    <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <label for="photo-input"
        style="display:block;background:#F3F4F6;border-radius:12px;padding:40px 24px;
               text-align:center;cursor:pointer;margin-bottom:20px;
               border:2px dashed #E5E7EB;transition:border-color .2s;"
        onmouseover="this.style.borderColor='#2563EB'" onmouseout="this.style.borderColor='#E5E7EB'">
        <div style="margin-bottom:10px;font-size:40px;">📷</div>
        <p style="font-size:14px;color:#6B7280;font-weight:500;margin:0;">Klik untuk pilih foto</p>
        <p style="font-size:12px;color:#9CA3AF;margin-top:4px;">JPG, PNG, WebP • Maks 2MB</p>
        <p id="photo-filename" style="font-size:13px;color:#2563EB;font-weight:600;margin-top:8px;display:none;"></p>
        <input type="file" id="photo-input" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none;" required
               onchange="document.getElementById('photo-filename').textContent=this.files[0]?.name;document.getElementById('photo-filename').style.display='block';">
      </label>

      <button type="submit"
        style="width:100%;padding:13px;background:var(--blue);color:white;border:none;
               border-radius:8px;font-size:15px;font-weight:600;cursor:pointer;
               font-family:'DM Sans',sans-serif;transition:background .15s;"
        onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='var(--blue)'">
        Upload Foto
      </button>
    </form>
  </div>
</div>

<style>
@keyframes popIn {
  from { transform: scale(.92); opacity: 0; }
  to   { transform: scale(1);   opacity: 1; }
}
#modal-upload:target { display: flex !important; }
</style>

@endsection
