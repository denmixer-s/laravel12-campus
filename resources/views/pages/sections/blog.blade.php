<!-- Blog Section -->
<section id="blog" class="py-20 bg-white" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-16" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
            <h2 class="text-3xl md:text-4xl font-bold font-heading text-secondary-900 mb-4">
                {{ __('บล็อกและข่าวสาร') }}
            </h2>
            <p class="text-secondary-600 max-w-2xl mx-auto leading-relaxed">
                {{ __('อัปเดตข่าวสารและความรู้ล่าสุดเกี่ยวกับงานเชื่อมและเทคโนโลยี') }}
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto mt-6 rounded-full"></div>
        </div>

        <!-- Blog Posts Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $blogPosts = [
                    [
                        'title' => __('เทคนิคการเชื่อม TIG สำหรับผู้เริ่มต้น'),
                        'excerpt' => __('การเชื่อม TIG เป็นเทคนิคที่ต้องการความละเอียดสูง เหมาะสำหรับงานที่ต้องการความสวยงาม และความแม่นยำ เรามาเรียนรู้เทคนิคพื้นฐานกัน'),
                        'image' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'category' => __('เทคนิค'),
                        'category_color' => 'bg-primary-500',
                        'date' => '2024-03-15',
                        'author' => __('ผู้ดูแล'),
                        'read_time' => '5 นาที',
                        'views' => 125,
                        'slug' => 'tig-welding-techniques-for-beginners'
                    ],
                    [
                        'title' => __('มาตรฐานความปลอดภัยในงานเชื่อม'),
                        'excerpt' => __('ความปลอดภัยในการทำงานเป็นสิ่งสำคัญที่สุด เรียนรู้มาตรฐานและแนวทางปฏิบัติ ที่จะช่วยป้องกันอุบัติเหตุในการทำงาน'),
                        'image' => 'https://images.unsplash.com/photo-1565793298595-6a879b1d9492?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'category' => __('ความปลอดภัย'),
                        'category_color' => 'bg-accent-500',
                        'date' => '2024-03-12',
                        'author' => __('ช่างโอ'),
                        'read_time' => '7 นาที',
                        'views' => 89,
                        'slug' => 'welding-safety-standards'
                    ],
                    [
                        'title' => __('การเลือกอุปกรณ์เชื่อมที่เหมาะสม'),
                        'excerpt' => __('การเลือกอุปกรณ์เชื่อมที่เหมาะสมจะส่งผลต่อคุณภาพของงาน เรียนรู้วิธีการเลือกอุปกรณ์ให้เหมาะกับงานแต่ละประเภท'),
                        'image' => 'https://images.unsplash.com/photo-1558618663-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'category' => __('อุปกรณ์'),
                        'category_color' => 'bg-secondary-500',
                        'date' => '2024-03-10',
                        'author' => __('ช่างบิน'),
                        'read_time' => '6 นาที',
                        'views' => 156,
                        'slug' => 'choosing-right-welding-equipment'
                    ],
                    [
                        'title' => __('เทรนด์เทคโนโลยีการเชื่อมในปี 2024'),
                        'excerpt' => __('เทคโนโลยีการเชื่อมก้าวหน้าอย่างรวดเร็ว เรียนรู้เทรนด์และนวัตกรรมใหม่ที่จะเปลี่ยนแปลงอุตสาหกรรมในปีนี้'),
                        'image' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'category' => __('เทคโนโลยี'),
                        'category_color' => 'bg-green-500',
                        'date' => '2024-03-08',
                        'author' => __('ทีมวิจัย'),
                        'read_time' => '8 นาที',
                        'views' => 203,
                        'slug' => 'welding-technology-trends-2024'
                    ],
                    [
                        'title' => __('การบำรุงรักษาอุปกรณ์เชื่อม'),
                        'excerpt' => __('การบำรุงรักษาอุปกรณ์อย่างถูกต้องจะช่วยให้อุปกรณ์มีอายุการใช้งานที่ยาวนาน และทำงานได้อย่างมีประสิทธิภาพ'),
                        'image' => 'https://images.unsplash.com/photo-1572981779307-38b8cabb2407?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'category' => __('บำรุงรักษา'),
                        'category_color' => 'bg-blue-500',
                        'date' => '2024-03-05',
                        'author' => __('ช่างเต๋า'),
                        'read_time' => '4 นาที',
                        'views' => 78,
                        'slug' => 'welding-equipment-maintenance'
                    ],
                    [
                        'title' => __('คุณภาพงานเชื่อมตามมาตรฐาน ISO'),
                        'excerpt' => __('มาตรฐาน ISO เป็นหลักประกันคุณภาพที่สำคัญ เรียนรู้วิธีการควบคุมคุณภาพและการตรวจสอบงานเชื่อม'),
                        'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                        'category' => __('มาตรฐาน'),
                        'category_color' => 'bg-purple-500',
                        'date' => '2024-03-01',
                        'author' => __('ผู้เชี่ยวชาญ'),
                        'read_time' => '10 นาที',
                        'views' => 92,
                        'slug' => 'iso-welding-quality-standards'
                    ]
                ];
            @endphp

            @foreach($blogPosts as $index => $post)
                <article class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 group"
                         x-data="animateOnScroll()"
                         x-intersect="setTimeout(() => $el.classList.add('animate-fadeInUp'), {{ $index * 150 }})">

                    <!-- Post Image -->
                    <div class="relative overflow-hidden">
                        <img src="{{ $post['image'] }}"
                             alt="{{ $post['title'] }}"
                             class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                             loading="lazy">

                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="{{ $post['category_color'] }} text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                {{ $post['category'] }}
                            </span>
                        </div>

                        <!-- Reading Time -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-black/70 text-white px-2 py-1 rounded text-xs backdrop-blur-sm">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $post['read_time'] }}
                            </span>
                        </div>

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <!-- Post Content -->
                    <div class="p-6">
                        <!-- Meta Information -->
                        <div class="flex items-center text-sm text-secondary-500 mb-3 space-x-4">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-primary-500"></i>
                                <span>{{ \Carbon\Carbon::parse($post['date'])->locale('th')->isoFormat('D MMM YYYY') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-primary-500"></i>
                                <span>{{ $post['author'] }}</span>
                            </div>
                        </div>

                        <!-- Post Title -->
                        <h3 class="text-xl font-bold text-secondary-900 mb-3 group-hover:text-primary-600 transition-colors duration-300 leading-tight">
                            <a href="{{ route('blog.show', $post['slug']) }}" class="hover:underline">
                                {{ $post['title'] }}
                            </a>
                        </h3>

                        <!-- Post Excerpt -->
                        <p class="text-secondary-600 mb-4 line-clamp-3 leading-relaxed">
                            {{ $post['excerpt'] }}
                        </p>

                        <!-- Post Footer -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('blog.show', $post['slug']) }}"
                               class="text-primary-500 hover:text-primary-600 font-semibold text-sm flex items-center group-hover:translate-x-1 transition-transform duration-300">
                                {{ __('อ่านต่อ') }}
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                            </a>
                            <div class="flex items-center text-sm text-secondary-500">
                                <i class="fas fa-eye mr-1"></i>
                                <span>{{ number_format($post['views']) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hover Effect Border -->
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-primary-500 to-accent-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                </article>
            @endforeach
        </div>

        <!-- Blog Categories Filter -->
        <div class="mt-16" x-data="blogFilter()" x-intersect="$el.classList.add('animate-fadeInUp')">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-secondary-900 mb-4">
                    {{ __('หมวดหมู่บทความ') }}
                </h3>
                <p class="text-secondary-600">
                    {{ __('เลือกหมวดหมู่ที่คุณสนใจ') }}
                </p>
            </div>

            <!-- Category Filters -->
            <div class="flex flex-wrap justify-center gap-3 mb-8">
                @php
                    $categories = [
                        ['name' => __('ทั้งหมด'), 'slug' => 'all', 'color' => 'bg-secondary-500'],
                        ['name' => __('เทคนิค'), 'slug' => 'technique', 'color' => 'bg-primary-500'],
                        ['name' => __('ความปลอดภัย'), 'slug' => 'safety', 'color' => 'bg-accent-500'],
                        ['name' => __('อุปกรณ์'), 'slug' => 'equipment', 'color' => 'bg-gray-500'],
                        ['name' => __('เทคโนโลยี'), 'slug' => 'technology', 'color' => 'bg-green-500'],
                        ['name' => __('มาตรฐาน'), 'slug' => 'standards', 'color' => 'bg-purple-500']
                    ];
                @endphp

                @foreach($categories as $category)
                    <button @click="filterCategory('{{ $category['slug'] }}')"
                            :class="activeCategory === '{{ $category['slug'] }}' ? '{{ $category['color'] }} text-white' : 'bg-gray-200 text-secondary-700 hover:{{ $category['color'] }} hover:text-white'"
                            class="px-6 py-2 rounded-full font-semibold transition-all duration-300 transform hover:scale-105">
                        {{ $category['name'] }}
                    </button>
                @endforeach
            </div>

            <!-- Popular Tags -->
            <div class="text-center">
                <h4 class="text-lg font-semibold text-secondary-900 mb-4">{{ __('แท็กยอดนิยม') }}</h4>
                <div class="flex flex-wrap justify-center gap-2">
                    @php
                        $popularTags = [
                            'TIG', 'MIG', 'เหล็กโครงสร้าง', 'อลูมิเนียม', 'สแตนเลส',
                            'ความปลอดภัย', 'ISO', 'AWS', 'NDT', 'อุปกรณ์ป้องกัน'
                        ];
                    @endphp

                    @foreach($popularTags as $tag)
                        <a href="{{ route('blog.tag', Str::slug($tag)) }}"
                           class="text-sm px-3 py-1 bg-gray-100 hover:bg-primary-100 text-secondary-600 hover:text-primary-600 rounded-full transition-colors duration-300">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Newsletter Subscription -->
        <div class="mt-16 bg-gradient-to-r from-primary-500 to-accent-500 rounded-2xl p-8 md:p-12 text-white" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
            <div class="text-center">
                <h3 class="text-2xl md:text-3xl font-bold mb-4">
                    {{ __('รับข่าวสารและเทคนิคใหม่ๆ') }}
                </h3>
                <p class="text-lg mb-8 text-white/90 max-w-2xl mx-auto">
                    {{ __('สมัครรับข่าวสารเพื่อได้รับบทความและเทคนิคการเชื่อมใหม่ๆ ส่งตรงถึงอีเมลของคุณ') }}
                </p>

                <form class="max-w-md mx-auto flex gap-3" action="{{ route('newsletter.subscribe') }}" method="POST" x-data="newsletterForm()">
                    @csrf
                    <div class="flex-1">
                        <input type="email"
                               name="email"
                               x-model="email"
                               placeholder="{{ __('กรอกอีเมลของคุณ') }}"
                               class="w-full px-4 py-3 rounded-lg text-secondary-900 placeholder-secondary-500 focus:outline-none focus:ring-2 focus:ring-white/50"
                               required>
                    </div>
                    <button type="submit"
                            :disabled="loading"
                            class="bg-white hover:bg-gray-100 text-primary-600 px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center">
                        <span x-show="!loading">{{ __('สมัคร') }}</span>
                        <span x-show="loading" class="flex items-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            {{ __('กำลังส่ง...') }}
                        </span>
                    </button>
                </form>

                <p class="text-white/70 text-sm mt-4">
                    {{ __('เราจะไม่ส่งอีเมลสแปม และคุณสามารถยกเลิกการสมัครได้ตลอดเวลา') }}
                </p>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-16" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
            <div class="bg-gray-50 rounded-2xl p-8 md:p-12">
                <h3 class="text-2xl md:text-3xl font-bold text-secondary-900 mb-4">
                    {{ __('ต้องการคำปรึกษาเฉพาะด้าน?') }}
                </h3>
                <p class="text-secondary-600 mb-8 max-w-2xl mx-auto">
                    {{ __('ทีมผู้เชี่ยวชาญของเราพร้อมให้คำปรึกษาเทคนิคและแนวทางการแก้ไขปัญหาเฉพาะกับโครงการของคุณ') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#contact" class="inline-flex items-center justify-center bg-primary-500 hover:bg-primary-600 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-phone mr-2"></i>
                        {{ __('ปรึกษาฟรี') }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center justify-center border-2 border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300">
                        <i class="fas fa-book mr-2"></i>
                        {{ __('ดูบล็อกทั้งหมด') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function blogFilter() {
    return {
        activeCategory: 'all',

        filterCategory(category) {
            this.activeCategory = category;

            // In a real implementation, this would filter the posts
            // For now, we'll just update the active state
            console.log('Filtering by category:', category);

            // You could implement AJAX filtering here or redirect to a filtered page
            // window.location.href = `/blog?category=${category}`;
        }
    }
}

function newsletterForm() {
    return {
        email: '',
        loading: false,

        async submitForm(event) {
            event.preventDefault();

            if (!this.email) return;

            this.loading = true;

            try {
                const formData = new FormData();
                formData.append('email', this.email);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content);

                const response = await fetch(event.target.action, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    this.showNotification('{{ __("สมัครสมาชิกสำเร็จแล้ว!") }}', 'success');
                    this.email = '';
                } else {
                    this.showNotification(result.message || '{{ __("เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง") }}', 'error');
                }
            } catch (error) {
                this.showNotification('{{ __("เกิดข้อผิดพลาดในการเชื่อมต่อ") }}', 'error');
            } finally {
                this.loading = false;
            }
        },

        showNotification(message, type) {
            // Create notification
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-success-500 text-white' : 'bg-danger-500 text-white'
            }`;

            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto hide
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
    }
}

// Initialize blog interactions
document.addEventListener('DOMContentLoaded', function() {
    // Handle newsletter form submissions
    document.querySelectorAll('form[action*="newsletter"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            // This will be handled by Alpine.js newsletterForm()
        });
    });

    // Handle blog post hover effects
    document.querySelectorAll('article').forEach(article => {
        article.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });

        article.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Lazy load blog images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('opacity-0');
                        img.classList.add('opacity-100');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Add reading progress for blog posts
    if (window.location.pathname.includes('/blog/')) {
        const progressBar = document.createElement('div');
        progressBar.className = 'fixed top-0 left-0 w-0 h-1 bg-primary-500 z-50 transition-all duration-300';
        document.body.appendChild(progressBar);

        window.addEventListener('scroll', () => {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight - windowHeight;
            const scrolled = window.scrollY;
            const progress = (scrolled / documentHeight) * 100;

            progressBar.style.width = `${Math.min(progress, 100)}%`;
        });
    }
});
</script>
@endpush
