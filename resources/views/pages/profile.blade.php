@extends('layouts.app')

@section('content')

<style>
/* ===== CSS-ONLY MODAL (:target trick) ===== */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  z-index: 200;
  align-items: center;
  justify-content: center;
}
.modal-overlay:target {
  display: flex;
}
.modal-box {
  background: white;
  border-radius: 16px;
  padding: 32px;
  position: relative;
  max-width: 95vw;
}
.modal-close {
  position: absolute;
  top: 16px;
  right: 16px;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  color: #6B7280;
  text-decoration: none;
  font-size: 20px;
  line-height: 1;
}
.modal-close:hover { background: #F3F4F6; color: #111827; }

.avatar-wrap { position: relative; flex-shrink: 0; display: inline-block; }
.avatar-pencil {
  position: absolute;
  bottom: 6px;
  right: -10px;
  width: 34px;
  height: 34px;
  background: var(--blue);
  border: 3px solid white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 15px;
  text-decoration: none;
  line-height: 1;
  box-shadow: 0 2px 8px rgba(0,0,0,.2);
}
.avatar-pencil:hover { background: var(--blue-dark); }

.form-input {
  width: 100%;
  padding: 10px 14px;
  border: none;
  background: #F3F4F6;
  border-radius: 8px;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
}
.form-label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
  color: #6B7280;
  display: block;
  margin-bottom: 6px;
  text-transform: uppercase;
}

.alert {
  padding: 12px 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 14px;
}
.alert-success {
  background: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}
.alert-error {
  background: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}
</style>

<div style="padding:24px;">

  {{-- SUCCESS/ERROR ALERTS --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
  @endif

  <!-- PROFILE HERO -->
  <div class="profile-hero">
    <div class="profile-bg">PROFILE</div>

    <!-- Avatar + tombol pensil -->
    <div class="avatar-wrap">
      <div class="profile-avatar">
        <img src="{{ asset('storage/profile/' . ($profile['avatar'] ?? 'avatar_user.png')) }}" alt="User"
             style="width:100%;height:100%;object-fit:cover;" onerror="this.src='{{ asset('images/avatar_user.png') }}'">
      </div>
      <a href="#modal-upload" class="avatar-pencil" title="Ganti Foto">&#9998;</a>
    </div>

    <div class="profile-info">
      <h2>{{ $profile['name'] ?? 'User' }} <span class="verified-badge">{{ ($profile['verified'] ?? true) ? '✓ VERIFIED' : '' }}</span></h2>
      <div class="level">{{ $profile['level'] ?? 'Academic Level 4' }}</div>
      <div class="bio">{{ $profile['bio'] ?? 'Tidak ada deskripsi' }}</div>
      <div class="profile-actions">
        <a href="#modal-edit" class="btn btn-primary" style="text-decoration:none;">Edit Profile</a>
        <button class="btn btn-outline">Share</button>
      </div>
    </div>
  </div>

    <div class="profile-info">
      <h2>Bro <span class="verified-badge">&#10004; VERIFIED</span></h2>
      <div class="level">Academic Level 4</div>
      <div class="bio">Passionate archivist of Indonesian literature and political philosophy. Sharing rare editions to foster intellectual growth within the Siklus community.</div>
      <div class="profile-actions">
        <a href="#modal-edit" class="btn btn-primary" style="text-decoration:none;">Edit Profile</a>
        <button class="btn btn-outline">Share</button>
      </div>
    </div>
  </div>

  <!-- STATS -->
  <div class="stats-grid">
    <div class="stat-card">
      <div><label>BOOKS LISTED</label><div class="value">12</div></div>
      <div class="stat-icon">
        <img src="{{ asset('images/icon_books_listed.png') }}" alt="" style="width:22px;height:22px;object-fit:contain;" onerror="this.outerHTML='&#128203;'">
      </div>
    </div>
    <div class="stat-card">
      <div><label>EXCHANGES</label><div class="value">48</div></div>
      <div class="stat-icon" style="color:var(--green)">
        <img src="{{ asset('images/icon_exchange.png') }}" alt="" style="width:22px;height:22px;object-fit:contain;" onerror="this.outerHTML='&#128260;'">
      </div>
    </div>
    <div class="stat-card">
      <div><label>RATING</label><div class="value">4.9</div></div>
      <div class="stat-icon" style="color:var(--yellow)">
        <img src="{{ asset('images/icon_star.png') }}" alt="" style="width:22px;height:22px;object-fit:contain;" onerror="this.outerHTML='&#11088;'">
      </div>
    </div>
  </div>

  <!-- CURATED COLLECTION -->
  <div class="section">
    <div class="section-header">
      <div class="section-title">Curated Collection</div>
      <div class="section-tabs">
        <a class="section-tab {{ request('tab','academic') === 'academic' ? 'active' : '' }}"
           href="{{ route('profile') }}?tab=academic" style="text-decoration:none;">ACADEMIC</a>
        <a class="section-tab {{ request('tab') === 'politics' ? 'active' : '' }}"
           href="{{ route('profile') }}?tab=politics" style="text-decoration:none;">POLITICS</a>
      </div>
    </div>
    <div class="book-grid">
      @foreach(range(1,4) as $i)
      <div class="book-card">
        <a href="{{ route('book.show', $i) }}" class="book-link" style="text-decoration:none;color:inherit;">
          <img src="{{ asset('images/cover_art_of_loving.webp') }}" class="cover" alt=""
               onerror="this.style.background='linear-gradient(135deg,#1a1a1a,#444)';this.removeAttribute('src')">
          <div class="card-body">
            <div class="card-title">The Art Of Loving</div>
            <div class="card-author">Erich Fromm</div>
            <div class="card-footer"><span class="arrow-btn">&#8594;</span></div>
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
      <div class="badge-item">
        <div class="badge-icon" style="background:#EFF6FF">&#128218;</div>
        <div><div class="badge-name">History Buff</div><div class="badge-desc">Top 5% contributor</div></div>
      </div>
      <div class="badge-item">
        <div class="badge-icon" style="background:#D1FAE5">&#129309;</div>
        <div><div class="badge-name">Trusted Lender</div><div class="badge-desc">Perfect return rate</div></div>
      </div>
      <div class="badge-item">
        <div class="badge-icon" style="background:#FEF3C7">&#128214;</div>
        <div><div class="badge-name">Archivist</div><div class="badge-desc">10+ Rare books listed</div></div>
      </div>
    </div>
  </div>

</div>


{{-- ========== MODAL EDIT PROFILE ========== --}}
<div id="modal-edit" class="modal-overlay">
  {{-- Backdrop: klik di luar = tutup --}}
  <a href="#" style="position:absolute;inset:0;z-index:0;display:block;"></a>

  <div class="modal-box" style="width:580px;display:flex;gap:28px;align-items:flex-start;z-index:1;">
    <a href="#" class="modal-close" title="Tutup">&#10005;</a>

    {{-- Kiri: foto --}}
    <div style="display:flex;flex-direction:column;align-items:center;gap:10px;flex-shrink:0;padding-top:36px;">
      <div style="position:relative;display:inline-block;">
        <div style="width:110px;height:110px;border-radius:50%;overflow:hidden;
                    background:linear-gradient(135deg,#f97316,#f59e0b);">
          <img src="{{ asset('images/avatar_user.png') }}"
               style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
        </div>
        <a href="#modal-upload"
          style="position:absolute;bottom:6px;right:-10px;width:32px;height:32px;
                 background:var(--blue);border:3px solid white;border-radius:50%;
                 display:flex;align-items:center;justify-content:center;
                 color:white;font-size:14px;line-height:1;text-decoration:none;
                 box-shadow:0 2px 8px rgba(0,0,0,.2);">&#9998;</a>
      </div>
      <a href="#modal-upload"
        style="font-size:13px;font-weight:600;text-decoration:underline;color:#111827;">
        Change Photo
      </a>
    </div>

    {{-- Kanan: form --}}
    <form action="{{ route('profile.update') }}" method="POST"
          style="flex:1;display:flex;flex-direction:column;gap:16px;">
      @csrf
      @method('PUT')

      <h2 style="font-size:22px;font-weight:700;margin:0;">Edit Profile</h2>

      <div>
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-input" value="{{ $profile['name'] ?? 'Adidharma Dewabrata Kusumaputra' }}" required>
      </div>

      <div>
        <label class="form-label">Occupation</label>
        <input type="text" name="occupation" class="form-input" value="{{ $profile['occupation'] ?? 'Undergraduate Student at FILKOM UB' }}">
      </div>

      <div>
        <label class="form-label">Bio / Deskripsi</label>
        <textarea name="bio" rows="4" class="form-input" style="resize:none;">{{ $profile['bio'] ?? 'Passionate archivist of Indonesian literature and political philosophy. Sharing rare editions to foster intellectual growth within the Siklus community.' }}</textarea>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:4px;">
        <a href="#" class="btn" style="background:#9CA3AF;color:white;text-decoration:none;">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>


{{-- ========== MODAL UPLOAD PHOTO ========== --}}
<div id="modal-upload" class="modal-overlay">
  {{-- Backdrop: klik di luar = tutup --}}
  <a href="#" style="position:absolute;inset:0;z-index:0;display:block;"></a>

  <div class="modal-box" style="width:420px;z-index:1;">
    <a href="#" class="modal-close" title="Tutup">&#10005;</a>

    <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data"
          style="margin-top:8px;">
      @csrf
      @method('PUT')

      <label for="photo-input"
        style="display:block;background:#F3F4F6;border-radius:12px;padding:52px 24px;
               text-align:center;cursor:pointer;margin-bottom:20px;
               border:2px dashed #E5E7EB;">
        <div style="margin-bottom:14px;color:#9CA3AF;">
          <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" style="display:inline-block;">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="12" y1="18" x2="12" y2="12"/>
            <line x1="9" y1="15" x2="12" y2="12"/>
            <line x1="15" y1="15" x2="12" y2="12"/>
          </svg>
        </div>
        <p style="font-size:14px;color:#6B7280;font-weight:500;">Drop file here or click to browse</p>
        <input type="file" id="photo-input" name="photo" accept="image/*" style="display:none;">
      </label>

      <button type="submit"
        style="width:100%;padding:13px;background:var(--blue);color:white;border:none;
               border-radius:8px;font-size:15px;font-weight:600;cursor:pointer;
               font-family:'DM Sans',sans-serif;">
        Upload
      </button>
    </form>
  </div>
</div>

@endsection