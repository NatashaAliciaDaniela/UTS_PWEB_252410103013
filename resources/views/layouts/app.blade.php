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
    @stack('scripts')
</body>
</html>
