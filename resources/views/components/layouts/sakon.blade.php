<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SAKON') }} - @yield('title', 'Welding Services')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'ผู้เชี่ยวชาญด้านงานเชื่อมและงานโลหะ ด้วยประสบการณ์กว่า 15 ปี พร้อมให้บริการด้วยมาตรฐานระดับสากล')">
    <meta name="keywords" content="@yield('keywords', 'งานเชื่อม, เชื่อม TIG, เชื่อม MIG, โครงสร้างเหล็ก, ซ่อมแซม, ตัดโลหะ')">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'ผู้เชี่ยวชาญด้านงานเชื่อมและงานโลหะ')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Preload Critical Assets -->
    <link rel="preconnect" href="https://www.skc.rmuti.ac.th">
    <link
        href="https://www.skc.rmuti.ac.th/kanit/css2.css?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Vite Assets -->
    @vite(['resources/css/frontend.css', 'resources/js/frontend.js', 'resources/css/translator.css', 'resources/js/translator.js'])

    @stack('styles')
    @livewireStyles
</head>

<body class="font-body antialiased">

    <!-- Header -->
    @include('sakon.partials.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('sakon.partials.footer')

    <!-- Back to Top Button -->
    <button id="backToTop"
        class="fixed bottom-8 right-8 bg-primary hover:bg-orange-600 text-white w-12 h-12 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    @stack('scripts')
    @livewireScripts


</body>

</html>
