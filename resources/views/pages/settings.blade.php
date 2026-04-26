@extends('layouts.app')

@section('content')
<style>
/* ====== SETTINGS PAGE ====== */
.settings-wrap {
    max-width: 760px;
    margin: 0 auto;
    padding: 32px 24px;
}

/* Section card */
.settings-card {
    background: var(--white);
    border: 1px solid var(--gray-border);
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 20px;
}
.settings-card-header {
    padding: 18px 24px 14px;
    border-bottom: 1px solid var(--gray-border);
}
.settings-card-header h3 {
    font-size: 13px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--gray);
    margin: 0;
}

/* Row inside card */
.settings-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
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
}
.settings-row-label span {
    font-size: 12px;
    color: var(--gray);
}
.settings-row-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray);
    flex-shrink: 0;
}

/* chevron icon */
.chevron {
    width: 20px; height: 20px;
    color: var(--gray-border);
    flex-shrink: 0;
}

/* Toggle switch */
.toggle-wrap { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
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

/* Select input */
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

/* User info row at the top */
.settings-user-card {
    background: var(--dark);
    border-radius: 16px;
    padding: 28px 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.settings-user-bg {
    position: absolute;
    right: -16px; top: 50%;
    transform: translateY(-50%);
    font-size: 100px;
    font-weight: 900;
    opacity: .06;
    font-family: 'DM Serif Display', serif;
    letter-spacing: -4px;
    pointer-events: none;
    color: white;
}
.settings-user-av {
    width: 64px; height: 64px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,.2);
    overflow: hidden;
    flex-shrink: 0;
    background: linear-gradient(135deg,#f97316,#f59e0b);
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; font-weight: 700; color: white;
}
.settings-user-av img { width: 100%; height: 100%; object-fit: cover; }
.settings-user-info { flex: 1; color: white; }
.settings-user-info .su-name { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
.settings-user-info .su-email { font-size: 13px; opacity: .65; margin-bottom: 8px; }
.settings-user-info .su-level {
    display: inline-block;
    font-size: 11px; font-weight: 700;
    background: rgba(255,255,255,.12);
    padding: 3px 10px; border-radius: 20px;
}
.settings-profile-btn {
    padding: 9px 18px;
    border: 1.5px solid rgba(255,255,255,.35);
    border-radius: 8px;
    color: white;
    font-size: 13px; font-weight: 600;
    text-decoration: none;
    transition: background .15s;
    flex-shrink: 0;
}
.settings-profile-btn:hover { background: rgba(255,255,255,.1); }

/* Logout button */
.logout-btn {
    width: 100%;
    text-align: left;
    padding: 16px 24px;
    background: none;
    border: none;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #EF4444;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: background .15s;
}
.logout-btn:hover { background: #FEF2F2; }
.logout-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #EF4444;
    flex-shrink: 0;
}

/* Alert */
.settings-alert {
    background: #D1FAE5; color: #065F46;
    border-radius: 10px; padding: 12px 18px;
    font-size: 13px; font-weight: 600;
    margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
}
.settings-alert.error { background: #FEE2E2; color: #991B1B; }

/* Page title */
.settings-page-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 24px;
    color: var(--dark);
}
</style>

<div class="settings-wrap">

    <div class="settings-page-title">Settings</div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="settings-alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="settings-alert error">{{ session('error') }}</div>
    @endif

    {{-- USER CARD --}}
    <div class="settings-user-card">
        <div class="settings-user-bg">SIKLUS</div>
        <div class="settings-user-av">
            @php $av = auth()->user()->avatar ?? null; @endphp
            @if($av)
            <img src="{{ asset('storage/profile/' . $av) }}"
                 onerror="this.style.display='none';this.parentElement.innerHTML='{{ strtoupper(substr(auth()->user()->name,0,1)) }}'">
            @else
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            @endif
        </div>
        <div class="settings-user-info">
            <div class="su-name">{{ auth()->user()->name }}</div>
            <div class="su-email">{{ auth()->user()->email }}</div>
            <span class="su-level">{{ auth()->user()->level ?? 'Reader' }}</span>
        </div>
        <a href="{{ route('profile') }}" class="settings-profile-btn">Edit Profile</a>
    </div>

    {{-- SECTION: PREFERENCES --}}
    <div class="settings-card">
        <div class="settings-card-header"><h3>Preferences</h3></div>

        {{-- Language --}}
        <form action="{{ route('settings.language') }}" method="POST">
            @csrf
            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Language</strong>
                    <span>Pilih bahasa antarmuka aplikasi</span>
                </div>
                <select name="language" class="settings-select"
                        onchange="this.form.submit()">
                    <option value="id" {{ (session('locale','id') === 'id') ? 'selected' : '' }}>Indonesia</option>
                    <option value="en" {{ (session('locale') === 'en') ? 'selected' : '' }}>English</option>
                    <option value="jv" {{ (session('locale') === 'jv') ? 'selected' : '' }}>Jawa</option>
                </select>
            </div>
        </form>

        {{-- Theme --}}
        <div class="settings-row">
            <div class="settings-row-label">
                <strong>Theme</strong>
                <span>Tampilan aplikasi</span>
            </div>
            <div class="settings-row-value">Light</div>
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>

        {{-- Text Size --}}
        <div class="settings-row">
            <div class="settings-row-label">
                <strong>Ukuran Teks</strong>
                <span>Sesuaikan ukuran font</span>
            </div>
            <div class="settings-row-value">Normal</div>
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>
    </div>

    {{-- SECTION: NOTIFICATIONS --}}
    <form action="{{ route('settings.notifications') }}" method="POST">
        @csrf
        <div class="settings-card">
            <div class="settings-card-header"><h3>Notifications</h3></div>

            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Permintaan Peminjaman</strong>
                    <span>Notifikasi saat ada yang ingin meminjam bukumu</span>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="notif_borrow" checked onchange="this.form.submit()">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Pesan Baru</strong>
                    <span>Notifikasi saat ada pesan masuk</span>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="notif_message" checked onchange="this.form.submit()">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Pengingat Pengembalian</strong>
                    <span>Notifikasi H-3 sebelum batas pengembalian</span>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="notif_return" checked onchange="this.form.submit()">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Update Fitur Baru</strong>
                    <span>Info tentang fitur dan pembaruan Siklus</span>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="notif_updates" onchange="this.form.submit()">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
    </form>

    {{-- SECTION: PRIVACY --}}
    <form action="{{ route('settings.privacy') }}" method="POST">
        @csrf
        <div class="settings-card">
            <div class="settings-card-header"><h3>Privacy &amp; Security</h3></div>

            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Tampilkan Profil ke Publik</strong>
                    <span>Pengguna lain bisa melihat profilmu</span>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="public_profile" checked onchange="this.form.submit()">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Tampilkan Lokasi</strong>
                    <span>Kota kamu terlihat di halaman buku</span>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="show_location" checked onchange="this.form.submit()">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Ganti Password</strong>
                    <span>Ubah kata sandi akunmu</span>
                </div>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </div>

            <div class="settings-row">
                <div class="settings-row-label">
                    <strong>Two-Factor Authentication</strong>
                    <span>Keamanan ekstra untuk akunmu</span>
                </div>
                <div class="settings-row-value">Off</div>
                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </div>
        </div>
    </form>

    {{-- SECTION: ABOUT --}}
    <div class="settings-card">
        <div class="settings-card-header"><h3>About</h3></div>

        <div class="settings-row">
            <div class="settings-row-label">
                <strong>Versi Aplikasi</strong>
                <span>Siklus Book Exchange</span>
            </div>
            <div class="settings-row-value">v1.0.0</div>
        </div>

        <div class="settings-row">
            <div class="settings-row-label">
                <strong>Kebijakan Privasi</strong>
                <span>Pelajari cara kami melindungi datamu</span>
            </div>
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>

        <div class="settings-row">
            <div class="settings-row-label">
                <strong>Syarat &amp; Ketentuan</strong>
                <span>Aturan penggunaan platform Siklus</span>
            </div>
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>

        <div class="settings-row">
            <div class="settings-row-label">
                <strong>Hubungi Kami</strong>
                <span>support@siklus.id</span>
            </div>
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>
    </div>

    {{-- LOGOUT --}}
    <div class="settings-card">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <span class="logout-dot"></span>
                Logout dari akun ini
            </button>
        </form>
    </div>

    <p style="text-align:center; font-size:11px; color:var(--gray); margin-top:8px; letter-spacing:.08em;">
        SIKLUS &mdash; Book Exchange Community &bull; &copy; {{ date('Y') }}
    </p>

</div>
@endsection
