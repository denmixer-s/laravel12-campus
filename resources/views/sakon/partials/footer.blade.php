<!-- Footer -->
<footer class="bg-secondary-900 text-white py-12 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0 bg-repeat" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><path d="M30 30l-4-4v-8l4-4 4 4v8l-4 4z"/></g></svg>')"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Company Info -->
            <div class="lg:col-span-1">
                <div class="flex items-center mb-6">
                    <div class="text-2xl font-bold text-primary-500">
                        <i class="fas fa-tools mr-2"></i>
                        {{ config('app.name', 'SAKON') }}
                    </div>
                </div>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    {{ __('ผู้เชี่ยวชาญด้านงานเชื่อมและงานโลหะ ด้วยประสบการณ์กว่า 15 ปี พร้อมให้บริการด้วยมาตรฐานระดับสากล') }}
                </p>

                <!-- Social Media -->
                <div class="flex space-x-4">
                    @php
                        $socialLinks = [
                            ['icon' => 'fab fa-facebook-f', 'url' => '#', 'label' => 'Facebook'],
                            ['icon' => 'fab fa-instagram', 'url' => '#', 'label' => 'Instagram'],
                            ['icon' => 'fab fa-line', 'url' => '#', 'label' => 'Line'],
                            ['icon' => 'fab fa-youtube', 'url' => '#', 'label' => 'YouTube']
                        ];
                    @endphp

                    @foreach($socialLinks as $social)
                        <a href="{{ $social['url'] }}"
                           class="w-10 h-10 bg-gray-800 hover:bg-primary-500 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:-translate-y-1 group"
                           aria-label="{{ $social['label'] }}"
                           target="_blank"
                           rel="noopener noreferrer">
                            <i class="{{ $social['icon'] }} group-hover:scale-110 transition-transform duration-300"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white relative">
                    {{ __('เมนูหลัก') }}
                    <span class="absolute bottom-0 left-0 w-8 h-0.5 bg-primary-500"></span>
                </h3>
                <ul class="space-y-3">
                    @php
                        $quickLinks = [
                            ['text' => __('หน้าแรก'), 'url' => '#home'],
                            ['text' => __('เกี่ยวกับเรา'), 'url' => '#about'],
                            ['text' => __('บริการ'), 'url' => '#services'],
                            ['text' => __('ผลงาน'), 'url' => '#portfolio'],
                            ['text' => __('บล็อก'), 'url' => '#blog'],
                            ['text' => __('ติดต่อเรา'), 'url' => '#contact']
                        ];
                    @endphp

                    @foreach($quickLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}"
                               class="text-gray-400 hover:text-primary-500 transition-colors duration-300 flex items-center group">
                                <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform duration-300 text-primary-500"></i>
                                {{ $link['text'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white relative">
                    {{ __('บริการของเรา') }}
                    <span class="absolute bottom-0 left-0 w-8 h-0.5 bg-primary-500"></span>
                </h3>
                <ul class="space-y-3">
                    @php
                        $serviceLinks = [
                            ['text' => __('งานเชื่อม MIG/MAG'), 'url' => '#services'],
                            ['text' => __('งานเชื่อม TIG'), 'url' => '#services'],
                            ['text' => __('งานโครงสร้าง'), 'url' => '#services'],
                            ['text' => __('งานซ่อมแซม'), 'url' => '#services'],
                            ['text' => __('งานตัดโลหะ'), 'url' => '#services'],
                            ['text' => __('ตรวจสอบคุณภาพ'), 'url' => '#services']
                        ];
                    @endphp

                    @foreach($serviceLinks as $service)
                        <li>
                            <a href="{{ $service['url'] }}"
                               class="text-gray-400 hover:text-primary-500 transition-colors duration-300 flex items-center group">
                                <i class="fas fa-chevron-right text-xs mr-2 group-hover:translate-x-1 transition-transform duration-300 text-primary-500"></i>
                                {{ $service['text'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-6 text-white relative">
                    {{ __('ติดต่อเรา') }}
                    <span class="absolute bottom-0 left-0 w-8 h-0.5 bg-primary-500"></span>
                </h3>
                <div class="space-y-4">
                    @php
                        $contactInfo = [
                            [
                                'icon' => 'fas fa-map-marker-alt',
                                'title' => __('ที่อยู่'),
                                'content' => __('123 ถนนสุขุมวิท แขวงคลองตัน<br>เขตวัฒนา กรุงเทพฯ 10110')
                            ],
                            [
                                'icon' => 'fas fa-phone',
                                'title' => __('โทรศัพท์'),
                                'content' => '02-123-4567<br>085-123-4567'
                            ],
                            [
                                'icon' => 'fas fa-envelope',
                                'title' => __('อีเมล'),
                                'content' => 'info@sakonwelding.com<br>contact@sakonwelding.com'
                            ],
                            [
                                'icon' => 'fas fa-clock',
                                'title' => __('เวลาทำการ'),
                                'content' => __('จ-ศ: 08:00-17:00<br>เสาร์: 08:00-12:00')
                            ]
                        ];
                    @endphp

                    @foreach($contactInfo as $info)
                        <div class="flex items-start space-x-3 group">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary-500/20 rounded-lg flex items-center justify-center group-hover:bg-primary-500/30 transition-colors duration-300">
                                <i class="{{ $info['icon'] }} text-primary-500 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-white text-sm mb-1">{{ $info['title'] }}</div>
                                <div class="text-gray-400 text-sm leading-relaxed">{!! $info['content'] !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Newsletter Subscription -->
        <div class="mt-12 pt-8 border-t border-gray-800">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-xl font-bold text-white mb-2">
                        {{ __('รับข่าวสารและข้อเสนอพิเศษ') }}
                    </h3>
                    <p class="text-gray-400">
                        {{ __('สมัครสมาชิกเพื่อรับข้อมูลข่าวสารและโปรโมชันพิเศษ') }}
                    </p>
                </div>
                <div>
                    <form class="flex gap-2" action="#" method="POST">
                        @csrf
                        <div class="flex-1">
                            <input type="email"
                                   name="email"
                                   placeholder="{{ __('กรอกอีเมลของคุณ') }}"
                                   class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400 transition-colors duration-200"
                                   required>
                        </div>
                        <button type="submit"
                                class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            {{ __('สมัคร') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Certifications -->
        <div class="mt-12 pt-8 border-t border-gray-800">
            <div class="text-center">
                <h4 class="text-lg font-bold text-white mb-6">{{ __('การรับรองมาตรฐาน') }}</h4>
                <div class="flex justify-center items-center space-x-8 opacity-60">
                    @php
                        $certifications = [
                            ['name' => 'ISO 9001', 'image' => 'iso-9001.png'],
                            ['name' => 'AWS', 'image' => 'aws-cert.png'],
                            ['name' => 'ASME', 'image' => 'asme-cert.png'],
                            ['name' => 'TIS', 'image' => 'tis-cert.png']
                        ];
                    @endphp

                    @foreach($certifications as $cert)
                        <div class="flex flex-col items-center group">
                            <div class="w-16 h-16 bg-gray-800 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors duration-300">
                                <!-- Replace with actual certification logos -->
                                <span class="text-xs text-center text-gray-300 font-semibold">{{ $cert['name'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="mt-12 pt-8 border-t border-gray-800">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-center md:text-left">
                    <p class="text-gray-400 text-sm">
                        © {{ date('Y') }} {{ config('app.name', 'SAKON') }} {{ __('Welding Services') }}. {{ __('สงวนลิขสิทธิ์ทั้งหมด') }}.
                    </p>
                </div>

                <!-- Legal Links -->
                <div class="flex space-x-6 text-sm">
                    @php
                        $legalLinks = [
                            ['text' => __('นโยบายความเป็นส่วนตัว'), 'url' => route('privacy')],
                            ['text' => __('ข้อกำหนดการใช้งาน'), 'url' => route('terms')],
                            ['text' => __('คุกกี้'), 'url' => route('cookies')]
                        ];
                    @endphp

                    @foreach($legalLinks as $legal)
                        <a href="{{ $legal['url'] }}"
                           class="text-gray-400 hover:text-primary-500 transition-colors duration-300">
                            {{ $legal['text'] }}
                        </a>
                        @if(!$loop->last)
                            <span class="text-gray-600">|</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Back to Top Button Integration -->
    <div class="hidden">
        <!-- This is handled by the main layout -->
    </div>
</footer>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Newsletter form handling
    const newsletterForm = document.querySelector('form[action*="newsletter"]');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            try {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + '{{ __("กำลังส่ง...") }}';

                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    // Show success message
                    showNotification('{{ __("สมัครสมาชิกสำเร็จแล้ว!") }}', 'success');
                    this.reset();
                } else {
                    showNotification(result.message || '{{ __("เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง") }}', 'error');
                }
            } catch (error) {
                showNotification('{{ __("เกิดข้อผิดพลาดในการเชื่อมต่อ") }}', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    }

    // Smooth scroll for footer links
    document.querySelectorAll('footer a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerHeight = document.querySelector('header')?.offsetHeight || 0;
                const targetPosition = target.offsetTop - headerHeight - 20;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add external link indicators
    document.querySelectorAll('footer a[target="_blank"]').forEach(link => {
        if (!link.querySelector('.external-icon')) {
            const icon = document.createElement('i');
            icon.className = 'fas fa-external-link-alt text-xs ml-1 external-icon';
            link.appendChild(icon);
        }
    });
});

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-success-500 text-white' :
        type === 'error' ? 'bg-danger-500 text-white' :
        'bg-primary-500 text-white'
    }`;

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
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

    // Auto hide notification
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>
@endpush
