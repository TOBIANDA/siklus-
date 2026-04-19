<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siklus - Book Exchange Community</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&family=Lato:wght@300;400;700;900&family=DM+Serif+Display&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">

    <input type="checkbox" id="sidebar-toggle" class="hidden-toggle">
    <input type="checkbox" id="notif-toggle" class="hidden-toggle">
    <input type="checkbox" id="borrow-submenu-toggle" class="hidden-toggle">

    <header class="topbar">
        <label for="sidebar-toggle" class="hamburger" style="cursor:pointer;">&#9776;</label>
        
        <a class="logo-text" href="{{ route('home') }}">
            <img src="{{ asset('images/siklus.png') }}" alt="Siklus" style="height:72px;vertical-align:middle;"
                 onerror="this.style.display='none';this.insertAdjacentHTML('afterend','<span style=\'font-family:Caveat,cursive;font-size:28px;font-weight:700;\'>Siklus</span>')">
        </a>
        
        <div class="search">
            <img src="{{ asset('images/search.png') }}" alt="Search" style="width:16px;height:16px;">
            <form action="{{ route('search') }}" method="GET" style="flex:1;display:flex;">
                <input type="text" name="q" placeholder="Cari Judul buku, penulis atau kategori" value="{{ request('q') }}">
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
                <div class="nav-item" title="Home">
                    <img src="{{ asset('images/icon_home.png') }}" alt="Home" style="width:22px;height:22px;object-fit:contain;">
                </div>
                <span class="nav-label">Home</span>
            </a>
            
            <div class="nav-row {{ request()->routeIs('notifications') ? 'active' : '' }}">
                <label for="notif-toggle" class="nav-item" title="Notifikasi" style="position:relative; cursor:pointer;">
                    <img src="{{ asset('images/icon_notification.png') }}" alt="Notifications" style="width:22px;height:22px;object-fit:contain;">
                    <span style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:#EF4444;border-radius:50%;border:2px solid white;"></span>
                </label>
                <label for="notif-toggle" class="nav-label" style="cursor:pointer;">Notifications</label>
            </div>

            <div class="nav-group" style="width:100%;">
                <div class="nav-row {{ request()->routeIs('borrow') || request()->routeIs('lent') ? 'active' : '' }}">
                    <label for="borrow-submenu-toggle" class="nav-item" title="Book" style="cursor:pointer;">
                        <img src="{{ asset('images/icon_closed_book.png') }}" alt="Book" style="width:22px;height:22px;object-fit:contain;" 
                             onerror="this.src='{{ asset('images/icon_borrow.png') }}'">
                    </label>
                    <label for="borrow-submenu-toggle" class="nav-label" style="cursor:pointer; flex:1;">Book</label>
                </div>
                
                <div class="submenu">
                    <a href="{{ route('borrow') }}" class="submenu-item {{ request()->routeIs('borrow') ? 'active' : '' }}">
                        <img src="{{ asset('images/icon_borrow.png') }}" alt="Borrow" style="width:16px;"> Borrow
                    </a>
                    <a href="{{ route('lent') }}" class="submenu-item {{ request()->routeIs('lent') ? 'active' : '' }}">
                        <img src="{{ asset('images/icon_exchange.png') }}" alt="Lent" style="width:16px;"> Lent
                    </a>
                </div>
            </div>

            <a href="{{ route('messages') }}" class="nav-row {{ request()->routeIs('messages') ? 'active' : '' }}" style="text-decoration:none;">
                <div class="nav-item" title="Pesan">
                    <img src="{{ asset('images/icon_message.png') }}" alt="Messages" style="width:22px;height:22px;object-fit:contain;">
                </div>
                <span class="nav-label">Message</span>
            </a>

            <div class="spacer"></div>

            <a href="{{ route('profile') }}" class="nav-row {{ request()->routeIs('profile') ? 'active' : '' }}" style="text-decoration:none;">
                <div class="nav-item" title="Pengaturan">
                    <img src="{{ asset('images/icon_settings.png') }}" alt="Settings" style="width:22px;height:22px;object-fit:contain;">
                </div>
                <span class="nav-label">Settings</span>
            </a>
        </nav>

        <div class="notif-panel" id="notifPanel">
            <div class="notif-panel-header">
                <h2>Notification</h2>
                <label for="notif-toggle" class="notif-close" style="cursor:pointer;">&#10005;</label>
            </div>
            @include('partials.notifications')
        </div>

        <main class="page active" style="flex:1;overflow-y:auto; padding:24px;">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>