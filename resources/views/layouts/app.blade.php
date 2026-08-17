<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SMAN 2 Situbondo' }}</title>
    <!-- Tailwind CDN & Alpine.js for 100% Exact Layout & Animation Parsing Across All Pages -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @php
        $colorSetting = \App\Models\ColorSetting::first();
        $primaryColor = $colorSetting?->primary_color ?? '#1e3a8a';
        $secondaryColor = $colorSetting?->secondary_color ?? '#f59e0b';
    @endphp
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --primary-main: {{ $primaryColor }};
            --secondary-gold: {{ $secondaryColor }};
            --primary-deep: color-mix(in srgb, var(--primary-main) 80%, black);
            --primary-light: color-mix(in srgb, var(--primary-main) 12%, white);
            --secondary-hover: color-mix(in srgb, var(--secondary-gold) 85%, black);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased overflow-x-hidden">
    <!-- SHARED LAYOUT USER PUBLIK (Disepakati tim - Tema warna dinamis dari Admin) -->
    @yield('content')
</body>
</html>

