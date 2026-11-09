@props(['username'])

<nav class="navbar navbar-expand-lg navbar-dark site-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard', ['username' => $username]) }}">
            @include('components.brand')
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavigation">
            <div class="navbar-nav ms-lg-5 me-auto">
                @include('components.nav-links', ['username' => $username])
            </div>
            <div class="d-flex align-items-center gap-3 py-3 py-lg-0">
                <a class="user-chip" href="{{ route('profile', ['username' => $username]) }}">
                    <span class="avatar avatar-nav">{{ mb_strtoupper(mb_substr($username, 0, 1)) }}</span>
                    <span class="small fw-semibold">{{ $username }}</span>
                </a>
                <a class="nav-logout" href="{{ route('login') }}" aria-label="Kembali ke halaman login" title="Kembali ke login">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</nav>
