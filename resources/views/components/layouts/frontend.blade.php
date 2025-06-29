<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('frontend.partials.head')
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased" x-data="{
            sidebarOpen: localStorage.getItem('sidebarOpen') === null ? window.innerWidth >= 1024 : localStorage.getItem('sidebarOpen') === 'true',
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                localStorage.setItem('sidebarOpen', this.sidebarOpen);
            },
            temporarilyOpenSidebar() {
                if (!this.sidebarOpen) {
                    this.sidebarOpen = true;
                    localStorage.setItem('sidebarOpen', true);
                }
            },
            formSubmitted: false,
        }">

    <!-- Main Container -->
    <div class="min-h-screen flex flex-col">

        @include('frontend.partials.nav')

        <!-- Main Content Area -->
        <div class="flex flex-1 overflow-hidden">



            <!-- Main Content -->
            <main class="flex-1 overflow-auto bg-gray-100 dark:bg-gray-900 content-transition">
                <div class="p-6">
                    <!-- Success Message -->
                    @session('status')
                        <div x-data="{ showStatusMessage: true }" x-show="showStatusMessage"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="mb-6 bg-green-50 dark:bg-green-900 border-l-4 border-green-500 p-4 rounded-md">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500 dark:text-green-400"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700 dark:text-green-200">{{ session('status') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <div class="-mx-1.5 -my-1.5">
                                        <button @click="showStatusMessage = false"
                                            class="inline-flex rounded-md p-1.5 text-green-500 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <span class="sr-only">{{ __('Dismiss') }}</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endsession

                    {{ $slot }}

                </div>
            </main>
        </div>
        @include('frontend.partials.footer')
    </div>
            <script>
                let currentSlide = 0;
                const slides = document.querySelectorAll('.carousel-item');
                const indicators = document.querySelectorAll('.bottom-2 button, .bottom-4 button');
                const progressBar = document.querySelector('.progress-bar');
                let autoAdvanceTimer;
                let touchStartX = 0;
                let touchEndX = 0;
                const carousel = document.querySelector('.carousel-track');

                // Add touch events for swipe
                carousel.addEventListener('touchstart', e => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                carousel.addEventListener('touchend', e => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }, { passive: true });

                function handleSwipe() {
                    const swipeThreshold = 50;
                    const diff = touchStartX - touchEndX;

                    if (Math.abs(diff) > swipeThreshold) {
                        if (diff > 0) {
                            nextSlide();
                        } else {
                            prevSlide();
                        }
                    }
                }

                function updateSlides() {
                    slides.forEach((slide, index) => {
                        slide.className = 'carousel-item absolute top-0 left-0 w-full h-full';
                        if (index === currentSlide) {
                            slide.classList.add('active');
                        } else if (index === (currentSlide + 1) % slides.length) {
                            slide.classList.add('next');
                        } else if (index === (currentSlide - 1 + slides.length) % slides.length) {
                            slide.classList.add('prev');
                        } else {
                            slide.classList.add('hidden');
                        }
                    });

                    // Update indicators
                    indicators.forEach((indicator, index) => {
                        indicator.className = `w-8 sm:w-12 h-1 sm:h-1.5 rounded-full transition-colors ${
                            index === currentSlide ? 'bg-white/40' : 'bg-white/20'
                        } hover:bg-white/60`;
                    });

                    // Update progress bar
                    progressBar.style.width = `${((currentSlide + 1) / slides.length) * 100}%`;
                }

                function resetAutoAdvance() {
                    clearInterval(autoAdvanceTimer);
                    autoAdvanceTimer = setInterval(nextSlide, 5000);
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slides.length;
                    updateSlides();
                    resetAutoAdvance();
                }

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    updateSlides();
                    resetAutoAdvance();
                }

                // Add click handlers to indicators
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        currentSlide = index;
                        updateSlides();
                        resetAutoAdvance();
                    });
                });

                // Initialize auto advance
                resetAutoAdvance();

                // Initialize slides
                updateSlides();
        </script>
</body>

</html>
