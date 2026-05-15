<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>La Sangre Berlín</title>
    <meta name="description" content="La Sangre Berlín — Banda de rock de Berlín, Alemania. Conciertos, discografía, galería y contacto.">
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="La Sangre Berlín">
    <meta property="og:description" content="Rock desde las entrañas de Berlín. Conciertos, discografía y contacto.">
    <meta property="og:image" content="{{ config('app.url') }}/img/02-La-Sangre_Logotype-3-colors.png">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ config('app.url') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/img/calavera.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Oswald:wght@300;400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-noir text-cream">
    <div id="app"></div>
</body>
</html>
