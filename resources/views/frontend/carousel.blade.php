<div class="bg-gray-200 h-full flex items-center justify-center overflow-hidden p-2 sm:p-4">
    <!-- Background effects -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute top-1/4 left-1/4 w-48 h-48 sm:w-96 sm:h-96 bg-violet-500/10 rounded-full filter blur-3xl">
        </div>
        <div
            class="absolute bottom-1/4 right-1/4 w-48 h-48 sm:w-96 sm:h-96 bg-fuchsia-500/10 rounded-full filter blur-3xl">
        </div>
    </div>

    <!-- Main container -->
    <div class="w-full max-w-6xl mx-auto">
        <!-- Carousel container -->
        <div class="carousel-container relative">
            <!-- Progress bar -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-white/10 rounded-full overflow-hidden z-20">
                <div
                    class="progress-bar absolute top-0 left-0 h-full w-1/3 bg-gradient-to-r from-violet-500 to-fuchsia-500">
                </div>
            </div>

            <!-- Navigation buttons -->
            <button
                class="nav-button absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center z-20 text-white touch-manipulation"
                onclick="prevSlide()" title="Previous slide">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button
                class="nav-button absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center z-20 text-white touch-manipulation"
                onclick="nextSlide()" title="Next slide">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Carousel track -->
            <div class="carousel-track relative h-[400px] sm:h-[500px] md:h-[600px] overflow-hidden">
                <!-- Carousel items -->
                <div class="carousel-item active absolute top-0 left-0 w-full h-full">
                    <div class="w-full h-full p-4 sm:p-8">
                        <div class="w-full h-full rounded-xl sm:rounded-2xl overflow-hidden relative group">
                            <img src="https://www.skc.rmuti.ac.th/storage/714/conversions/6848e4e8600be_506129866_1037336121923506_3669372999327552169_n-thumb.jpg"
                                alt="Geometric art installation"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-violet-500/40 to-purple-500/40 mix-blend-overlay">
                            </div>
                            <div
                                class="absolute inset-x-0 bottom-0 p-4 sm:p-8 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                {{-- <h3 class="text-white text-xl sm:text-2xl md:text-3xl font-bold mb-2 sm:mb-3">Digital
                                    Prism</h3>
                                <p class="text-gray-200 text-sm sm:text-base md:text-lg max-w-2xl">Where geometry meets
                                    art in a stunning display of light and form.</p> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-item next absolute top-0 left-0 w-full h-full">
                    <div class="w-full h-full p-4 sm:p-8">
                        <div class="w-full h-full rounded-xl sm:rounded-2xl overflow-hidden relative group">
                            <img src="https://www.skc.rmuti.ac.th/storage/713/conversions/6848e42c23c5e_505724190_23941481695468863_3429004346998356457_n-thumb.jpg"
                                alt="Futuristic tech setup"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/40 to-pink-500/40 mix-blend-overlay">
                            </div>
                            <div
                                class="absolute inset-x-0 bottom-0 p-4 sm:p-8 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                {{-- <h3 class="text-white text-xl sm:text-2xl md:text-3xl font-bold mb-2 sm:mb-3">Tech Haven
                                </h3>
                                <p class="text-gray-200 text-sm sm:text-base md:text-lg max-w-2xl">Immerse yourself in
                                    the cutting edge of technology and innovation.</p> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-item hidden absolute top-0 left-0 w-full h-full">
                    <div class="w-full h-full p-4 sm:p-8">
                        <div class="w-full h-full rounded-xl sm:rounded-2xl overflow-hidden relative group">
                            <img src="https://www.skc.rmuti.ac.th/storage/712/conversions/6848e417600b5_506037062_23941481698802196_3220670852382533605_n-thumb.jpg"
                                alt="Abstract digital art"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-pink-500/40 to-rose-500/40 mix-blend-overlay">
                            </div>
                            <div
                                class="absolute inset-x-0 bottom-0 p-4 sm:p-8 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                {{-- <h3 class="text-white text-xl sm:text-2xl md:text-3xl font-bold mb-2 sm:mb-3">Neural
                                    Dreams</h3>
                                <p class="text-gray-200 text-sm sm:text-base md:text-lg max-w-2xl">AI-generated
                                    masterpieces that blur the line between human and machine creativity.</p> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-item next absolute top-0 left-0 w-full h-full">
                    <div class="w-full h-full p-4 sm:p-8">
                        <div class="w-full h-full rounded-xl sm:rounded-2xl overflow-hidden relative group">
                            <img src="https://www.skc.rmuti.ac.th/storage/634/conversions/67dcdc6fe9fc0_NSTIS-thumb.jpg"
                                alt="Futuristic tech setup"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/40 to-pink-500/40 mix-blend-overlay">
                            </div>
                            <div
                                class="absolute inset-x-0 bottom-0 p-4 sm:p-8 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                {{-- <h3 class="text-white text-xl sm:text-2xl md:text-3xl font-bold mb-2 sm:mb-3">Tech Haven
                                </h3>
                                <p class="text-gray-200 text-sm sm:text-base md:text-lg max-w-2xl">Immerse yourself in
                                    the cutting edge of technology and innovation.</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item next absolute top-0 left-0 w-full h-full">
                    <div class="w-full h-full p-4 sm:p-8">
                        <div class="w-full h-full rounded-xl sm:rounded-2xl overflow-hidden relative group">
                            <img src="https://www.skc.rmuti.ac.th/storage/458/conversions/67456cd838ed9_%E0%B8%9B%E0%B9%89%E0%B8%B2%E0%B8%A2%E0%B8%A3%E0%B8%B1%E0%B8%9A%E0%B8%AA%E0%B8%A1%E0%B8%B1%E0%B8%84%E0%B8%A3-%E0%B8%A2%E0%B8%B2%E0%B8%A7-6-%E0%B9%80%E0%B8%A1%E0%B8%95%E0%B8%A3-%E0%B8%AA%E0%B8%B9%E0%B8%87-2.8-%E0%B9%80%E0%B8%A1%E0%B8%95%E0%B8%A3-%E0%B8%9E%E0%B8%B1%E0%B8%9A%E0%B9%80%E0%B8%88%E0%B8%B2%E0%B8%B0-1-%E0%B8%9B%E0%B9%89%E0%B8%B2%E0%B8%A2-copy-dddd-thumb.jpg"
                                alt="Futuristic tech setup"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/40 to-pink-500/40 mix-blend-overlay">
                            </div>
                            <div
                                class="absolute inset-x-0 bottom-0 p-4 sm:p-8 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                {{-- <h3 class="text-white text-xl sm:text-2xl md:text-3xl font-bold mb-2 sm:mb-3">Tech Haven
                                </h3>
                                <p class="text-gray-200 text-sm sm:text-base md:text-lg max-w-2xl">Immerse yourself in
                                    the cutting edge of technology and innovation.</p> --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Indicators -->
            <div class="absolute bottom-2 sm:bottom-4 left-1/2 -translate-x-1/2 flex gap-1 sm:gap-2 z-20">
                <button class="w-8 sm:w-12 h-1 sm:h-1.5 rounded-full bg-white/40 hover:bg-white/60 transition-colors"
                    title="Go to slide 1"></button>
                <button class="w-8 sm:w-12 h-1 sm:h-1.5 rounded-full bg-white/20 hover:bg-white/60 transition-colors"
                    title="Go to slide 2"></button>
                <button class="w-8 sm:w-12 h-1 sm:h-1.5 rounded-full bg-white/20 hover:bg-white/60 transition-colors"
                    title="Go to slide 3"></button>
                <button class="w-8 sm:w-12 h-1 sm:h-1.5 rounded-full bg-white/20 hover:bg-white/60 transition-colors"
                    title="Go to slide 4"></button>
                <button class="w-8 sm:w-12 h-1 sm:h-1.5 rounded-full bg-white/20 hover:bg-white/60 transition-colors"
                    title="Go to slide 5"></button>
            </div>
        </div>
    </div>


</div>
