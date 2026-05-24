<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Siklus - {{ __('navigation.home') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">

    <style>
    /* ===== DARK THEME ===== */
    [data-theme="dark"] {
        --dark: #F9FAFB;
        --white: #1F2937;
        --gray-light: #374151;
        --gray-border: #374151;
        --blue-light: #1E3A5F;
    }
    [data-theme="dark"] body { background: #111827; color: #F9FAFB; }
    [data-theme="dark"] .topbar { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .sidebar { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .search { background: #374151; }
    [data-theme="dark"] .search input { color: #F9FAFB; }
    [data-theme="dark"] .settings-card { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .settings-row:hover { background: #374151; }
    [data-theme="dark"] .settings-row { border-color: #374151; }
    [data-theme="dark"] .settings-row-label strong { color: #F9FAFB; }
    [data-theme="dark"] .settings-select { background: #374151; color: #F9FAFB; border-color: #4B5563; }
    [data-theme="dark"] .book-card { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .stat-card { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .badge-item { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .msg-sidebar { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .chat-area { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .bubble.recv { background: #374151; border-color: #4B5563; color: #F9FAFB; }
    [data-theme="dark"] .notif-panel { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .borrow-card { background: #1F2937; border-color: #374151; }
    [data-theme="dark"] .lender-card { background: #1F2937; border-color: #374151; }

    /* ===== TEXT SIZE ===== */
    [data-textsize="small"]  body { font-size: 13px; }
    [data-textsize="normal"] body { font-size: 15px; }
    [data-textsize="large"]  body { font-size: 17px; }
    [data-textsize="small"]  .nav-label,
    [data-textsize="small"]  .settings-row-label strong { font-size: 13px; }
    [data-textsize="large"]  .nav-label,
    [data-textsize="large"]  .settings-row-label strong { font-size: 16px; }

    /* ===== TOAST NOTIFICATION ===== */
    #settings-toast {
        position: fixed;
        bottom: 28px;
        right: 28px;
        padding: 14px 22px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: white;
        background: #10B981;
        box-shadow: 0 8px 24px rgba(0,0,0,.18);
        z-index: 9999;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity .3s, transform .3s;
        pointer-events: none;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 220px;
    }
    #settings-toast.error { background: #EF4444; }
    #settings-toast.show { opacity: 1; transform: translateY(0); }
    </style>
</head>
@php
    $__theme    = auth()->check() ? (auth()->user()->theme_preference    ?? 'light') : 'light';
    $__textsize = auth()->check() ? (auth()->user()->text_size           ?? 'normal') : 'normal';
@endphp
<body data-theme="{{ $__theme }}" data-textsize="{{ $__textsize }}">

{{-- Global Toast --}}
<div id="settings-toast"></div>

<div class="app">

    <header class="topbar">
        <span id="btn-sidebar-toggle" class="hamburger" style="cursor:pointer; display:inline-block;">&#9776;</span>
        
        <a class="logo-text" href="{{ route('home') }}">
            <img src="{{ asset('images/siklus.png') }}" alt="Siklus" style="height:72px;vertical-align:middle;"
                 onerror="this.style.display='none';this.insertAdjacentHTML('afterend','<span style=\'font-family:Caveat,cursive;font-size:28px;font-weight:700;\'>Siklus</span>')">
        </a>
        
        <div class="search">
            <img src="{{ asset('images/search.png') }}" alt="Search" style="width:16px;height:16px;">
            <form action="{{ route('search') }}" method="GET" style="flex:1;display:flex;">
                <input type="text" name="q" placeholder="{{ __('navigation.search_placeholder') }}" value="{{ request('q') }}">
            </form>
        </div>

        <a href="{{ route('profile') }}" class="top-avatar">
            <img src="{{ asset('images/avatar_user.png') }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;" 
                 onerror="this.style.display='none';this.parentElement.innerHTML='&#128018;'">
        </a>
    </header>

    <div class="body-wrap">
        <nav class="sidebar" id="mainSidebar">
            <a href="{{ route('home') }}" class="nav-row {{ request()->routeIs('home') ? 'active' : '' }}" style="text-decoration:none;">
                <div class="nav-item" title="{{ __('navigation.home') }}">
                    <img src="{{ asset('images/icon_home.png') }}" alt="{{ __('navigation.home') }}" style="width:22px;height:22px;object-fit:contain;">
                </div>
                <span class="nav-label">{{ __('navigation.home') }}</span>
            </a>
            
            <div class="nav-row {{ request()->routeIs('notifications') ? 'active' : '' }}">
                <div class="nav-item btn-notif-toggle" title="{{ __('navigation.notifications') }}" style="position:relative; cursor:pointer;">
                    <img src="{{ asset('images/icon_notification.png') }}" alt="{{ __('navigation.notifications') }}" style="width:22px;height:22px;object-fit:contain;">
                    <span style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:#EF4444;border-radius:50%;border:2px solid white;"></span>
                </div>
                <div class="nav-label btn-notif-toggle" style="cursor:pointer;">{{ __('navigation.notifications') }}</div>
            </div>


            <div class="nav-row {{ request()->routeIs('borrow') || request()->routeIs('lent') ? 'active' : '' }}" id="book-nav-row">
                <div class="nav-item btn-borrow-toggle" title="{{ __('navigation.book') }}" style="cursor:pointer;">
                    <img src="{{ asset('images/icon_closed_book.png') }}" alt="{{ __('navigation.book') }}" style="width:22px;height:22px;object-fit:contain;"
                         onerror="this.src='{{ asset('images/icon_borrow.png') }}'">
                </div>
                <div class="nav-label btn-borrow-toggle" style="cursor:pointer;">{{ __('navigation.book') }}</div>
            </div>

            <div class="submenu">
                <a href="{{ route('borrow') }}" class="submenu-item {{ request()->routeIs('borrow') ? 'active' : '' }}">
                    <img src="{{ asset('images/icon_borrow.png') }}" alt="{{ __('navigation.borrow') }}" style="width:16px;"> {{ __('navigation.borrow') }}
                </a>
                <a href="{{ route('lent') }}" class="submenu-item {{ request()->routeIs('lent') ? 'active' : '' }}">
                    <img src="{{ asset('images/icon_exchange.png') }}" alt="{{ __('navigation.lent') }}" style="width:16px;"> {{ __('navigation.lent') }}
                </a>
            </div>


            <a href="{{ route('messages') }}" class="nav-row {{ request()->routeIs('messages') ? 'active' : '' }}" style="text-decoration:none;">
                <div class="nav-item" title="{{ __('navigation.messages') }}">
                    <img src="{{ asset('images/icon_message.png') }}" alt="{{ __('navigation.messages') }}" style="width:22px;height:22px;object-fit:contain;">
                </div>
                <span class="nav-label">{{ __('navigation.messages') }}</span>
            </a>

            <div class="spacer"></div>

            <a href="{{ route('settings') }}" class="nav-row {{ request()->routeIs('settings') ? 'active' : '' }}" style="text-decoration:none;">
                <div class="nav-item" title="{{ __('navigation.settings') }}">
                    <img src="{{ asset('images/icon_settings.png') }}" alt="{{ __('navigation.settings') }}" style="width:22px;height:22px;object-fit:contain;">
                </div>
                <span class="nav-label">{{ __('navigation.settings') }}</span>
            </a>
        </nav>

        <div class="notif-panel" id="notifPanel">
            <div class="notif-panel-header">
                <h2>{{ __('navigation.notifications') }}</h2>
                <div class="notif-close btn-notif-toggle" style="cursor:pointer;">&#10005;</div>
            </div>
            <div class="notif-scroll-area">
                @include('partials.notifications')
            </div>
        </div>

        <main class="page active" style="flex:1;overflow-y:auto; padding:24px;">
            @yield('content')
        </main>
    </div>
</div>

<script>
// ===== GLOBAL TOAST =====
window.showToast = function(msg, isError = false) {
    const t = document.getElementById('settings-toast');
    t.textContent = (isError ? '✕ ' : '✓ ') + msg;
    t.className = 'show' + (isError ? ' error' : '');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => { t.className = t.className.replace('show','').trim(); }, 3000);
};

// ===== GLOBAL AJAX SETTINGS SAVE =====
window.saveSetting = function(url, data, onSuccess) {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showToast(res.message || 'Tersimpan!');
            if (onSuccess) onSuccess(res);
        } else {
            showToast(res.message || 'Gagal menyimpan.', true);
        }
    })
    .catch(() => showToast('{{ __('messages.connection_failed') }}', true));
};

document.addEventListener('DOMContentLoaded', function() {
  const appContainer = document.querySelector('.app');
  const btnSidebar = document.getElementById('btn-sidebar-toggle');
  const btnNotif = document.querySelectorAll('.btn-notif-toggle');
  const btnBorrow = document.querySelectorAll('.btn-borrow-toggle');

  if (btnSidebar) {
    btnSidebar.addEventListener('click', function() {
      appContainer.classList.toggle('sidebar-expanded');
    });
  }

  if (btnBorrow) {
    btnBorrow.forEach(btn => {
      btn.addEventListener('click', function() {
        appContainer.classList.toggle('submenu-expanded');
        if (appContainer.classList.contains('submenu-expanded') && !appContainer.classList.contains('sidebar-expanded')) {
          appContainer.classList.add('sidebar-expanded');
        }
      });
    });
  }

  if (btnNotif) {
    btnNotif.forEach(btn => {
      btn.addEventListener('click', function() {
        appContainer.classList.toggle('notif-expanded');
      });
    });
  }

  document.querySelectorAll('a[href*="/books/"]').forEach(link => {
    link.addEventListener('click', function(e) {
      const isBookLink = this.classList.contains('book-link') || 
                        this.closest('.book-card') ||
                        this.closest('.book-grid');
      if (isBookLink) {
        if (!appContainer.classList.contains('sidebar-expanded')) {
          appContainer.classList.add('sidebar-expanded');
          if (window.innerWidth < 768) {
            setTimeout(() => { appContainer.classList.remove('sidebar-expanded'); }, 2000);
          }
        }
      }
    });
  });
});
</script>
</body>
</html>