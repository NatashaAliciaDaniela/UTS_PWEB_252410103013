<a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard', ['username' => $username]) }}">Dashboard</a>
<a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile', ['username' => $username]) }}">Profil</a>
<a class="nav-link {{ request()->routeIs('pengelolaan') ? 'active' : '' }}" href="{{ route('pengelolaan', ['username' => $username]) }}">Pengelolaan Mahasiswa</a>
