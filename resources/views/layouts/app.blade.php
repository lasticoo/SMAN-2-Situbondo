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
            --secondary-main: {{ $secondaryColor }};
            --primary-deep: color-mix(in srgb, var(--primary-main) 85%, black);
            --primary-light: color-mix(in srgb, var(--primary-main) 12%, white);
            --secondary-hover: color-mix(in srgb, var(--secondary-gold) 85%, black);
        }
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Hanken Grotesk', 'Outfit', sans-serif; }

        /* Unified Theme Utility Classes across All Views & Footers */
        .bg-theme-primary { background-color: var(--primary-main) !important; }
        .text-theme-primary { color: var(--primary-main) !important; }
        .border-theme-primary { border-color: var(--primary-main) !important; }

        .bg-theme-secondary { background-color: var(--secondary-gold) !important; }
        .text-theme-secondary { color: var(--secondary-gold) !important; }
        .border-theme-secondary { border-color: var(--secondary-gold) !important; }

        .bg-theme-primary-deep { background-color: var(--primary-deep) !important; }
        .bg-theme-primary-light { background-color: var(--primary-light) !important; }
        .bg-theme-gradient {
            background: linear-gradient(135deg, var(--primary-main) 0%, var(--primary-deep) 100%) !important;
        }

        .hover-text-primary:hover { color: var(--primary-main) !important; }
        .hover-bg-primary:hover { background-color: var(--primary-main) !important; color: #ffffff !important; }
        .hover-border-primary:hover { border-color: var(--primary-main) !important; }

        .hover-text-secondary:hover,
        .hover\:text-theme-secondary:hover { color: var(--secondary-gold) !important; }
        .hover-bg-secondary:hover,
        .hover\:bg-theme-secondary:hover { background-color: var(--secondary-gold) !important; color: #020617 !important; }
        .hover-border-secondary:hover,
        .hover\:border-theme-secondary:hover { border-color: var(--secondary-gold) !important; }

        /* Group Hover Variants */
        .group:hover .group-hover-bg-secondary,
        .group:hover .group-hover\:bg-theme-secondary {
            background-color: var(--secondary-gold) !important;
            color: #020617 !important;
        }
        .group:hover .group-hover-border-secondary,
        .group:hover .group-hover\:border-theme-secondary {
            border-color: var(--secondary-gold) !important;
        }
        .group:hover .group-hover-text-secondary,
        .group:hover .group-hover\:text-theme-secondary {
            color: var(--secondary-gold) !important;
        }
        .group:hover .group-hover-text-slate-950,
        .group:hover .group-hover\:text-slate-950 {
            color: #020617 !important;
        }

        /* Smooth Micro-Animations */
        .spring-hover {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }
        .spring-hover:hover {
            transform: translateY(-2px);
        }

        @keyframes subtle-float {
            0%, 100% { transform: translateY(0) translate3d(0, 0, 0); }
            50% { transform: translateY(-6px) translate3d(0, 0, 0); }
        }
        .animate-float {
            animation: subtle-float 4.5s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(241, 158, 56, 0.4); }
            50% { box-shadow: 0 0 20px 6px rgba(241, 158, 56, 0.55); }
        }
        .glow-pulse {
            animation: pulse-glow 3s infinite;
        }
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
