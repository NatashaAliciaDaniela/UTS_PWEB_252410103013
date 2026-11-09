@props(['username'])

<header class="topbar">
    <div>
        <p class="topbar-kicker">GPdI DADAPAN</p>
        <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
    </div>
    <div class="topbar-user">
        <div class="topbar-user-copy"><span>Selamat melayani,</span><strong>{{ $username }}</strong></div>
        <span class="topbar-avatar">{{ mb_strtoupper(mb_substr($username, 0, 1)) }}</span>
        <a href="{{ route('profile', ['username' => $username]) }}" class="topbar-icon" aria-label="Profil" title="Profil"><i class="bi bi-person"></i></a>
        <a href="{{ route('login') }}" class="topbar-icon" aria-label="Keluar" title="Keluar"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</header>
