<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
      x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
     @vite(['resources/css/backend.css', 'resources/js/backend.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 lg:z-10 flex flex-col transition-all duration-300 ease-in-out"
             :class="{
                 'w-64': !sidebarCollapsed && !sidebarOpen,
                 'w-16': sidebarCollapsed && !sidebarOpen,
                 'w-64': sidebarOpen,
                 '-translate-x-full lg:translate-x-0': !sidebarOpen
             }">

            <!-- Sidebar Content -->
            <div class="flex flex-col flex-1 bg-white border-r border-gray-200 shadow-sm">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
                    <div class="flex items-center" :class="{ 'justify-center': sidebarCollapsed }">
                        <!-- Logo/Brand -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr(config('app.name', 'L'), 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3 transition-opacity duration-300"
                                 :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                                <h1 class="text-lg font-semibold text-gray-900 whitespace-nowrap">
                                    {{ config('app.name', 'Laravel') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <!-- Collapse Toggle (Desktop only) -->
                    <button @click="sidebarCollapsed = !sidebarCollapsed"
                            class="hidden lg:block p-1.5 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 transition-transform duration-300"
                             :class="{ 'rotate-180': sidebarCollapsed }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    @livewire('sakon.menus.backend-menu', ['collapsed' => 'sidebarCollapsed'])
                </nav>

                <!-- Sidebar Footer -->
                <div class="flex-shrink-0 p-4 border-t border-gray-200">
                    <div class="flex items-center" :class="{ 'justify-center': sidebarCollapsed }">
                        <div class="flex-shrink-0">
                            <img class="w-8 h-8 rounded-full"
                                 src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'User') . '&color=7F9CF5&background=EBF4FF' }}"
                                 alt="{{ auth()->user()->name ?? 'User' }}">
                        </div>
                        <div class="ml-3 transition-opacity duration-300"
                             :class="{ 'opacity-0 w-0 overflow-hidden': sidebarCollapsed, 'opacity-100': !sidebarCollapsed }">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ auth()->user()->name ?? 'User' }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ auth()->user()->email ?? 'user@example.com' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 transition-all duration-300 ease-in-out"
             :class="{
                 'lg:ml-64': !sidebarCollapsed,
                 'lg:ml-16': sidebarCollapsed
             }">

            <!-- Top Navigation Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center">
                <div class="flex items-center justify-between w-full px-4 sm:px-6 lg:px-8">
                    <!-- Left: Mobile menu button -->
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true"
                                class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Page Title -->
                        <h1 class="ml-4 lg:ml-0 text-xl font-semibold text-gray-900">
                            {{ $title ?? 'Dashboard' }}
                        </h1>
                    </div>

                    <!-- Right: User menu and notifications -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5zM15 17V7a6 6 0 00-12 0v10"></path>
                            </svg>
                            <!-- Notification badge -->
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-400"></span>
                        </button>

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <img class="w-8 h-8 rounded-full"
                                     src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'User') . '&color=7F9CF5&background=EBF4FF' }}"
                                     alt="{{ auth()->user()->name ?? 'User' }}">
                                <div class="ml-3 text-left hidden sm:block">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                                </div>
                                <svg class="ml-2 -mr-0.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <!-- User Info -->
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                                        <p class="text-sm text-gray-500 truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                                    </div>

                                    <!-- Menu Items -->
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Your Profile
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Settings
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Help & Support
                                    </a>

                                    <div class="border-t border-gray-100"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
        <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
