<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 12 with Vue 3</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    @if (Route::currentRouteName() == 'welcome')
        <div id="app"></div>
    @else
        <div id="home"></div>
    @endif

    @php
        echo Route::currentRouteName();
    @endphp
    
</body>
</html>