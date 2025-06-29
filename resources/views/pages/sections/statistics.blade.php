<!-- Statistics Section -->
<section class="stats-section py-20 text-white relative overflow-hidden" x-data="statisticsCounter()">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-r from-primary-500 via-primary-600 to-accent-500"></div>

    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-repeat" style="background-image: url('data:image/svg+xml,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><g fill="%23ffffff" fill-opacity="0.1"><path d="M50 10L60 40L90 40L68 58L78 88L50 70L22 88L32 58L10 40L40 40z"/></g></svg>')"></div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-white/5 rounded-full animate-float hidden lg:block"></div>
    <div class="absolute top-20 right-20 w-16 h-16 bg-white/5 rounded-full animate-float animation-delay-1000 hidden lg:block"></div>
    <div class="absolute bottom-20 left-20 w-24 h-24 bg-white/5 rounded-full animate-float animation-delay-2000 hidden lg:block"></div>
    <div class="absolute bottom-10 right-10 w-12 h-12 bg-white/5 rounded-full animate-float animation-delay-300 hidden lg:block"></div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
            <h2 class="text-3xl md:text-4xl font-bold font-heading mb-4">
                {{ __('ความสำเร็จในตัวเลข') }}
            </h2>
            <p class="text-white/90 max-w-2xl mx-auto leading-relaxed">
                {{ __('ด้วยประสบการณ์และความเชี่ยวชาญ เราได้สร้างผลงานที่น่าภาคภูมิใจมากมาย') }}
            </p>
            <div class="w-24 h-1 bg-white/30 mx-auto mt-6 rounded-full"></div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @php
                $statistics = [
                    [
                        'number' => 15,
                        'suffix' => '+',
                        'title' => __('ปีประสบการณ์'),
                        'description' => __('ความเชี่ยวชาญที่สั่งสม'),
                        'icon' => 'fas fa-calendar-alt',
                        'color' => 'from-yellow-400 to-orange-500'
                    ],
                    [
                        'number' => 500,
                        'suffix' => '+',
                        'title' => __('ลูกค้าพึงพอใจ'),
                        'description' => __('ความไว้วางใจจากลูกค้า'),
                        'icon' => 'fas fa-users',
                        'color' => 'from-green-400 to-blue-500'
                    ],
                    [
                        'number' => 1000,
                        'suffix' => '+',
                        'title' => __('โครงการสำเร็จ'),
                        'description' => __('ผลงานที่ภาคภูมิใจ'),
                        'icon' => 'fas fa-project-diagram',
                        'color' => 'from-purple-400 to-pink-500'
                    ],
                    [
                        'number' => 24,
                        'suffix' => '/7',
                        'title' => __('บริการฉุกเฉิน'),
                        'description' => __('พร้อมให้บริการทุกเวลา'),
                        'icon' => 'fas fa-clock',
                        'color' => 'from-red-400 to-yellow-500'
                    ]
                ];
            @endphp

            @foreach($statistics as $index => $stat)
                <div class="text-center group"
                     x-data="animateOnScroll()"
                     x-intersect="setTimeout(() => { $el.classList.add('animate-fadeInUp'); animateCounter({{ $stat['number'] }}, '{{ $stat['suffix'] }}', $el.querySelector('.stats-counter')); }, {{ $index * 200 }})">

                    <!-- Icon Background -->
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto backdrop-blur-sm group-hover:scale-110 transition-all duration-500 shadow-lg">
                            <i class="{{ $stat['icon'] }} text-2xl text-white"></i>
                        </div>
                        <!-- Animated Ring -->
                        <div class="absolute inset-0 w-20 h-20 rounded-full mx-auto border-2 border-white/20 group-hover:border-white/40 transition-colors duration-500"></div>
                        <div class="absolute inset-0 w-20 h-20 rounded-full mx-auto border-t-2 border-white/60 animate-spin group-hover:border-t-white transition-colors duration-500" style="animation-duration: 3s;"></div>
                    </div>

                    <!-- Number -->
                    <div class="mb-4">
                        <div class="stats-counter text-4xl md:text-5xl font-bold mb-2 bg-gradient-to-r {{ $stat['color'] }} bg-clip-text text-transparent"
                             data-target="{{ $stat['number'] }}"
                             data-suffix="{{ $stat['suffix'] }}">
                            0{{ $stat['suffix'] }}
                        </div>
                    </div>

                    <!-- Title -->
                    <h3 class="text-lg md:text-xl font-bold mb-2 group-hover:text-yellow-300 transition-colors duration-300">
                        {{ $stat['title'] }}
                    </h3>

                    <!-- Description -->
                    <p class="text-white/80 text-sm leading-relaxed group-hover:text-white transition-colors duration-300">
                        {{ $stat['description'] }}
                    </p>

                    <!-- Progress Bar -->
                    <div class="mt-4 w-full bg-white/20 rounded-full h-1 overflow-hidden">
                        <div class="h-full bg-gradient-to-r {{ $stat['color'] }} rounded-full transform scale-x-0 group-hover:scale-x-100 transition-transform duration-1000 origin-left"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Additional Stats Row -->
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
            @php
                $additionalStats = [
                    [
                        'number' => 99,
                        'suffix' => '%',
                        'title' => __('ความพึงพอใจ'),
                        'icon' => 'fas fa-heart'
                    ],
                    [
                        'number' => 48,
                        'suffix' => 'ชม.',
                        'title' => __('เวลาตอบสนอง'),
                        'icon' => 'fas fa-stopwatch'
                    ],
                    [
                        'number' => 5,
                        'suffix' => '⭐',
                        'title' => __('คะแนนเฉลี่ย'),
                        'icon' => 'fas fa-star'
                    ],
                    [
                        'number' => 100,
                        'suffix' => '%',
                        'title' => __('มาตรฐานคุณภาพ'),
                        'icon' => 'fas fa-certificate'
                    ]
                ];
            @endphp

            @foreach($additionalStats as $index => $stat)
                <div class="text-center group opacity-80 hover:opacity-100 transition-opacity duration-300">
                    <div class="flex items-center justify-center mb-3">
                        <i class="{{ $stat['icon'] }} text-xl text-white/80 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <div class="stats-counter text-2xl font-bold text-white mb-1"
                         data-target="{{ $stat['number'] }}"
                         data-suffix="{{ $stat['suffix'] }}">
                        0{{ $stat['suffix'] }}
                    </div>
                    <div class="text-sm text-white/80 group-hover:text-white transition-colors duration-300">
                        {{ $stat['title'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-16" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 inline-block">
                <h3 class="text-2xl font-bold mb-4">
                    {{ __('พร้อมที่จะเป็นส่วนหนึ่งของความสำเร็จ?') }}
                </h3>
                <p class="text-white/90 mb-6 max-w-lg">
                    {{ __('เข้าร่วมกับลูกค้าร่วมร้อยคนที่ไว้วางใจให้เราดูแลโครงการของพวกเขา') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#contact" class="inline-flex items-center justify-center bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-phone mr-2"></i>
                        {{ __('เริ่มโครงการ') }}
                    </a>
                    <a href="#portfolio" class="inline-flex items-center justify-center border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary-600 transition-all duration-300">
                        <i class="fas fa-eye mr-2"></i>
                        {{ __('ดูผลงาน') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function statisticsCounter() {
    return {
        // This will be used by Alpine.js
    }
}

function animateCounter(target, suffix, element) {
    if (!element) return;

    const duration = 2000; // 2 seconds
    const start = performance.now();
    const startValue = 0;

    function updateCounter(currentTime) {
        const elapsed = currentTime - start;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function for smooth animation
        const easedProgress = easeOutCubic(progress);
        const currentValue = Math.floor(startValue + (target - startValue) * easedProgress);

        element.textContent = currentValue + suffix;

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target + suffix;
        }
    }

    requestAnimationFrame(updateCounter);
}

function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
}

// Auto-initialize counters when they come into view
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                const counters = entry.target.querySelectorAll('.stats-counter');

                counters.forEach((counter, index) => {
                    const target = parseInt(counter.dataset.target);
                    const suffix = counter.dataset.suffix || '';

                    setTimeout(() => {
                        animateCounter(target, suffix, counter);
                    }, index * 200);
                });

                entry.target.dataset.animated = 'true';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all stats sections
    document.querySelectorAll('.stats-section').forEach(section => {
        observer.observe(section);
    });
});
</script>
@endpush
