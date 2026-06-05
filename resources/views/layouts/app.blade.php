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
    /* ── Logo swap light/dark ── */
    .logo-dark  { display: none !important; }
    .logo-light { display: inline-block !important; }
    [data-theme="dark"] .logo-dark  { display: inline-block !important; }
    [data-theme="dark"] .logo-light { display: none !important; }
    /* ===== DARK THEME ===== */
    [data-theme="dark"] {
        --dark: #F1F5F9;
        --white: #1E2433;
        --gray-light: #2A3045;
        --gray-border: #334155;
        --blue-light: #1E3A5F;
        --gray: #94A3B8;
    }

    /* Base */
    [data-theme="dark"] body {
        background: #0F1623;
        color: #F1F5F9;
    }

    /* Topbar */
    [data-theme="dark"] .topbar {
        background: linear-gradient(135deg, #1E2433, #1A2035);
        border-color: #334155;
        box-shadow: 0 2px 16px rgba(0,0,0,.4);
    }
    [data-theme="dark"] .logo-text { color: #F1F5F9; }
    [data-theme="dark"] .search {
        background: #2A3045;
        border: 1px solid #334155;
        box-shadow: inset 0 1px 3px rgba(0,0,0,.3);
    }
    [data-theme="dark"] .search input { color: #F1F5F9; }
    [data-theme="dark"] .search input::placeholder { color: #64748B; }
    [data-theme="dark"] .hamburger { color: #94A3B8; }

    /* Sidebar */
    [data-theme="dark"] .sidebar {
        background: linear-gradient(180deg, #1E2433, #1A2035);
        border-color: #334155;
        box-shadow: 2px 0 16px rgba(0,0,0,.3);
    }
    [data-theme="dark"] .nav-item { color: #64748B; }
    [data-theme="dark"] .nav-label { color: #64748B; }
    [data-theme="dark"] .nav-row:hover,
    [data-theme="dark"] .nav-row.active { background: rgba(37,99,235,.15); }
    [data-theme="dark"] .nav-row:hover .nav-label,
    [data-theme="dark"] .nav-row.active .nav-label { color: #60A5FA; }
    [data-theme="dark"] .nav-row:hover .nav-item,
    [data-theme="dark"] .nav-row.active .nav-item { color: #60A5FA; }
    [data-theme="dark"] .submenu-item { color: #64748B; }
    [data-theme="dark"] .submenu-item:hover,
    [data-theme="dark"] .submenu-item.active { color: #60A5FA; }

    /* Book Cards */
    [data-theme="dark"] .book-card {
        box-shadow: 0 4px 16px rgba(0,0,0,.5);
    }
    [data-theme="dark"] .book-card:hover {
        box-shadow: 0 20px 50px rgba(0,0,0,.7);
    }
    [data-theme="dark"] .book-card .card-title { color: #F1F5F9; }
    [data-theme="dark"] .book-card .card-author { color: #94A3B8; }

    /* Hero */
    [data-theme="dark"] .hero-dark {
        background: linear-gradient(135deg, #1E2A3A, #0F1623);
        border: 1px solid #334155;
        box-shadow: 0 4px 32px rgba(0,0,0,.4);
    }

    /* Section */
    [data-theme="dark"] .section-title {
        border-color: #60A5FA;
        color: #F1F5F9;
    }
    [data-theme="dark"] .section-tab { color: #94A3B8; }
    [data-theme="dark"] .section-tab.active { color: #60A5FA; border-bottom-color: #60A5FA; }
    [data-theme="dark"] .section { color: #F1F5F9; }

    /* Home page */
    [data-theme="dark"] .hero-info h1 { color: #F1F5F9; }
    [data-theme="dark"] .hero-info .author { color: rgba(241,245,249,.75); }
    [data-theme="dark"] .hero-info .desc { color: rgba(241,245,249,.65); }
    [data-theme="dark"] .explore-btn { color: #F1F5F9; border-bottom-color: #F1F5F9; }

    /* Notification Panel */
    [data-theme="dark"] .notif-panel {
        background: #1E2433;
        border-color: #334155;
    }
    [data-theme="dark"] .notif-panel-header { background: #1E2433; border-color: #334155; }
    [data-theme="dark"] .notif-panel-header h2 { color: #F1F5F9; }
    [data-theme="dark"] .notif-item { border-color: #334155; }
    [data-theme="dark"] .notif-item:hover { background: rgba(37,99,235,.12); }
    [data-theme="dark"] .notif-item.unread { background: rgba(37,99,235,.08); }
    [data-theme="dark"] .notif-name { color: #F1F5F9; }
    [data-theme="dark"] .notif-text { color: #94A3B8; }
    [data-theme="dark"] .notif-book { background: #2A3045; }
    [data-theme="dark"] .notif-close:hover { background: #2A3045; }

    /* Messages */
    [data-theme="dark"] .msg-sidebar {
        background: #1E2433;
        border-color: #334155;
    }
    [data-theme="dark"] .msg-item { border-color: #334155; }
    [data-theme="dark"] .msg-item:hover { background: #2A3045; }
    [data-theme="dark"] .msg-item.active { background: rgba(37,99,235,.15); }
    [data-theme="dark"] .msg-name { color: #F1F5F9; }
    [data-theme="dark"] .msg-preview { color: #64748B; }
    [data-theme="dark"] .chat-area {
        background: #1A2035;
        border-color: #334155;
    }
    [data-theme="dark"] .chat-header { border-color: #334155; }
    [data-theme="dark"] .chat-name { color: #F1F5F9; }
    [data-theme="dark"] .icon-btn { background: #2A3045; color: #94A3B8; }
    [data-theme="dark"] .bubble.recv {
        background: #2A3045;
        border-color: #334155;
        color: #F1F5F9;
    }
    [data-theme="dark"] .chat-input { background: #2A3045; color: #F1F5F9; }
    [data-theme="dark"] .chat-input::placeholder { color: #64748B; }
    [data-theme="dark"] .chat-input-area { border-color: #334155; }
    [data-theme="dark"] .chat-date { color: #64748B; }
    [data-theme="dark"] .secured { color: #64748B; }

    /* Profile */
    [data-theme="dark"] .stat-card {
        background: #1E2433;
        border-color: #334155;
    }
    [data-theme="dark"] .stat-card label { color: #94A3B8; }
    [data-theme="dark"] .stat-card .value { color: #F1F5F9; }
    [data-theme="dark"] .stat-icon { background: #2A3045; }
    [data-theme="dark"] .badge-item {
        background: #1E2433;
        border-color: #334155;
    }
    [data-theme="dark"] .badge-name { color: #F1F5F9; }
    [data-theme="dark"] .badge-desc { color: #94A3B8; }

    /* Borrow Cards */
    [data-theme="dark"] .borrow-card {
        background: #1E2433;
        border-color: #334155;
    }
    [data-theme="dark"] .bc-title { color: #F1F5F9; }
    [data-theme="dark"] .bc-author { color: #94A3B8; }
    [data-theme="dark"] .date-row { color: #64748B; }
    [data-theme="dark"] .msg-lender { border-color: #334155; }
    [data-theme="dark"] .bsection-title { color: #F1F5F9; }

    /* Lender Card (Book Page) */
    [data-theme="dark"] .lender-card {
        background: #1E2433;
        border-color: #334155;
    }
    [data-theme="dark"] .lc-name { color: #F1F5F9; }
    [data-theme="dark"] .lc-loc { color: #94A3B8; }

    /* Settings */
    [data-theme="dark"] .settings-card {
        background: #1E2433;
        border-color: #334155;
        box-shadow: 0 2px 12px rgba(0,0,0,.3);
    }
    [data-theme="dark"] .settings-row { border-color: #2A3045; }
    [data-theme="dark"] .settings-row:hover { background: #2A3045; }
    [data-theme="dark"] .settings-row-label strong { color: #F1F5F9; }
    [data-theme="dark"] .settings-row-label p { color: #94A3B8; }
    [data-theme="dark"] .settings-select {
        background: #2A3045;
        color: #F1F5F9;
        border-color: #334155;
    }
    [data-theme="dark"] .settings-section-title { color: #94A3B8; }

    /* Review Cards */
    [data-theme="dark"] .review-card {
        background: #1E2433;
        border-color: #334155;
    }
    [data-theme="dark"] .rev-name { color: #F1F5F9; }
    [data-theme="dark"] .rev-role { color: #94A3B8; }
    [data-theme="dark"] .rev-text { color: #CBD5E1; }

    /* Book desc tabs */
    [data-theme="dark"] .desc-tabs { border-color: #334155; }
    [data-theme="dark"] .desc-tab { color: #94A3B8; }
    [data-theme="dark"] .desc-tab.active { border-color: #60A5FA; color: #F1F5F9; }
    [data-theme="dark"] .book-desc p { color: #CBD5E1; }

    /* Generic text in dark mode */
    [data-theme="dark"] p { color: #CBD5E1; }
    [data-theme="dark"] h1, [data-theme="dark"] h2,
    [data-theme="dark"] h3, [data-theme="dark"] h4,
    [data-theme="dark"] h5, [data-theme="dark"] h6 { color: #F1F5F9; }
    [data-theme="dark"] .rev-text { color: #CBD5E1; }
    [data-theme="dark"] .rev-name { color: #F1F5F9; }
    [data-theme="dark"] .bc-message-btn { background: #2A3045; color: #94A3B8; border-color: #334155; }
    [data-theme="dark"] .bc-message-btn:hover { background: #334155; }
    [data-theme="dark"] .status-picker-menu { background: #1E2433; border-color: #334155; }
    [data-theme="dark"] .status-picker-menu .status-opt { }
    [data-theme="dark"] .borrow-section-empty { color: #94A3B8; }

    /* Scrollbars */
    [data-theme="dark"] ::-webkit-scrollbar-track { background: #0F1623; }
    [data-theme="dark"] ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    [data-theme="dark"] ::-webkit-scrollbar-thumb:hover { background: #475569; }

    /* ── Main content area ── */
    [data-theme="dark"] main,
    [data-theme="dark"] .page { background: #0F1623; }

    /* ── Page/section titles ── */
    [data-theme="dark"] .settings-page-title { color: #F1F5F9; }
    [data-theme="dark"] .settings-section-title,
    [data-theme="dark"] .settings-card-header h3 { color: #64748B; }

    /* ── Settings user card (already dark bg, just ensure contrast) ── */
    [data-theme="dark"] .settings-user-card { background: linear-gradient(135deg, #1E3A5F, #1E2433); border: 1px solid #334155; }

    /* ── Settings logout ── */
    [data-theme="dark"] .logout-btn { color: #F87171; }
    [data-theme="dark"] .logout-btn:hover { background: rgba(239,68,68,.1); }
    [data-theme="dark"] .logout-dot { background: #F87171; }

    /* ── Password modal ── */
    [data-theme="dark"] .pw-modal-box { background: #1E2433; border: 1px solid #334155; }
    [data-theme="dark"] .pw-modal-box h2 { color: #F1F5F9; }
    [data-theme="dark"] .pw-field label { color: #94A3B8; }
    [data-theme="dark"] .pw-field input { background: #2A3045; border-color: #334155; color: #F1F5F9; }
    [data-theme="dark"] .pw-field input::placeholder { color: #64748B; }
    [data-theme="dark"] .pw-field input:focus { border-color: #60A5FA; }
    [data-theme="dark"] .pw-modal-close { color: #94A3B8; }
    [data-theme="dark"] .pw-modal-close:hover { background: #2A3045; }

    /* ── Theme / size chips ── */
    [data-theme="dark"] .theme-chip { background: #2A3045; border-color: #334155; color: #94A3B8; }
    [data-theme="dark"] .theme-chip.active-dark { background: #3B5BDB; border-color: #3B5BDB; color: white; }
    [data-theme="dark"] .theme-chip.active-light { background: #1E3A5F; border-color: #60A5FA; color: #60A5FA; }
    [data-theme="dark"] .size-chip { background: #2A3045; border-color: #334155; color: #94A3B8; }
    [data-theme="dark"] .size-chip.active { background: #1E3A5F; border-color: #60A5FA; color: #60A5FA; }

    /* ── Toggle slider in dark mode ── */
    [data-theme="dark"] .toggle-slider { background: #334155; }

    /* ── Row chevron ── */
    [data-theme="dark"] .row-chevron { color: #475569; }

    /* ── Messages: borrow request card inside chat ── */
    [data-theme="dark"] .borrow-request-card,
    [data-theme="dark"] .notif-book { background: #2A3045; border-color: #334155; }

    /* ── Borrow stats in dark mode ── */
    [data-theme="dark"] .borrow-stat { background: #1E2433; border: 1px solid #334155; }
    [data-theme="dark"] .borrow-stat-label { color: #94A3B8; }
    [data-theme="dark"] .borrow-stat-value { color: #F1F5F9; }
    [data-theme="dark"] .bsection-title { color: #F1F5F9; }
    [data-theme="dark"] .borrow-tab { color: #94A3B8; }
    [data-theme="dark"] .borrow-tab.active { background: #3B5BDB; color: white; }
    [data-theme="dark"] .borrow-card { background: #1E2433; border-color: #334155; }
    [data-theme="dark"] .bc-title { color: #F1F5F9; }
    [data-theme="dark"] .bc-author { color: #94A3B8; }
    [data-theme="dark"] .date-row { color: #64748B; }
    [data-theme="dark"] .msg-lender { border-color: #334155; }
    [data-theme="dark"] .lender-i { color: #94A3B8; }
    [data-theme="dark"] .lender-i a { color: #94A3B8; }

    /* ── Share card in messages ── */
    [data-theme="dark"] .share-card { background: #1E2433; border-color: #334155; }
    [data-theme="dark"] .share-thumb { background: #0F1623; }
    [data-theme="dark"] .share-info { color: #F1F5F9; }

    /* ── Lent / book catalog ── */
    [data-theme="dark"] .lent-card { background: #1E2433; border-color: #334155; }

    /* ── Search page ── */
    [data-theme="dark"] .search-result-card { background: #1E2433; border-color: #334155; }

    /* ── Section underline highlight ── */
    [data-theme="dark"] .section-title { border-color: #60A5FA; color: #F1F5F9; }

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
              <img src="{{ asset('images/siklus.png') }}" alt="Siklus" class="logo-light" style="height:72px;vertical-align:middle;"
                   onerror="this.style.display='none'">
              <img src="{{ asset('images/sikklus.png') }}" alt="Siklus" class="logo-dark" style="height:72px;vertical-align:middle;display:none;"
                   onerror="this.style.display='none'">
          </a>
          
          <div class="search">
              <img src="{{ asset('images/search.png') }}" alt="Search" style="width:16px;height:16px;">
              <form action="{{ route('search') }}" method="GET" style="flex:1;display:flex;">
                  <input type="text" name="q" placeholder="{{ __('navigation.search_placeholder') }}" value="{{ request('q') }}">
              </form>
          </div>

          <a href="{{ route('profile') }}" class="top-avatar">
              <img src="{{ auth()->user()->avatar_url }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;" 
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
                      @if(auth()->check() && auth()->user()->unread_notifications_count > 0)
                      <span style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:#EF4444;border-radius:50%;border:2px solid white;"></span>
                      @endif
                  </div>
                  <div class="nav-label btn-notif-toggle" style="cursor:pointer;">{{ __('navigation.notifications') }}</div>
              </div>


              <div class="nav-row {{ request()->routeIs('borrow') || request()->routeIs('lent') ? 'active' : '' }}" id="book-nav-row">
                  <div class="nav-item btn-borrow-toggle" title="{{ __('navigation.book') }}" style="cursor:pointer;">
                  <img src="{{ asset('images/solar_book-broken.png') }}" alt="{{ __('navigation.book') }}" style="width:22px;height:22px;object-fit:contain;"
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

              <a href="{{ route('friends') }}" class="nav-row {{ request()->routeIs('friends') ? 'active' : '' }}" style="text-decoration:none;">
                  <div class="nav-item" title="{{ __('navigation.friends') }}" style="position:relative;">
                      <img src="{{ asset('images/icon_friend.png') }}" alt="{{ __('navigation.friends') }}" style="width:22px;height:22px;object-fit:contain;">
                      @if(auth()->check() && auth()->user()->pending_friend_requests_count > 0)
                      <span style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:#EF4444;border-radius:50%;border:2px solid white;"></span>
                      @endif
                  </div>
                  <span class="nav-label">{{ __('navigation.friends') }}</span>
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

  // ===== DYNAMIC TOPBAR HEIGHT =====
  function syncNotifPanelPosition() {
    const topbar = document.querySelector('.topbar');
    const notifPanel = document.getElementById('notifPanel');
    if (!topbar || !notifPanel) return;
    const h = topbar.offsetHeight;
    notifPanel.style.top    = h + 'px';
    notifPanel.style.height = 'calc(100vh - ' + h + 'px)';
  }
  syncNotifPanelPosition();
  window.addEventListener('resize', syncNotifPanelPosition);

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