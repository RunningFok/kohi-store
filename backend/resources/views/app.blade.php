<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Shop premium coffee products at kohiSTORE. Browse our collection of high-quality coffee beans and accessories.">
    <meta name="keywords" content="coffee, coffee shop, coffee beans, premium coffee, buy coffee online, kohiSTORE">
    
    <title>{{ config('app.name', 'kohiSTORE') }} - Premium Coffee Bean</title>
    
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
