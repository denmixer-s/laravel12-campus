<!-- Hero Section -->
<section id="home" class="relative min-h-screen flex items-center justify-center text-white overflow-hidden">
    <!-- Background with Livewire Carousel -->
    <div class="absolute inset-0 z-0">
        <livewire:sakon.sliders.carousel-slider
            :location="'both'"
            :autoplay="true"
            :autoplay-delay="5000"
            :show-indicators="true"
            :show-navigation="true"
            :pause-on-hover="true"
            :css-classes="[
                'container' => 'w-full h-full',
                'slide' => 'w-full h-full object-cover',
                'overlay' => 'absolute inset-0 bg-black/60'
            ]"
        />

        <!-- Fallback Background -->
        <div class="hero-bg absolute inset-0 z-0"></div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-black/70 z-10"></div>
    </div>

    {{-- <!-- Content -->
    <div class="container mx-auto px-4 text-center relative z-20">
        <div class="max-w-4xl mx-auto">
            <!-- Main Heading -->
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold font-heading mb-6 animate-fadeInUp">
                {{ __('ผู้เชี่ยวชาญด้านงานเชื่อม') }}<br>
                <span class="text-accent bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                    {{ __('คุณภาพระดับมืออาชีพ') }}
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="text-lg md:text-xl lg:text-2xl mb-8 text-gray-200 leading-relaxed animate-fadeInUp animation-delay-300">
                {{ __('เราให้บริการงานเชื่อมทุกประเภท ด้วยมาตรฐานสากลและประสบการณ์กว่า 15 ปี') }}<br>
                {{ __('พร้อมทีมช่างผู้เชี่ยวชาญและเทคโนโลยีที่ทันสมัย') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fadeInUp animation-delay-600">
                <a href="#contact" class="group bg-primary hover:bg-orange-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-phone mr-2 group-hover:animate-pulse"></i>
                    {{ __('ติดต่อเรา') }}
                </a>
                <a href="#portfolio" class="group border-2 border-white text-white hover:bg-white hover:text-primary px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 backdrop-blur-sm">
                    <i class="fas fa-eye mr-2 group-hover:scale-110 transition-transform"></i>
                    {{ __('ดูผลงาน') }}
                </a>
            </div>

            <!-- Key Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 animate-fadeInUp animation-delay-900">
                <div class="flex items-center justify-center space-x-3 text-sm md:text-base">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-certificate text-accent text-xl"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold">{{ __('มาตรฐานสากล') }}</div>
                        <div class="text-gray-300 text-sm">{{ __('ISO Certified') }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-center space-x-3 text-sm md:text-base">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-clock text-accent text-xl"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold">{{ __('บริการ 24/7') }}</div>
                        <div class="text-gray-300 text-sm">{{ __('ฉุกเฉินทุกเวลา') }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-center space-x-3 text-sm md:text-base">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-award text-accent text-xl"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold">{{ __('15+ ปีประสบการณ์') }}</div>
                        <div class="text-gray-300 text-sm">{{ __('ผู้เชี่ยวชาญ') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-20">
        <a href="#about" class="block text-white hover:text-accent transition-colors duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </a>
    </div> --}}

    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-primary/10 rounded-full animate-float hidden lg:block"></div>
    <div class="absolute top-40 right-20 w-16 h-16 bg-accent/10 rounded-full animate-float animation-delay-1000 hidden lg:block"></div>
    <div class="absolute bottom-40 left-20 w-12 h-12 bg-primary/20 rounded-full animate-float animation-delay-2000 hidden lg:block"></div>

</section>
