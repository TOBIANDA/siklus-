@extends('layouts.app')

@section('content')
<style>
.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--blue);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    transition: color 0.2s;
}
.back-button:hover {
    color: var(--blue-dark);
}

.settings-container {
    max-width: 600px;
    margin: 0 auto;
    background: var(--white);
    border: 1px solid var(--gray-border);
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.settings-header {
    text-align: center;
    margin-bottom: 32px;
}
.settings-header h2 {
    font-size: 28px;
    font-weight: 700;
    font-family: 'Lato', sans-serif;
    color: var(--dark);
}
.settings-header p {
    color: var(--gray);
    font-size: 14px;
    margin-top: 4px;
}

.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--dark);
    font-family: 'Lato', sans-serif;
}
.form-group input, .form-group textarea, .form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid var(--gray-border);
    border-radius: 8px;
    font-size: 14px;
    font-family: 'Lato', sans-serif;
    outline: none;
    transition: border-color 0.2s;
    background: var(--gray-light);
}
.form-group input:focus, .form-group textarea:focus, .form-group select:focus {
    border-color: var(--blue);
    background: var(--white);
}

.avatar-section {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--gray-border);
}
.avatar-preview {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    font-weight: 700;
    flex-shrink: 0;
}
.avatar-preview img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-upload-group {
    flex: 1;
}
.avatar-upload-group label {
    display: block;
    font-size: 14px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 8px;
    font-family: 'Lato', sans-serif;
}
.avatar-upload-wrapper {
    display: flex;
    gap: 12px;
}
.avatar-upload-wrapper input {
    flex: 1;
    padding: 10px 12px;
    border: 1px solid var(--gray-border);
    border-radius: 8px;
    font-size: 14px;
    font-family: 'Lato', sans-serif;
    background: var(--gray-light);
}
.avatar-upload-wrapper button {
    background: var(--blue);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;
    font-weight: 600;
    font-size: 14px;
    font-family: 'Lato', sans-serif;
    cursor: pointer;
    transition: background 0.2s;
    flex-shrink: 0;
}
.avatar-upload-wrapper button:hover {
    background: var(--blue-dark);
}
.avatar-upload-hint {
    font-size: 11px;
    color: var(--gray);
    margin-top: 6px;
    font-family: 'Lato', sans-serif;
}

.btn-primary {
    background: var(--blue);
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    font-family: 'Lato', sans-serif;
    cursor: pointer;
    width: 100%;
    transition: background 0.2s;
}
.btn-primary:hover {
    background: var(--blue-dark);
}

.danger-zone {
    margin-top: 40px;
    padding-top: 24px;
    border-top: 1px solid var(--gray-border);
}
.danger-zone h3 {
    font-size: 18px;
    font-weight: 700;
    color: #ef4444;
    margin-bottom: 16px;
    font-family: 'Lato', sans-serif;
}
.btn-danger {
    background: transparent;
    color: #ef4444;
    padding: 12px 24px;
    border: 1px solid #ef4444;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    font-family: 'Lato', sans-serif;
    cursor: pointer;
    width: 100%;
    transition: all 0.2s;
}
.btn-danger:hover {
    background: #fee2e2;
}

.alert-success {
    background: #D1FAE5; color: #065F46; border-radius: 8px;
    padding: 12px 16px; font-size: 14px; font-weight: 600;
    margin-bottom: 24px; text-align: center; font-family: 'Lato', sans-serif;
}
.alert-error {
    background: #FEE2E2; color: #991B1B; border-radius: 8px;
    padding: 12px 16px; font-size: 14px; font-weight: 600;
    margin-bottom: 24px; text-align: center; font-family: 'Lato', sans-serif;
}
</style>

<div style="padding: 24px; max-width: 600px; margin: 0 auto;">
    <a href="{{ route('profile') }}" class="back-button" title="Kembali ke profil">
        <span>←</span>
        <span>Kembali</span>
    </a>
</div>

<div class="settings-container">
    <div class="settings-header">
        <h2>{{ __('common.settings') }}</h2>
        <p>{{ __('profile.manage_account') }}</p>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <!-- Avatar Form -->
    <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="avatar-section" id="avatarForm">
        @csrf @method('PUT')
        <div class="avatar-preview" id="avatarPreviewWrap">
            <img id="avatarPreviewImg"
                 src="{{ $user->avatar_url }}"
                 alt="Avatar"
                 onerror="this.style.display='none'">
        </div>
        <div class="avatar-upload-group">
            <label>{{ __('profile.profile_picture') }}</label>
            <div class="avatar-upload-wrapper">
                <input type="file" name="photo" id="photoInput" accept="image/*" required>
                <button type="submit" id="uploadBtn">{{ __('common.upload') }}</button>
            </div>
            <p class="avatar-upload-hint">JPG, PNG, GIF · Maks 2MB</p>
        </div>
    </form>

    <script>
    // Live preview: show selected image before uploading
    document.getElementById('photoInput').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        
        // Validate file size
        if (file.size > 2 * 1024 * 1024) {
            alert('File terlalu besar. Maksimal 2MB.');
            this.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar (JPG, PNG, GIF).');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('avatarPreviewImg');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
    
    // Auto-submit on file select (optional - can also keep manual button)
    // Or add error handling
    document.getElementById('avatarForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('photoInput');
        if (!fileInput.files || fileInput.files.length === 0) {
            e.preventDefault();
            alert('Pilih file terlebih dahulu');
        }
    });
    </script>

    <!-- Profile Data Form -->
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf @method('PUT')
        
        <div class="form-group">
            <label>{{ __('profile.name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div style="display:flex; gap:16px;">
            <div class="form-group" style="flex:1;">
                <label>{{ __('profile.province') }}</label>
                <select name="province">
                    <option value="" disabled {{ !$user->province ? 'selected' : '' }}>{{ __('profile.select_province') }}</option>
                    <option value="Jawa Barat" {{ $user->province == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                    <option value="Jawa Tengah" {{ $user->province == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                    <option value="Jawa Timur" {{ $user->province == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                    <option value="DKI Jakarta" {{ $user->province == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                </select>
            </div>
            <div class="form-group" style="flex:1;">
                <label>{{ __('profile.city') }}</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="{{ __('profile.city_placeholder') }}">
            </div>
        </div>

        <div class="form-group">
            <label>{{ __('profile.occupation_optional') }}</label>
            <input type="text" name="occupation" value="{{ old('occupation', $user->occupation) }}" placeholder="{{ __('profile.occupation_placeholder') }}">
        </div>

        <div class="form-group">
            <label>{{ __('profile.bio_label') }}</label>
            <textarea name="bio" rows="4" placeholder="{{ __('profile.bio_placeholder') }}">{{ old('bio', $user->bio) }}</textarea>
        </div>

        <button type="submit" class="btn-primary">{{ __('profile.save_changes') }}</button>
    </form>

    <div class="danger-zone">
        <h3>{{ __('profile.account_actions') }}</h3>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-danger">{{ __('auth.logout') }}</button>
        </form>
    </div>
</div>
@endsection
