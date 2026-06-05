@extends('layouts.app')

@section('content')
@php $user = auth()->user(); @endphp
<style>
/* Back Button */
.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--blue);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Lato', sans-serif;
    margin-bottom: 16px;
    transition: color 0.2s;
}
.back-button:hover {
    color: var(--blue-dark);
}

/* ====== SETTINGS PAGE ====== */
.settings-wrap {
    max-width: 720px;
    margin: 0 auto;
    padding: 28px 20px;
}
.settings-page-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 24px;
    margin-top: 0;
    color: var(--dark);
    font-family: 'Lato', sans-serif;
}

/* Section card */
.settings-card {
    background: var(--white);
    border: 1px solid var(--gray-border);
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 16px;
}
.settings-card-header {
    padding: 16px 22px 12px;
    border-bottom: 1px solid var(--gray-border);
}
.settings-card-header h3 {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--gray);
    margin: 0;
    font-family: 'Lato', sans-serif;
}

/* Row inside card */
.settings-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 22px;
    border-bottom: 1px solid var(--gray-border);
    gap: 16px;
    transition: background .15s;
}
.settings-row:last-child { border-bottom: none; }
.settings-row:hover { background: var(--gray-light); }

.settings-row-label { flex: 1; }
.settings-row-label strong {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 2px;
    font-family: 'Lato', sans-serif;
}
.settings-row-label span {
    font-size: 12px;
    color: var(--gray);
    font-family: 'Lato', sans-serif;
}

/* Toggle switch */
.toggle {
    position: relative;
    width: 44px; height: 24px;
    display: inline-block;
    flex-shrink: 0;
}
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0;
    background: var(--gray-border);
    border-radius: 24px;
    cursor: pointer;
    transition: background .2s;
}
.toggle-slider:before {
    content: '';
    position: absolute;
    width: 18px; height: 18px;
    left: 3px; bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.toggle input:checked + .toggle-slider { background: var(--blue); }
.toggle input:checked + .toggle-slider:before { transform: translateX(20px); }

/* Select */
.settings-select {
    padding: 7px 32px 7px 12px;
    border: 1.5px solid var(--gray-border);
    border-radius: 8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: var(--dark);
    background: var(--white);
    outline: none;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236B7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    transition: border-color .15s;
}
.settings-select:focus { border-color: var(--blue); }

/* Clickable row chevron */
.row-chevron { color: var(--gray-border); flex-shrink: 0; }

/* User card */
.settings-user-card {
    background: var(--dark);
    border-radius: 16px;
    padding: 24px 22px;
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.settings-user-bg {
    position: absolute; left: 50%; top: 50%;
    transform: translate(-50%, -50%);
    font-size: 96px; font-weight: 900; opacity: .06;
    font-family: 'DM Serif Display', serif; letter-spacing: -4px;
    pointer-events: none; color: white;
}
.settings-user-av {
    width: 60px; height: 60px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,.2); overflow: hidden;
    flex-shrink: 0; background: linear-gradient(135deg,#f97316,#f59e0b);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; font-weight: 700; color: white;
}
.settings-user-av img { width: 100%; height: 100%; object-fit: cover; }
.settings-user-info { flex: 1; color: white; font-family: 'Lato', sans-serif; }
.settings-user-info .su-name { font-size: 17px; font-weight: 700; margin-bottom: 3px; }
.settings-user-info .su-email { font-size: 12px; opacity: .6; margin-bottom: 7px; }
.settings-user-info .su-level {
    display: inline-block; font-size: 11px; font-weight: 700;
    background: rgba(255,255,255,.12); padding: 3px 10px; border-radius: 20px;
}
.settings-profile-btn {
    padding: 8px 16px; border: 1.5px solid rgba(255,255,255,.35);
    border-radius: 8px; color: white; font-size: 13px; font-weight: 600;
    text-decoration: none; transition: background .15s; flex-shrink: 0;
}
.settings-profile-btn:hover { background: rgba(255,255,255,.1); }

/* Logout */
.logout-btn {
    width: 100%; text-align: left; padding: 16px 22px;
    background: none; border: none; font-family: 'DM Sans', sans-serif;
    font-size: 14px; font-weight: 600; color: #EF4444;
    cursor: pointer; display: flex; align-items: center; gap: 10px;
    transition: background .15s;
}
.logout-btn:hover { background: #FEF2F2; }
.logout-dot { width: 8px; height: 8px; border-radius: 50%; background: #EF4444; flex-shrink: 0; }

/* Password modal */
.pw-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.45); z-index: 300;
    align-items: center; justify-content: center;
}
.pw-modal-overlay.open { display: flex; }
.pw-modal-box {
    background: white; border-radius: 16px;
    padding: 32px; width: 420px; max-width: 95vw; position: relative;
}
.pw-modal-close {
    position: absolute; top: 16px; right: 16px;
    width: 32px; height: 32px; border-radius: 8px;
    border: none; background: none;
    font-size: 18px; cursor: pointer; color: #6B7280;
    display: flex; align-items: center; justify-content: center;
}
.pw-modal-close:hover { background: #F3F4F6; }
.pw-field { margin-bottom: 16px; }
.pw-field label {
    display: block; font-size: 12px; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: #6B7280; margin-bottom: 6px;
}
.pw-field input {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #E5E7EB; border-radius: 8px;
    font-family: 'DM Sans', sans-serif; font-size: 14px;
    outline: none; box-sizing: border-box;
}
.pw-field input:focus { border-color: var(--blue); }
.pw-submit-btn {
    width: 100%; padding: 13px;
    background: var(--blue); color: white; border: none;
    border-radius: 8px; font-size: 15px; font-weight: 600;
    cursor: pointer; font-family: 'DM Sans', sans-serif; margin-top: 4px;
}
.pw-submit-btn:hover { opacity: .9; }

/* Spinner on save */
.saving-spinner {
    display: inline-block; width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,.4); border-top-color: white;
    border-radius: 50%; animation: spin .6s linear infinite; vertical-align: middle;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Theme preview chips */
.theme-chips { display: flex; gap: 8px; flex-shrink: 0; }
.theme-chip {
    padding: 6px 14px; border-radius: 20px; font-size: 13px;
    font-weight: 600; cursor: pointer; border: 2px solid var(--gray-border);
    transition: all .2s; background: var(--white); color: var(--gray);
}
.theme-chip.active-light { border-color: var(--blue); color: var(--blue); background: var(--blue-light); }
.theme-chip.active-dark  { border-color: #374151; color: white; background: #374151; }

/* Size chips */
.size-chips { display: flex; gap: 6px; flex-shrink: 0; }
.size-chip {
    padding: 5px 12px; border-radius: 20px; font-weight: 600;
    cursor: pointer; border: 2px solid var(--gray-border);
    transition: all .2s; background: var(--white); color: var(--gray);
}
.size-chip:nth-child(1) { font-size: 11px; }
.size-chip:nth-child(2) { font-size: 13px; }
.size-chip:nth-child(3) { font-size: 15px; }
.size-chip.active { border-color: var(--blue); color: var(--blue); background: var(--blue-light); }
/* Language switch loading overlay */
.lang-loading {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,0.85);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 14px;
    backdrop-filter: blur(4px);
}
.lang-loading.show { display: flex; }
.lang-loading-spinner {
    width: 36px; height: 36px;
    border: 3px solid #E5E7EB;
    border-top-color: var(--blue);
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
.lang-loading p {
    font-size: 14px; font-weight: 600;
    color: var(--gray); margin: 0;
    font-family: 'DM Sans', sans-serif;
}

/* ===== SETTINGS DARK MODE ===== */
[data-theme="dark"] .lang-loading { background: rgba(15,22,35,0.92); }
[data-theme="dark"] .lang-loading p { color: #94A3B8; }
[data-theme="dark"] .lang-loading-spinner { border-color: #334155; border-top-color: #60A5FA; }
[data-theme="dark"] .settings-page-title { color: #F1F5F9; }
[data-theme="dark"] .settings-card-header h3 { color: #64748B; }
[data-theme="dark"] .settings-card { background: #1E2433; border-color: #334155; }
[data-theme="dark"] .settings-card-header { border-color: #334155; }
[data-theme="dark"] .settings-row { border-color: #2A3045; }
[data-theme="dark"] .settings-row:hover { background: #2A3045; }
[data-theme="dark"] .settings-row-label strong { color: #F1F5F9; }
[data-theme="dark"] .settings-row-label span { color: #64748B; }
[data-theme="dark"] .settings-select { background: #2A3045; color: #F1F5F9; border-color: #334155; }
[data-theme="dark"] .row-chevron { color: #475569; }
[data-theme="dark"] .logout-btn { color: #F87171; }
[data-theme="dark"] .logout-btn:hover { background: rgba(239,68,68,.1); }
[data-theme="dark"] .logout-dot { background: #F87171; }
[data-theme="dark"] .theme-chip { background: #2A3045; border-color: #334155; color: #94A3B8; }
[data-theme="dark"] .theme-chip.active-dark { background: #3B5BDB; border-color: #3B5BDB; color: white; }
[data-theme="dark"] .theme-chip.active-light { background: #1E3A5F; border-color: #60A5FA; color: #60A5FA; }
[data-theme="dark"] .size-chip { background: #2A3045; border-color: #334155; color: #94A3B8; }
[data-theme="dark"] .size-chip.active { background: #1E3A5F; border-color: #60A5FA; color: #60A5FA; }
[data-theme="dark"] .toggle-slider { background: #334155; }
[data-theme="dark"] .pw-modal-overlay { background: rgba(0,0,0,.65); }
[data-theme="dark"] .pw-modal-box { background: #1E2433; border: 1px solid #334155; }
[data-theme="dark"] .pw-modal-box h2 { color: #F1F5F9; }
[data-theme="dark"] .pw-field label { color: #94A3B8; }
[data-theme="dark"] .pw-field input { background: #2A3045; border-color: #334155; color: #F1F5F9; }
[data-theme="dark"] .pw-field input::placeholder { color: #64748B; }
[data-theme="dark"] .pw-field input:focus { border-color: #60A5FA; }
[data-theme="dark"] .pw-modal-close { color: #94A3B8; }
[data-theme="dark"] .pw-modal-close:hover { background: #2A3045; }
[data-theme="dark"] .settings-user-card { background: linear-gradient(135deg, #1E3A5F, #1E2433); border: 1px solid #334155; }
</style>

<div style="padding: 24px; max-width: 720px; margin: 0 auto;">
    <a href="{{ route('home') }}" class="back-button" title="Kembali ke beranda">
        <span>←</span>
        <span>Kembali</span>
    </a>
</div>

<div class="settings-wrap">
    <div class="settings-page-title">{{ __('settings.title') }}</div>

    {{-- USER CARD --}}
    <div class="settings-user-card">
        <div class="settings-user-bg">SIKLUS</div>
        <div class="settings-user-av">
            @if($user->avatar)
                <img src="{{ asset('storage/profile/' . $user->avatar) }}"
                     alt="{{ $user->name }}"
                     onerror="this.style.display='none';this.parentElement.textContent='{{ strtoupper(substr($user->name,0,1)) }}'">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <div class="settings-user-info">
            <div class="su-name">{{ $user->name }}</div>
            <div class="su-email">{{ $user->email }}</div>
            <span class="su-level">{{ $user->level ?? __('profile.reader_level') . ' 1' }}</span>
        </div>
        <a href="{{ route('profile.edit') }}" class="settings-profile-btn">{{ __('common.edit') }}</a>
    </div>

    {{-- PREFERENSI —— LANGUAGE --}}
    <div class="settings-card">
        <div class="settings-card-header"><h3>{{ __('settings.language') }}</h3></div>

        <div class="settings-row">
            <div class="settings-row-label">
                <strong>{{ __('settings.language') }}</strong>
                <span>{{ __('common.select_language') }}</span>
            </div>
            <select id="sel-language" class="settings-select"
                    data-url="{{ route('settings.language') }}">
                <option value="id" {{ ($user->language_preference ?? 'id') === 'id' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                <option value="en" {{ ($user->language_preference ?? '') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
            </select>
        </div>
    </div>

    {{-- TAMPILAN —— THEME + TEXT SIZE --}}
    <div class="settings-card">
        <div class="settings-card-header"><h3>{{ __('settings.appearance') }}</h3></div>

        {{-- Theme --}}
        <div class="settings-row">
            <div class="settings-row-label">
                <strong>{{ __('settings.theme') }}</strong>
                <span>{{ __('common.select_theme') }}</span>
            </div>
            <div class="theme-chips" id="theme-chips">
                <button class="theme-chip {{ ($user->theme_preference ?? 'light') === 'light' ? 'active-light' : '' }}"
                        data-value="light">☀️ {{ __('settings.light') }}</button>
                <button class="theme-chip {{ ($user->theme_preference ?? '') === 'dark' ? 'active-dark' : '' }}"
                        data-value="dark">🌙 {{ __('settings.dark') }}</button>
            </div>
        </div>

        {{-- Text Size --}}
        <div class="settings-row">
            <div class="settings-row-label">
                <strong>{{ __('settings.text_size') }}</strong>
                <span>{{ __('common.adjust_text_size') }}</span>
            </div>
            <div class="size-chips" id="size-chips">
                <button class="size-chip {{ ($user->text_size ?? '') === 'small'  ? 'active' : '' }}"
                        data-value="small">A</button>
                <button class="size-chip {{ ($user->text_size ?? 'normal') === 'normal' ? 'active' : '' }}"
                        data-value="normal">A</button>
                <button class="size-chip {{ ($user->text_size ?? '') === 'large'  ? 'active' : '' }}"
                        data-value="large">A</button>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    <div class="settings-card">
        <div class="settings-card-header"><h3>{{ __('settings.notifications') }}</h3></div>

        @php
        $notifs = [
            ['id'=>'notif_borrow',  'key'=>'notif_borrow',  'label'=>__('settings.notif_borrow'), 'desc'=>__('settings.notif_borrow_desc')],
            ['id'=>'notif_message', 'key'=>'notif_message', 'label'=>__('settings.notif_message'), 'desc'=>__('settings.notif_message_desc')],
            ['id'=>'notif_return',  'key'=>'notif_return',  'label'=>__('settings.notif_return'), 'desc'=>__('settings.notif_return_desc')],
            ['id'=>'notif_updates', 'key'=>'notif_updates', 'label'=>__('settings.notif_updates'), 'desc'=>__('settings.notif_updates_desc')],
        ];
        @endphp

        @foreach($notifs as $n)
        <div class="settings-row">
            <div class="settings-row-label">
                <strong>{{ $n['label'] }}</strong>
                <span>{{ $n['desc'] }}</span>
            </div>
            <label class="toggle">
                <input type="checkbox" class="notif-toggle"
                       data-field="{{ $n['key'] }}"
                       {{ $user->{$n['key']} ?? ($n['key'] !== 'notif_updates') ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
        @endforeach
    </div>

    {{-- PRIVASI --}}
    <div class="settings-card">
        <div class="settings-card-header"><h3>{{ __('settings.privacy') }}</h3></div>

        <div class="settings-row">
            <div class="settings-row-label">
                <strong>{{ __('settings.public_profile') }}</strong>
                <span>{{ __('settings.public_profile_desc') }}</span>
            </div>
            <label class="toggle">
                <input type="checkbox" class="privacy-toggle"
                       data-field="public_profile"
                       {{ $user->public_profile ?? true ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-row">
            <div class="settings-row-label">
                <strong>{{ __('settings.show_location') }}</strong>
                <span>{{ __('settings.show_location_desc') }}</span>
            </div>
            <label class="toggle">
                <input type="checkbox" class="privacy-toggle"
                       data-field="show_location"
                       {{ $user->show_location ?? true ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="settings-row" style="cursor:pointer;" id="open-pw-modal">
            <div class="settings-row-label">
                <strong>{{ __('settings.change_password') }}</strong>
                <span>{{ __('settings.change_password_desc') }}</span>
            </div>
            <svg class="row-chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>
    </div>

    {{-- TENTANG --}}
    <div class="settings-card">
        <div class="settings-card-header"><h3>{{ __('settings.about_app') }}</h3></div>

        <div class="settings-row">
            <div class="settings-row-label">
                <strong>{{ __('settings.app_version') }}</strong>
                <span>{{ __('settings.app_name') }}</span>
            </div>
            <span style="font-size:13px;font-weight:600;color:var(--gray);">v1.0.0</span>
        </div>

        <a href="mailto:support@siklus.id" class="settings-row" style="text-decoration:none;">
            <div class="settings-row-label">
                <strong>{{ __('settings.contact_us') }}</strong>
                <span>{{ __('settings.contact_email') }}</span>
            </div>
            <svg class="row-chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </a>
    </div>

    {{-- LOGOUT --}}
    <div class="settings-card">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn"
                    onclick="return confirm('Yakin ingin logout dari akun ini?');">
                <span class="logout-dot"></span>
                Logout dari akun ini
            </button>
        </form>
    </div>

    <p style="text-align:center;font-size:11px;color:var(--gray);margin-top:8px;letter-spacing:.08em;">
        SIKLUS &mdash; Book Exchange Community &bull; &copy; {{ date('Y') }}
    </p>
</div>

{{-- Language switch loading overlay --}}
<div class="lang-loading" id="lang-loading">
    <div class="lang-loading-spinner"></div>
    <p>{{ app()->getLocale() === 'id' ? 'Mengganti bahasa...' : 'Switching language...' }}</p>
</div>

{{-- PASSWORD MODAL --}}
<div id="pw-modal" class="pw-modal-overlay"
     onclick="if(event.target===this)this.classList.remove('open')">
    <div class="pw-modal-box">
        <button class="pw-modal-close"
                onclick="document.getElementById('pw-modal').classList.remove('open')">&#10005;</button>
        <h2 style="font-size:20px;font-weight:700;margin:0 0 24px;">Ganti Password</h2>

        <div class="pw-field">
            <label>Password Saat Ini</label>
            <input type="password" id="pw-current" placeholder="Masukkan password lama">
        </div>
        <div class="pw-field">
            <label>Password Baru</label>
            <input type="password" id="pw-new" placeholder="Min. 8 karakter">
        </div>
        <div class="pw-field">
            <label>Konfirmasi Password Baru</label>
            <input type="password" id="pw-confirm" placeholder="Ulangi password baru">
        </div>
        <button class="pw-submit-btn" id="pw-submit-btn">Simpan Password</button>
    </div>
</div>

<script>
(function() {
    const URLS = {
        language:      '{{ route("settings.language") }}',
        appearance:    '{{ route("settings.appearance") }}',
        notifications: '{{ route("settings.notifications") }}',
        privacy:       '{{ route("settings.privacy") }}',
        password:      '{{ route("settings.password") }}',
    };

    /* ─── helpers ─── */
    function post(url, data) {
        return window.saveSetting(url, data);
    }

    /* ─── LANGUAGE ─── */
    document.getElementById('sel-language').addEventListener('change', function() {
        const lang = this.value;
        const overlay = document.getElementById('lang-loading');
        const token = document.querySelector('meta[name="csrf-token"]').content;
        fetch(URLS.language, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ language: lang })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                // Show smooth loading overlay, then reload so server-side locale takes effect
                overlay.classList.add('show');
                setTimeout(() => window.location.reload(), 400);
            } else {
                window.showToast(res.message || 'Failed to change language.', true);
            }
        })
        .catch(() => window.showToast('Connection failed.', true));
    });

    /* ─── THEME CHIPS ─── */
    let currentTheme = '{{ $user->theme_preference ?? "light" }}';
    let currentSize  = '{{ $user->text_size ?? "normal" }}';

    function applyAppearance(theme, size) {
        document.body.setAttribute('data-theme', theme);
        document.body.setAttribute('data-textsize', size);
    }

    function saveAppearance(theme, size) {
        window.saveSetting(URLS.appearance, { theme: theme, text_size: size }, function(res) {
            currentTheme = res.theme;
            currentSize  = res.text_size;
        });
    }

    document.getElementById('theme-chips').addEventListener('click', function(e) {
        const btn = e.target.closest('.theme-chip');
        if (!btn) return;
        const val = btn.dataset.value;
        // Update chip styles
        document.querySelectorAll('.theme-chip').forEach(b => {
            b.className = 'theme-chip';
            if (b.dataset.value === val) b.classList.add(val === 'light' ? 'active-light' : 'active-dark');
        });
        applyAppearance(val, currentSize);
        saveAppearance(val, currentSize);
    });

    /* ─── TEXT SIZE CHIPS ─── */
    document.getElementById('size-chips').addEventListener('click', function(e) {
        const btn = e.target.closest('.size-chip');
        if (!btn) return;
        const val = btn.dataset.value;
        document.querySelectorAll('.size-chip').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        applyAppearance(currentTheme, val);
        saveAppearance(currentTheme, val);
    });

    /* ─── NOTIFICATION TOGGLES ─── */
    function collectNotifs() {
        const data = {};
        document.querySelectorAll('.notif-toggle').forEach(el => {
            data[el.dataset.field] = el.checked;
        });
        return data;
    }

    document.querySelectorAll('.notif-toggle').forEach(el => {
        el.addEventListener('change', function() {
            window.saveSetting(URLS.notifications, collectNotifs());
        });
    });

    /* ─── PRIVACY TOGGLES ─── */
    function collectPrivacy() {
        const data = {};
        document.querySelectorAll('.privacy-toggle').forEach(el => {
            data[el.dataset.field] = el.checked;
        });
        return data;
    }

    document.querySelectorAll('.privacy-toggle').forEach(el => {
        el.addEventListener('change', function() {
            window.saveSetting(URLS.privacy, collectPrivacy());
        });
    });

    /* ─── PASSWORD MODAL ─── */
    document.getElementById('open-pw-modal').addEventListener('click', function() {
        document.getElementById('pw-modal').classList.add('open');
    });

    document.getElementById('pw-submit-btn').addEventListener('click', function() {
        const current = document.getElementById('pw-current').value;
        const newPw   = document.getElementById('pw-new').value;
        const confirm = document.getElementById('pw-confirm').value;

        if (!current || !newPw || !confirm) {
            window.showToast('Semua kolom wajib diisi.', true); return;
        }
        if (newPw !== confirm) {
            window.showToast('Password baru tidak cocok.', true); return;
        }
        if (newPw.length < 8) {
            window.showToast('Password minimal 8 karakter.', true); return;
        }

        const btn = this;
        btn.innerHTML = '<span class="saving-spinner"></span> Menyimpan...';
        btn.disabled = true;

        const token = document.querySelector('meta[name="csrf-token"]').content;
        fetch(URLS.password, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                current_password: current,
                new_password: newPw,
                new_password_confirmation: confirm,
            })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                window.showToast(res.message);
                document.getElementById('pw-modal').classList.remove('open');
                document.getElementById('pw-current').value = '';
                document.getElementById('pw-new').value = '';
                document.getElementById('pw-confirm').value = '';
            } else {
                window.showToast(res.message || 'Gagal mengubah password.', true);
            }
        })
        .catch(() => window.showToast('Koneksi gagal.', true))
        .finally(() => {
            btn.textContent = 'Simpan Password';
            btn.disabled = false;
        });
    });
})();
</script>
@endsection
