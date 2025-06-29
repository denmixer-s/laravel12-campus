<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Modern Web Solutions')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://www.skc.rmuti.ac.th">
    <link
        href="https://www.skc.rmuti.ac.th/kanit/css2.css?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    @vite(['resources/css/frontend.css', 'resources/js/frontend.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    @stack('styles')
</head>

<body x-data="pageTranslator" class="font-sans antialiased bg-white text-gray-900 overflow-x-hidden">
    <!-- Fixed Header with Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" x-data="navigation"
        :class="{
            'bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-200/50': scrolled,
            'bg-transparent': !scrolled
        }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div
                            class="size-10 lg:size-12 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-bolt text-white text-lg lg:text-xl"></i>
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="text-xl lg:text-2xl font-bold"
                                :class="scrolled ? 'text-gray-900' : 'text-white'">
                                {{ config('app.name', 'Sakon') }}
                            </h1>
                            <p class="text-xs lg:text-sm opacity-75"
                                :class="scrolled ? 'text-gray-600' : 'text-white/80'">
                                Digital Solutions
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:block">

                    <livewire:frontend.menu.frontend-menu location="header" variant="default" :show-icons="true"
                        :show-breadcrumbs="false" :cache-enabled="true" />


                </nav>
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Toggle -->
                    <livewire:frontend.language-switcher />
                </div>

                <!-- CTA Button & Mobile Menu -->
                <div class="flex items-center space-x-4">
                    <!-- CTA Button -->
                    <!-- Mobile Menu Toggle -->
                    <div class="lg:hidden">
                        <livewire:frontend.menu.frontend-menu show="header" :css-classes="[
                            'mobile' => 'mobile-menu-active',
                        ]" />
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Company Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="size-10 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center">
                            <i class="fas fa-bolt text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold">{{ config('app.name', 'Sakon') }}</h3>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        199 หมู่ 3 ถ.พังโคน-วาริชภูมิ ต.พังโคน อ.พังโคน
                        จ.สกลนคร 47160 </br>
                        โทรศัพท์: 0-4277-2285 || แฟกส์: 0-4277-2158
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="size-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors duration-300">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#"
                            class="size-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-sky-400 transition-colors duration-300">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="#"
                            class="size-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-700 transition-colors duration-300">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                        <a href="#"
                            class="size-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-pink-600 transition-colors duration-300">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                    </div>
                </div>


                <livewire:frontend.menu.frontend-menu location="Footer" variant="default" :show-icons="true"
                    :show-breadcrumbs="false" :cache-enabled="true" />
            </div>

            <!-- Footer Bottom -->
            <div
                class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} {{ config('app.name', 'Sakon') }}. All rights reserved.
                </p>
                <div class="flex items-center space-x-6 text-sm text-gray-400">
                    <a href="/privacy" class="hover:text-white transition-colors duration-200">Privacy Policy</a>
                    <a href="/terms" class="hover:text-white transition-colors duration-200">Terms of Service</a>
                    <a href="/cookies" class="hover:text-white transition-colors duration-200">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <div x-data="{ show: false }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90" @scroll.window="show = window.scrollY > 400"
        class="fixed bottom-8 right-8 z-40">
        <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="size-12 bg-gradient-to-r from-primary-600 to-secondary-600 rounded-full shadow-lg hover:shadow-xl flex items-center justify-center text-white hover:scale-110 transition-all duration-300">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Custom Scripts -->
    @stack('scripts')
</body>

</html>
