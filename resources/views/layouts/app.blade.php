<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $title ?? 'SMAN 2 Situbondo')</title>
    @stack('meta')
    
    <!-- Preconnect & Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700;800;900&family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome 6 Icons & AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    
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
        body { font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Hanken Grotesk', 'Outfit', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased overflow-x-hidden">
    <!-- SHARED LAYOUT USER PUBLIK (Disepakati tim - Tema warna dinamis dari Admin) -->
    @yield('content')
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 600,
                    once: true,
                    offset: 50
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
