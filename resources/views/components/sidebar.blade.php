@props(['username'])

<aside class="app-sidebar" id="appSidebar">
    <div class="sidebar-head">
        <a class="sidebar-brand" href="{{ route('dashboard', ['username' => $username]) }}">
            @include('components.brand')
        </a>
        <button class="sidebar-toggle" type="button" aria-label="Minimalkan menu" aria-expanded="true" aria-controls="appSidebar">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>

    <p class="sidebar-label">MENU UTAMA</p>
    <nav class="sidebar-nav" aria-label="Menu utama">
        <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard', ['username' => $username]) }}" title="Dashboard">
            <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('jemaat') ? 'active' : '' }}" href="{{ route('jemaat', ['username' => $username]) }}" title="Data Jemaat">
            <i class="bi bi-people"></i><span>Data Jemaat</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('kehadiran') ? 'active' : '' }}" href="{{ route('kehadiran', ['username' => $username]) }}" title="Kehadiran">
            <i class="bi bi-calendar2-check"></i><span>Kehadiran</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('remaja') ? 'active' : '' }}" href="{{ route('remaja', ['username' => $username]) }}" title="Remaja">
            <i class="bi bi-emoji-smile"></i><span>Remaja</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('pemuda') ? 'active' : '' }}" href="{{ route('pemuda', ['username' => $username]) }}" title="Pemuda">
            <i class="bi bi-lightning"></i><span>Pemuda</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('kas') ? 'active' : '' }}" href="{{ route('kas', ['username' => $username]) }}" title="Kas Gereja">
            <i class="bi bi-wallet2"></i><span>Kas Gereja</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('persembahan') ? 'active' : '' }}" href="{{ route('persembahan', ['username' => $username]) }}" title="Persembahan">
            <i class="bi bi-heart"></i><span>Persembahan</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('ibadah') ? 'active' : '' }}" href="{{ route('ibadah', ['username' => $username]) }}" title="Informasi Ibadah">
            <i class="bi bi-calendar-event"></i><span>Informasi Ibadah</span>
        </a>
    </nav>

    <div class="sidebar-bottom">
        <span class="sidebar-avatar">{{ mb_strtoupper(mb_substr($username, 0, 1)) }}</span>
        <span class="sidebar-user">{{ $username }}</span>
        <a href="{{ route('login') }}" class="sidebar-logout" aria-label="Keluar" title="Keluar"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</aside>
