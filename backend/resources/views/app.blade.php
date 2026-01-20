<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'KohiStore') }}</title>
    
    <!-- Preload critical banner images for faster LCP -->
    <link rel="preload" as="image" href="/images/banner_web.webp" type="image/webp" media="(min-width: 768px)" />
    <link rel="preload" as="image" href="/images/banner_mobile.webp" type="image/webp" media="(max-width: 767px)" />
    <link rel="preload" as="image" href="/images/banner_web.jpg" type="image/jpeg" media="(min-width: 768px)" />
    <link rel="preload" as="image" href="/images/banner_mobile.jpg" type="image/jpeg" media="(max-width: 767px)" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
