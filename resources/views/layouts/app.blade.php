<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#132c4a">
    <title>@yield('title', 'GPdI Dadapan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    @if (isset($username) && ! ($hideNavigation ?? false))
        <div class="app-shell">
            <x-sidebar :username="$username" />
            <div class="app-main">
                <x-navbar :username="$username" />
                <main class="app-content">@yield('content')</main>
                <x-footer />
            </div>
        </div>
    @else
        <main>
            @yield('content')
        </main>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const appShell = document.querySelector('.app-shell');
        const sidebarToggle = document.querySelector('.sidebar-toggle');

        if (appShell && sidebarToggle) {
            const storageKey = 'gpdidadapan.sidebar.collapsed';
            const setCollapsed = (collapsed) => {
                appShell.classList.toggle('sidebar-collapsed', collapsed);
                sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
                sidebarToggle.setAttribute('aria-label', collapsed ? 'Perluas menu' : 'Minimalkan menu');
            };

            setCollapsed(localStorage.getItem(storageKey) === 'true');
            sidebarToggle.addEventListener('click', () => {
                const collapsed = !appShell.classList.contains('sidebar-collapsed');
                setCollapsed(collapsed);
                localStorage.setItem(storageKey, String(collapsed));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
