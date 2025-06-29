<x-layouts.sakon>

@section('title', __('หน้าแรก - ผู้เชี่ยวชาญด้านงานเชื่อม'))
@section('description', __('บริการงานเชื่อมทุกประเภท ด้วยมาตรฐานสากลและประสบการณ์กว่า 15 ปี
    พร้อมทีมช่างผู้เชี่ยวชาญและเทคโนโลยีที่ทันสมัย'))
@section('keywords', __('งานเชื่อม, เชื่อม TIG, เชื่อม MIG, โครงสร้างเหล็ก, ซ่อมแซม, ตัดโลหะ, SAKON'))

@section('content')
    <!-- Hero Section -->
    @include('pages.sections.hero')

    <!-- About Section -->
    @include('pages.sections.about')

    <!-- Services Section -->
    @include('pages.sections.services')

    <!-- Statistics Section -->
    @include('pages.sections.statistics')

    {{-- <!-- Portfolio Section -->
    @include('pages.sections.portfolio') --}}

    {{-- <!-- Blog Section -->
    @include('pages.sections.blog') --}}

    {{-- <!-- Contact Section -->
    @include('pages.sections.contact') --}}
@endsection

@push('styles')
    <style>
        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), url('{{ asset('images/hero-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        @media (max-width: 768px) {
            .hero-bg {
                background-attachment: scroll;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Portfolio Filter
            const portfolioFilters = document.querySelectorAll('.portfolio-filter');
            const portfolioItems = document.querySelectorAll('.portfolio-item');

            portfolioFilters.forEach(filter => {
                filter.addEventListener('click', () => {
                    // Remove active class from all filters
                    portfolioFilters.forEach(f => {
                        f.classList.remove('active', 'bg-primary', 'text-white');
                        f.classList.add('bg-gray-200', 'text-gray-700');
                    });

                    // Add active class to clicked filter
                    filter.classList.add('active', 'bg-primary', 'text-white');
                    filter.classList.remove('bg-gray-200', 'text-gray-700');

                    // Filter portfolio items
                    const filterValue = filter.dataset.filter;
                    portfolioItems.forEach(item => {
                        if (filterValue === 'all' || item.dataset.category ===
                            filterValue) {
                            item.style.display = 'block';
                            item.classList.add('animate-fadeIn');
                        } else {
                            item.style.display = 'none';
                            item.classList.remove('animate-fadeIn');
                        }
                    });
                });
            });

            // Statistics counter animation
            const animateCounters = () => {
                const counters = document.querySelectorAll('.stats-counter');
                const speed = 200;

                counters.forEach(counter => {
                    const target = parseInt(counter.dataset.target);
                    const increment = target / speed;
                    let current = 0;

                    const updateCounter = () => {
                        if (current < target) {
                            current += increment;
                            counter.textContent = Math.ceil(current) + (counter.dataset.suffix ||
                                '');
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target + (counter.dataset.suffix || '');
                        }
                    };

                    updateCounter();
                });
            };

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (entry.target.classList.contains('stats-section')) {
                            animateCounters();
                            observer.unobserve(entry.target);
                        }
                    }
                });
            }, observerOptions);

            // Observe stats section
            const statsSection = document.querySelector('.stats-section');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });
    </script>
@endpush


</x-layouts.sakon>
