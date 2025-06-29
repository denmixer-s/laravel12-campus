<x-layouts.home>

@section('title', 'Home - Innovative Digital Solutions')

@section('content')
<!-- Hero Section -->
    <section class="relative">
        <livewire:sakon.sliders.carousel-slider
            :location="'both'"
            :autoplay="true"
            :autoplay-delay="5000"
            :show-indicators="true"
            :show-navigation="true"
            :pause-on-hover="true"
        />
    </section>
<section class="relative min-h-screen flex items-center justify-center overflow-hidden gradient-bg hero-pattern">
    <!-- Background Elements -->
    {{-- <div class="absolute inset-0">
        <div class="absolute top-20 left-10 size-72 bg-white/10 rounded-full blur-3xl floating-animation"></div>
        <div class="absolute bottom-20 right-10 size-96 bg-secondary-400/20 rounded-full blur-3xl floating-animation" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 size-64 bg-primary-400/20 rounded-full blur-3xl floating-animation" style="animation-delay: 2s;"></div>
    </div> --}}



    {{-- <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="space-y-8 lg:space-y-12">
            <!-- Main Headline -->
            <div class="space-y-4 lg:space-y-6">
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold text-white leading-tight">
                    Transform Your
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-pink-300">
                        Digital Vision
                    </span>
                    Into Reality
                </h1>
                <p class="text-lg sm:text-xl lg:text-2xl text-white/90 max-w-4xl mx-auto leading-relaxed">
                    We craft exceptional web experiences that drive growth, enhance user engagement, and bring your ideas to life with cutting-edge technology and innovative design.
                </p>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 lg:gap-6 justify-center items-center">
                <a href="#services"
                   class="group px-8 lg:px-10 py-4 lg:py-5 bg-white text-gray-900 rounded-2xl font-semibold text-lg hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-3xl flex items-center space-x-3">
                    <span>Explore Our Services</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
                <a href="#portfolio"
                   class="group px-8 lg:px-10 py-4 lg:py-5 glass-effect text-white rounded-2xl font-semibold text-lg hover:bg-white/20 transform hover:scale-105 transition-all duration-300 flex items-center space-x-3">
                    <i class="fas fa-play-circle text-xl"></i>
                    <span>View Our Work</span>
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 pt-12 lg:pt-16">
                <div class="text-center space-y-2">
                    <div class="text-3xl lg:text-4xl font-bold text-white">150+</div>
                    <div class="text-white/80 text-sm lg:text-base">Projects Completed</div>
                </div>
                <div class="text-center space-y-2">
                    <div class="text-3xl lg:text-4xl font-bold text-white">98%</div>
                    <div class="text-white/80 text-sm lg:text-base">Client Satisfaction</div>
                </div>
                <div class="text-center space-y-2">
                    <div class="text-3xl lg:text-4xl font-bold text-white">5+</div>
                    <div class="text-white/80 text-sm lg:text-base">Years Experience</div>
                </div>
                <div class="text-center space-y-2">
                    <div class="text-3xl lg:text-4xl font-bold text-white">24/7</div>
                    <div class="text-white/80 text-sm lg:text-base">Support Available</div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/80 animate-bounce">
        <div class="flex flex-col items-center space-y-2">
            <span class="text-sm">Scroll to explore</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-20 lg:py-32 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center space-y-4 lg:space-y-6 mb-16 lg:mb-24">
            <span class="inline-flex items-center px-4 py-2 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">
                <i class="fas fa-cogs mr-2"></i>
                Our Services
            </span>
            <h2 class="text-3xl lg:text-5xl font-bold text-gray-900">
                Comprehensive Digital
                <span class="text-gradient">Solutions</span>
            </h2>
            <p class="text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">
                From concept to deployment, we provide end-to-end digital services that transform your business and create lasting impact.
            </p>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
            <!-- Web Development -->
            <div class="group bg-white p-8 lg:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100">
                <div class="size-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-code text-white text-xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-4">Web Development</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Custom web applications built with modern frameworks like Laravel, React, and Vue.js for scalable and robust solutions.
                </p>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Full-stack Development</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>API Integration</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Database Design</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Performance Optimization</li>
                </ul>
                <a href="/services/web-development" class="inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 transition-colors duration-200">
                    Learn More <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>

            <!-- Mobile App Development -->
            <div class="group bg-white p-8 lg:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100">
                <div class="size-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-mobile-alt text-white text-xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-4">Mobile App Development</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Native and cross-platform mobile applications that deliver exceptional user experiences across all devices.
                </p>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>iOS & Android Apps</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>React Native & Flutter</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>App Store Optimization</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Push Notifications</li>
                </ul>
                <a href="/services/mobile-development" class="inline-flex items-center text-secondary-600 font-semibold hover:text-secondary-700 transition-colors duration-200">
                    Learn More <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>

            <!-- UI/UX Design -->
            <div class="group bg-white p-8 lg:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100">
                <div class="size-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-palette text-white text-xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-4">UI/UX Design</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Intuitive and beautiful user interfaces that enhance user engagement and drive conversions through thoughtful design.
                </p>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>User Research & Testing</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Wireframing & Prototyping</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Responsive Design</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Design Systems</li>
                </ul>
                <a href="/services/ui-ux-design" class="inline-flex items-center text-pink-600 font-semibold hover:text-pink-700 transition-colors duration-200">
                    Learn More <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>

            <!-- Digital Marketing -->
            <div class="group bg-white p-8 lg:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100">
                <div class="size-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-bullhorn text-white text-xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-4">Digital Marketing</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Comprehensive digital marketing strategies that increase your online presence and drive targeted traffic to your business.
                </p>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>SEO & SEM</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Social Media Marketing</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Content Marketing</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Analytics & Reporting</li>
                </ul>
                <a href="/services/digital-marketing" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700 transition-colors duration-200">
                    Learn More <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>

            <!-- Cloud Solutions -->
            <div class="group bg-white p-8 lg:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100">
                <div class="size-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-cloud text-white text-xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-4">Cloud Solutions</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Scalable cloud infrastructure and deployment solutions that ensure your applications perform optimally at any scale.
                </p>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>AWS & Google Cloud</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>DevOps & CI/CD</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Monitoring & Security</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Migration Services</li>
                </ul>
                <a href="/services/cloud-solutions" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-700 transition-colors duration-200">
                    Learn More <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>

            <!-- Consulting -->
            <div class="group bg-white p-8 lg:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100">
                <div class="size-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-handshake text-white text-xl"></i>
                </div>
                <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-4">Technology Consulting</h3>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Strategic technology consulting to help you make informed decisions and choose the right tech stack for your projects.
                </p>
                <ul class="space-y-2 text-sm text-gray-600 mb-6">
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Technology Assessment</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Architecture Planning</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Digital Transformation</li>
                    <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Project Management</li>
                </ul>
                <a href="/services/consulting" class="inline-flex items-center text-orange-600 font-semibold hover:text-orange-700 transition-colors duration-200">
                    Learn More <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Portfolio Section -->
<section id="portfolio" class="py-20 lg:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center space-y-4 lg:space-y-6 mb-16 lg:mb-24">
            <span class="inline-flex items-center px-4 py-2 bg-secondary-100 text-secondary-800 rounded-full text-sm font-medium">
                <i class="fas fa-briefcase mr-2"></i>
                Our Portfolio
            </span>
            <h2 class="text-3xl lg:text-5xl font-bold text-gray-900">
                Recent Projects &
                <span class="text-gradient">Success Stories</span>
            </h2>
            <p class="text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">
                Discover how we've helped businesses transform their digital presence and achieve remarkable results through innovative solutions.
            </p>
        </div>

        <!-- Portfolio Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-16">
            <!-- Project 1 -->
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-500 to-secondary-600 p-8 lg:p-12 text-white hover:scale-105 transition-all duration-500">
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="size-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl lg:text-2xl font-bold">E-Commerce Platform</h3>
                            <p class="text-white/80">Fashion Retail</p>
                        </div>
                    </div>
                    <p class="text-white/90 mb-6 leading-relaxed">
                        A comprehensive e-commerce solution with advanced inventory management, payment processing, and analytics dashboard.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">Laravel</span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">Vue.js</span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">Stripe API</span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">AWS</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <div class="text-2xl font-bold">350%</div>
                            <div class="text-white/80 text-sm">Sales Increase</div>
                        </div>
                        <a href="/portfolio/ecommerce-platform" class="group inline-flex items-center px-6 py-3 bg-white text-gray-900 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200">
                            View Case Study
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>
                <div class="absolute top-0 right-0 size-32 bg-white/10 rounded-full transform translate-x-16 -translate-y-16"></div>
            </div>

            <!-- Project 2 -->
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-green-500 to-teal-600 p-8 lg:p-12 text-white hover:scale-105 transition-all duration-500">
                <div class="relative z-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="size-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-heartbeat text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl lg:text-2xl font-bold">Healthcare Portal</h3>
                            <p class="text-white/80">Medical Center</p>
                        </div>
                    </div>
                    <p class="text-white/90 mb-6 leading-relaxed">
                        Patient management system with appointment scheduling, telemedicine capabilities, and secure medical records.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">Laravel</span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">React</span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">WebRTC</span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">HIPAA</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <div class="text-2xl font-bold">85%</div>
                            <div class="text-white/80 text-sm">Efficiency Gain</div>
                        </div>
                        <a href="/portfolio/healthcare-portal" class="group inline-flex items-center px-6 py-3 bg-white text-gray-900 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200">
                            View Case Study
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 size-24 bg-white/10 rounded-full transform -translate-x-12 translate-y-12"></div>
            </div>
        </div>

        <!-- View All Projects Button -->
        <div class="text-center">
            <a href="/portfolio" class="inline-flex items-center px-8 py-4 bg-gray-900 text-white rounded-2xl font-semibold hover:bg-gray-800 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-th-large mr-3"></i>
                View All Projects
                <i class="fas fa-arrow-right ml-3"></i>
            </a>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="py-20 lg:py-32 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center space-y-4 lg:space-y-6 mb-16 lg:mb-24">
            <span class="inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                <i class="fas fa-cogs mr-2"></i>
                Our Process
            </span>
            <h2 class="text-3xl lg:text-5xl font-bold text-gray-900">
                How We Work &
                <span class="text-gradient">Deliver Excellence</span>
            </h2>
            <p class="text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">
                Our proven methodology ensures successful project delivery from initial consultation to final deployment and beyond.
            </p>
        </div>

        <!-- Process Steps -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="text-center space-y-6">
                <div class="relative">
                    <div class="size-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-lightbulb text-white text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 size-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-sm font-bold">
                        1
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Discovery & Planning</h3>
                    <p class="text-gray-600 leading-relaxed">
                        We start by understanding your business goals, target audience, and technical requirements to create a comprehensive project roadmap.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="text-center space-y-6">
                <div class="relative">
                    <div class="size-20 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-palette text-white text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 size-8 bg-secondary-100 text-secondary-600 rounded-full flex items-center justify-center text-sm font-bold">
                        2
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Design & Prototyping</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Our design team creates wireframes, mockups, and interactive prototypes to visualize the final product before development begins.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="text-center space-y-6">
                <div class="relative">
                    <div class="size-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-code text-white text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 size-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-bold">
                        3
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Development & Testing</h3>
                    <p class="text-gray-600 leading-relaxed">
                        We build your solution using modern technologies and best practices, with rigorous testing throughout the development process.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="text-center space-y-6">
                <div class="relative">
                    <div class="size-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-rocket text-white text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 size-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-bold">
                        4
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Launch & Support</h3>
                    <p class="text-gray-600 leading-relaxed">
                        We deploy your solution to production and provide ongoing support, maintenance, and optimization to ensure continued success.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 lg:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center space-y-4 lg:space-y-6 mb-16 lg:mb-24">
            <span class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                <i class="fas fa-star mr-2"></i>
                Client Testimonials
            </span>
            <h2 class="text-3xl lg:text-5xl font-bold text-gray-900">
                What Our Clients
                <span class="text-gradient">Say About Us</span>
            </h2>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-gray-50 p-8 rounded-3xl hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center space-x-1 mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <blockquote class="text-gray-700 mb-6 leading-relaxed">
                    "The team delivered an exceptional e-commerce platform that exceeded our expectations. Our sales increased by 350% within the first quarter of launch."
                </blockquote>
                <div class="flex items-center space-x-3">
                    <div class="size-12 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-full flex items-center justify-center text-white font-bold">
                        SJ
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Sarah Johnson</div>
                        <div class="text-gray-600 text-sm">CEO, Fashion Forward</div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-gray-50 p-8 rounded-3xl hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center space-x-1 mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <blockquote class="text-gray-700 mb-6 leading-relaxed">
                    "Professional, responsive, and incredibly skilled. They transformed our outdated system into a modern, efficient healthcare portal."
                </blockquote>
                <div class="flex items-center space-x-3">
                    <div class="size-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold">
                        MW
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Dr. Michael Wilson</div>
                        <div class="text-gray-600 text-sm">Director, HealthCare Plus</div>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-gray-50 p-8 rounded-3xl hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center space-x-1 mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <blockquote class="text-gray-700 mb-6 leading-relaxed">
                    "Outstanding work on our mobile app. The user experience is fantastic and our customer engagement has improved significantly."
                </blockquote>
                <div class="flex items-center space-x-3">
                    <div class="size-12 bg-gradient-to-br from-pink-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold">
                        AL
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Amanda Lee</div>
                        <div class="text-gray-600 text-sm">Product Manager, TechStart</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 lg:py-32 bg-gray-900 text-white relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 size-96 bg-primary-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 size-96 bg-secondary-500/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="space-y-8">
            <h2 class="text-3xl lg:text-5xl font-bold leading-tight">
                Ready to Transform Your
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-400">
                    Digital Presence?
                </span>
            </h2>
            <p class="text-lg lg:text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                Let's discuss your project and discover how we can help you achieve your digital goals with innovative solutions tailored to your needs.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="/contact"
                   class="group px-8 py-4 bg-gradient-to-r from-primary-600 to-secondary-600 rounded-2xl font-semibold text-lg hover:from-primary-700 hover:to-secondary-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center space-x-3">
                    <i class="fas fa-paper-plane"></i>
                    <span>Start Your Project</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
                <a href="/portfolio"
                   class="group px-8 py-4 border-2 border-white/20 text-white rounded-2xl font-semibold text-lg hover:bg-white/10 transition-all duration-300 flex items-center space-x-3">
                    <i class="fas fa-eye"></i>
                    <span>View Our Work</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);

    // Observe all sections
    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });

    // Parallax effect for hero section
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero-pattern');
        if (hero) {
            hero.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
</script>
@endpush

</x-layouts.home>
