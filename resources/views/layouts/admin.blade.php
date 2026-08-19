<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Admin SMADA' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.theme-variables')
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased">
    <!-- SHARED LAYOUT ADMIN PANEL (Disepakati tim) -->
    @yield('content')
</body>
</html>
