<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'มทร.อีสาน-วิทยาเขตสกลนคร' }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $description ?? 'มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน วิทยาเขตสกลนคร' }}">
    <meta name="keywords" content="{{ $keywords ?? 'มทร.อีสาน, สกลนคร, มหาวิทยาลัย, เทคโนโลยี' }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $title ?? 'มทร.อีสาน-วิทยาเขตสกลนคร' }}">
    <meta property="og:description" content="{{ $description ?? 'มหาวิทยาลัยเทคโนโลยีราชมงคลอีسาน วิทยาเขตสกลนคร' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
     @vite(['resources/css/frontend.css', 'resources/js/frontend.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Custom Styles -->

     @stack('styles')
</head>
<body class="bg-gradient-to-br from-orange-50 via-gray-50 to-gray-100">
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-orange-500 to-gray-400 text-white py-2 px-4 text-sm">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span><i class="fas fa-phone mr-2"></i>042-761-000</span>
                <span><i class="fas fa-envelope mr-2"></i>info@skc.rmuti.ac.th</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="hover:text-orange-200 transition-colors">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" class="hover:text-orange-200 transition-colors">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="#" class="hover:text-orange-200 transition-colors">
                    <i class="fab fa-line"></i>
                </a>
                <a href="#" class="hover:text-orange-200 transition-colors">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-4">
                    <img src="https://picsum.photos/60/60" alt="RMUTI Logo">
                    <div>
                        <h1 class="text-xl font-bold text-blue-900">มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน</h1>
                        <p class="text-sm text-gray-600">วิทยาเขตสกลนคร</p>
                    </div>
                </div>

                <!-- Right Side - Language & Mobile Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative dropdown">
                        <livewire:frontend.language-switcher />
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="w-full">
            <!-- Navigation Section (Full Width) -->
            <div class="w-full bg-orange-300">
                <!-- Desktop Menu -->
                <div class="hidden lg:block">
                    <livewire:frontend.menu.mega-menu />
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <livewire:frontend.menu.mega-menu />
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    @if(isset($breadcrumb) && $breadcrumb)
        <nav class="bg-gray-100 py-3">
            <div class="container mx-auto px-4">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-home"></i> หน้าแรก
                        </a>
                    </li>
                    {{ $breadcrumb }}
                </ol>
            </div>
        </nav>
    @endif

    <!-- Main Content -->
    <main x-data="pageTranslator" class="min-h-screen">
        <!-- Hero Section (if provided) -->
        @if(isset($heroSection))
            <section class="relative bg-gradient-to-r from-orange-100 via-orange-50 to-gray-50 text-gray-800">
                {{ $heroSection }}
            </section>
        @endif

        <!-- Page Header -->
        @if(isset($pageHeader))
            <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
                <div class="container mx-auto px-4">
                    {{ $pageHeader }}
                </div>
            </section>
        @endif

        <!-- Content -->
        <div class="{{ isset($containerClass) ? $containerClass : 'container mx-auto px-4 py-8' }}">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-orange-500 to-gray-400 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Contact Info -->
                <div>
                    <h5 class="font-bold text-lg mb-4">ติดต่อเรา</h5>
                    <div class="space-y-2 text-sm">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>123 ถนนมหาวิทยาลัย</p>
                        <p>ตำบลธาตุเชิงชุม อำเภอเมือง</p>
                        <p>จังหวัดสกลนคร 47000</p>
                        <p><i class="fas fa-phone mr-2"></i>042-761-000</p>
                        <p><i class="fas fa-fax mr-2"></i>042-761-001</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@skc.rmuti.ac.th</p>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h5 class="font-bold text-lg mb-4">ลิงก์ด่วน</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-orange-200 transition-colors">รับเข้าศึกษา</a></li>
                        <li><a href="#" class="hover:text-orange-200 transition-colors">ระบบสารสนเทศ</a></li>
                        <li><a href="#" class="hover:text-orange-200 transition-colors">บุคลากร</a></li>
                        <li><a href="#" class="hover:text-orange-200 transition-colors">หลักสูตร</a></li>
                        <li><a href="#" class="hover:text-orange-200 transition-colors">การวิจัย</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div>
                    <h5 class="font-bold text-lg mb-4">บริการ</h5>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-orange-200 transition-colors">ห้องสมุด</a></li>
                        <li><a href="#" class="hover:text-orange-200 transition-colors">ศูนย์คอมพิวเตอร์</a></li>
                        <li><a href="#" class="hover:text-orange-200 transition-colors">งานทะเบียน</a></li>
                        <li><a href="#" class="hover:text-orange-200 transition-colors">กิจการนักศึกษา</a></li>
                        <li><a href="#" class="hover:text-orange-200 transition-colors">งานประกันคุณภาพ</a></li>
                    </ul>
                </div>
                
                <!-- Social Media -->
                <div>
                    <h5 class="font-bold text-lg mb-4">ติดตามเรา</h5>
                    <div class="flex space-x-4 mb-4">
                        <a href="#" class="text-2xl hover:text-orange-200 transition-colors">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-2xl hover:text-orange-200 transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="text-2xl hover:text-orange-200 transition-colors">
                            <i class="fab fa-line"></i>
                        </a>
                        <a href="#" class="text-2xl hover:text-orange-200 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    

                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-orange-300 mt-8 pt-8 text-center">
                <p class="text-sm">
                    &copy; {{ date('Y') }} มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน วิทยาเขตสกลนคร สงวนลิขสิทธิ์
                </p>
                <div class="mt-2 space-x-4 text-xs">
                    <a href="#" class="hover:text-orange-200">นโยบายความเป็นส่วนตัว</a>
                    <a href="#" class="hover:text-orange-200">เงื่อนไขการใช้งาน</a>
                    <a href="#" class="hover:text-orange-200">แผนผังเว็บไซต์</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-4 right-4 bg-orange-500 hover:bg-orange-600 text-white p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 invisible">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Livewire Scripts -->
    @livewireScripts



    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>