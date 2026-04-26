@extends('layouts.app')

@section('content')
<style>
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
    font-family: 'DM Serif Display', serif;
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
.form-group input:focus, .form-group textarea:focus {
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
}
.avatar-preview img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.btn-primary {
    background: var(--blue);
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
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
    color: #ef4444;
    margin-bottom: 16px;
}
.btn-danger {
    background: transparent;
    color: #ef4444;
    padding: 12px 24px;
    border: 1px solid #ef4444;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    transition: all 0.2s;
}
.btn-danger:hover {
    background: #fee2e2;
}

/* Alert */
.alert-success {
    background: #D1FAE5; color: #065F46; border-radius: 8px;
    padding: 12px 16px; font-size: 14px; font-weight: 600;
    margin-bottom: 24px; text-align: center;
}
</style>

<div class="settings-container">
    <div class="settings-header">
        <h2>Settings</h2>
        <p>Manage your account settings and profile</p>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    <!-- Avatar Form -->
    <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="avatar-section">
        @csrf @method('PUT')
        <div class="avatar-preview">
            @if($user->avatar)
                <img src="{{ asset('images/' . $user->avatar) }}" alt="Avatar" onerror="this.style.display='none'">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <div style="flex: 1;">
            <label style="display:block; font-size:14px; font-weight:700; color:var(--dark); margin-bottom:8px;">Profile Picture</label>
            <div style="display:flex; gap:12px;">
                <input type="file" name="photo" accept="image/*" required style="font-size:14px; padding:6px;">
                <button type="submit" style="background:var(--dark); color:white; border:none; border-radius:4px; padding:8px 16px; font-weight:600; cursor:pointer;">Update</button>
            </div>
        </div>
    </form>

    <!-- Profile Data Form -->
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf @method('PUT')
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div style="display:flex; gap:16px;">
            <div class="form-group" style="flex:1;">
                <label>Province</label>
                <select name="province">
                    <option value="" disabled {{ !$user->province ? 'selected' : '' }}>Select Province</option>
                    <option value="Jawa Barat" {{ $user->province == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                    <option value="Jawa Tengah" {{ $user->province == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                    <option value="Jawa Timur" {{ $user->province == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                    <option value="DKI Jakarta" {{ $user->province == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                </select>
            </div>
            <div class="form-group" style="flex:1;">
                <label>City</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. Bandung">
            </div>
        </div>

        <div class="form-group">
            <label>Title / Occupation (Optional)</label>
            <input type="text" name="occupation" value="{{ old('occupation', $user->occupation) }}" placeholder="e.g. Undergraduate Student">
        </div>

        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" rows="4" placeholder="Tell the community about yourself...">{{ old('bio', $user->bio) }}</textarea>
        </div>

        <button type="submit" class="btn-primary">Save Changes</button>
    </form>

    <div class="danger-zone">
        <h3>Account Actions</h3>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-danger">Logout</button>
        </form>
    </div>
</div>
@endsection
