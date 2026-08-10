<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SMAN 2 Situbondo' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $colorSetting = \App\Models\ColorSetting::first();
        $primaryColor = $colorSetting?->primary_color ?? '#1e3a8a';
        $secondaryColor = $colorSetting?->secondary_color ?? '#f59e0b';
    @endphp
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <!-- SHARED LAYOUT USER PUBLIK (Disepakati tim - Tema warna dinamis dari Admin) -->
    @yield('content')
</body>
</html>

