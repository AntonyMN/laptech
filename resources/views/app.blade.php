<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laptech') }} — Innovative Tech Hub</title>

        <!-- Preloaded SEO Meta (defaults for crawlers before JS executes) -->
        <meta name="description" content="Laptech is your premier gateway to elite hardware and precision technical services in Nairobi, Kenya. Enterprise servers, networking, security, and expert IT solutions.">
        <meta name="keywords" content="Laptech, IT hardware, enterprise servers, networking equipment, IT services, Nairobi, Kenya, tech solutions, Dell, HPE, computer repair">
        <meta name="author" content="Laptech">
        <meta name="robots" content="index, follow">
        <meta name="theme-color" content="#1A1A1A">
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph (defaults — overridden per-page by Inertia <Head>) -->
        <meta property="og:site_name" content="Laptech — Innovative Tech Hub">
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Laptech — Innovative Tech Hub">
        <meta property="og:description" content="Your premier gateway to elite hardware and precision technical services. Enterprise servers, networking, security, and expert IT solutions in Nairobi, Kenya.">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('images/logo.png') }}">
        <meta property="og:image:width" content="512">
        <meta property="og:image:height" content="512">
        <meta property="og:image:alt" content="Laptech Logo">

        <!-- Twitter Card (defaults) -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Laptech — Innovative Tech Hub">
        <meta name="twitter:description" content="Elite hardware and precision technical services. Enterprise servers, networking, security, and IT solutions in Nairobi, Kenya.">
        <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/favicon.png">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

        <!-- JSON-LD Structured Data -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Laptech",
            "url": "{{ config('app.url') }}",
            "logo": "{{ asset('images/logo.png') }}",
            "description": "Premier gateway to elite hardware and precision technical services in Nairobi, Kenya.",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Tech Plaza",
                "addressLocality": "Nairobi",
                "addressCountry": "KE"
            },
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+254-700-000-000",
                "contactType": "sales",
                "email": "specialists@laptech.co.ke"
            },
            "sameAs": []
        }
        </script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
