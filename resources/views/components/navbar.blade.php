@props(['username'])

<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container-fluid navbar-inner">
        <a class="navbar-brand app-brand" href="{{ route('dashboard', ['username' => $username]) }}">
            @include('components.brand')
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNavigation" aria-controls="appNavigation" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="appNavigation">
            <div class="navbar-nav main-nav">
                <a class="app-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard', ['username' => $username]) }}"><i class="bi bi-grid-1x2"></i><span>Dashboard</span></a>
                <a class="app-nav-link {{ request()->routeIs('jemaat') ? 'active' : '' }}" href="{{ route('jemaat', ['username' => $username]) }}"><i class="bi bi-people"></i><span>Jemaat</span></a>
                <a class="app-nav-link {{ request()->routeIs('kehadiran') ? 'active' : '' }}" href="{{ route('kehadiran', ['username' => $username]) }}"><i class="bi bi-calendar2-check"></i><span>Kehadiran</span></a>
                <a class="app-nav-link {{ request()->routeIs('remaja') ? 'active' : '' }}" href="{{ route('remaja', ['username' => $username]) }}"><i class="bi bi-emoji-smile"></i><span>Remaja</span></a>
                <a class="app-nav-link {{ request()->routeIs('pemuda') ? 'active' : '' }}" href="{{ route('pemuda', ['username' => $username]) }}"><i class="bi bi-lightning"></i><span>Pemuda</span></a>
                <a class="app-nav-link {{ request()->routeIs('kas') ? 'active' : '' }}" href="{{ route('kas', ['username' => $username]) }}"><i class="bi bi-wallet2"></i><span>Kas</span></a>
                <a class="app-nav-link {{ request()->routeIs('persembahan') ? 'active' : '' }}" href="{{ route('persembahan', ['username' => $username]) }}"><i class="bi bi-heart"></i><span>Persembahan</span></a>
                <a class="app-nav-link {{ request()->routeIs('ibadah') ? 'active' : '' }}" href="{{ route('ibadah', ['username' => $username]) }}"><i class="bi bi-calendar-event"></i><span>Ibadah</span></a>
            </div>
            <div class="navbar-user">
                <a class="user-initial" href="{{ route('profile', ['username' => $username]) }}" aria-label="Profil {{ $username }}">{{ mb_strtoupper(mb_substr($username, 0, 1)) }}</a>
                <span class="user-name">{{ $username }}</span>
                <a href="{{ route('login') }}" class="logout-link" aria-label="Keluar" title="Keluar"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="page-topline">
    <div><span>GPdI DADAPAN</span><h1>@yield('page-title', 'Dashboard')</h1></div>
    <a class="topline-profile" href="{{ route('profile', ['username' => $username]) }}"><i class="bi bi-person-circle me-1"></i> Profil pengurus</a>
</div>
