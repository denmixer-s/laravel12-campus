<!-- About Section -->
<section id="about" class="py-20 bg-gray-50" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <!-- Content Side -->
            <div class="order-2 lg:order-1" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-slideInLeft')">
                <div class="bg-white p-8 md:p-12 rounded-2xl shadow-xl relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-500 to-accent-500 opacity-10 rounded-full transform translate-x-8 -translate-y-8"></div>

                    <div class="relative z-10">
                        <!-- Section Badge -->
                        <div class="inline-flex items-center bg-primary-100 text-primary-600 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ __('เกี่ยวกับเรา') }}
                        </div>

                        <!-- Title -->
                        <h2 class="text-3xl md:text-4xl font-bold font-heading text-secondary-900 mb-6">
                            {{ __('เกี่ยวกับ') }} <span class="text-gradient-primary">{{ config('app.name', 'SAKON') }}</span>
                        </h2>

                        <!-- Description -->
                        <p class="text-secondary-600 leading-relaxed mb-6 text-lg">
                            {{ __('เราเป็นบริษัทชั้นนำด้านงานเชื่อมและงานโลหะ ที่มีประสบการณ์กว่า 15 ปี ในการให้บริการลูกค้าทั้งในและต่างประเทศ ด้วยมาตรฐานคุณภาพระดับสากล') }}
                        </p>

                        <p class="text-secondary-600 leading-relaxed mb-8">
                            {{ __('ด้วยทีมช่างผู้เชี่ยวชาญที่ผ่านการอบรมและรับรองจากสถาบันมาตรฐานสากล พร้อมด้วยเครื่องมือและเทคโนโลジีที่ทันสมัย เราพร้อมที่จะตอบสนองความต้องการของลูกค้าในทุกโครงการ') }}
                        </p>

                        <!-- Statistics Mini Grid -->
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            @php
                                $miniStats = [
                                    [
                                        'number' => '15+',
                                        'label' => __('ปีประสบการณ์'),
                                        'icon' => 'fas fa-calendar-check',
                                        'color' => 'from-primary-500 to-orange-500'
                                    ],
                                    [
                                        'number' => '500+',
                                        'label' => __('โครงการสำเร็จ'),
                                        'icon' => 'fas fa-trophy',
                                        'color' => 'from-accent-500 to-yellow-500'
                                    ]
                                ];
                            @endphp

                            @foreach($miniStats as $stat)
                                <div class="text-center p-6 bg-gradient-to-br {{ $stat['color'] }} bg-opacity-10 rounded-xl border border-primary-200 hover:bg-opacity-20 transition-all duration-300 group">
                                    <div class="w-12 h-12 bg-gradient-to-br {{ $stat['color'] }} rounded-lg flex items-center justify-center text-white mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <i class="{{ $stat['icon'] }}"></i>
                                    </div>
                                    <div class="text-2xl font-bold text-secondary-900 mb-1">{{ $stat['number'] }}</div>
                                    <div class="text-sm text-secondary-600 font-medium">{{ $stat['label'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Features List -->
                        <div class="mb-8">
                            @php
                                $features = [
                                    __('รับรองมาตรฐาน ISO 9001:2015'),
                                    __('ทีมช่างผู้เชี่ยวชาญที่ผ่านการรับรอง'),
                                    __('เครื่องมือและเทคโนโลยีที่ทันสมัย'),
                                    __('บริการหลังการขายที่ครบครัน')
                                ];
                            @endphp

                            <ul class="space-y-3">
                                @foreach($features as $index => $feature)
                                    <li class="flex items-center group" x-data="animateOnScroll()" x-intersect="setTimeout(() => $el.classList.add('animate-slideInLeft'), {{ $index * 100 }})">
                                        <div class="w-6 h-6 bg-gradient-to-br from-success-500 to-green-600 rounded-full flex items-center justify-center mr-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                        <span class="text-secondary-700 group-hover:text-secondary-900 transition-colors duration-300">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#contact" class="inline-flex items-center justify-center bg-gradient-to-r from-primary-500 to-orange-500 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
                                <i class="fas fa-phone mr-2 group-hover:animate-pulse"></i>
                                {{ __('ติดต่อเรา') }}
                            </a>
                            <a href="#services" class="inline-flex items-center justify-center border-2 border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 group">
                                <i class="fas fa-cogs mr-2 group-hover:rotate-180 transition-transform duration-500"></i>
                                {{ __('ดูบริการ') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Side -->
            <div class="order-1 lg:order-2" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-slideInRight')">
                <div class="relative">
                    <!-- Main Image -->
                    <div class="relative overflow-hidden rounded-2xl shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                             alt="{{ __('เกี่ยวกับเรา') }}"
                             class="w-full h-auto object-cover hover:scale-105 transition-transform duration-700"
                             loading="lazy">

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>

                        <!-- Floating Badge -->
                        <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-sm p-4 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-award text-white"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-secondary-900">{{ __('ISO 9001:2015') }}</div>
                                    <div class="text-sm text-secondary-600">{{ __('Certified Quality') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="absolute -top-4 -left-4 w-24 h-24 bg-gradient-to-br from-accent-500 to-yellow-500 rounded-2xl opacity-20 animate-float"></div>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-gradient-to-br from-primary-500 to-red-500 rounded-2xl opacity-20 animate-float animation-delay-1000"></div>

                    <!-- Secondary Image -->
                    <div class="absolute -bottom-8 -left-8 w-48 h-32 overflow-hidden rounded-xl shadow-xl border-4 border-white transform -rotate-6 hover:-rotate-3 transition-transform duration-500 hidden lg:block">
                        <img src="https://images.unsplash.com/photo-1565793298595-6a879b1d9492?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                             alt="{{ __('ทีมงาน') }}"
                             class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Values -->
        <div class="mt-20" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
            <div class="text-center mb-12">
                <h3 class="text-2xl md:text-3xl font-bold text-secondary-900 mb-4">
                    {{ __('ค่านิยมของเรา') }}
                </h3>
                <p class="text-secondary-600 max-w-2xl mx-auto">
                    {{ __('หลักการและค่านิยมที่ขับเคลื่อนองค์กรของเราในการให้บริการที่ดีที่สุด') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $values = [
                        [
                            'icon' => 'fas fa-shield-alt',
                            'title' => __('คุณภาพ'),
                            'description' => __('มุ่งมั่นในการส่งมอบงานที่มีคุณภาพสูงสุด'),
                            'color' => 'from-blue-500 to-cyan-500'
                        ],
                        [
                            'icon' => 'fas fa-handshake',
                            'title' => __('ความไว้วางใจ'),
                            'description' => __('สร้างความเชื่อมั่นด้วยความโปร่งใสและซื่อสัตย์'),
                            'color' => 'from-green-500 to-emerald-500'
                        ],
                        [
                            'icon' => 'fas fa-lightbulb',
                            'title' => __('นวัตกรรม'),
                            'description' => __('พัฒนาและปรับปรุงเทคนิคอย่างต่อเนื่อง'),
                            'color' => 'from-yellow-500 to-orange-500'
                        ],
                        [
                            'icon' => 'fas fa-heart',
                            'title' => __('การดูแล'),
                            'description' => __('ใส่ใจในทุกรายละเอียดและความปลอดภัย'),
                            'color' => 'from-red-500 to-pink-500'
                        ]
                    ];
                @endphp

                @foreach($values as $index => $value)
                    <div class="text-center group" x-data="animateOnScroll()" x-intersect="setTimeout(() => $el.classList.add('animate-fadeInUp'), {{ $index * 150 }})">
                        <!-- Icon Container -->
                        <div class="relative mb-6">
                            <div class="w-20 h-20 bg-gradient-to-br {{ $value['color'] }} rounded-2xl flex items-center justify-center text-white text-2xl mx-auto shadow-lg group-hover:scale-110 group-hover:rotate-12 transition-all duration-500">
                                <i class="{{ $value['icon'] }}"></i>
                            </div>
                            <!-- Glow Effect -->
                            <div class="absolute inset-0 w-20 h-20 bg-gradient-to-br {{ $value['color'] }} rounded-2xl mx-auto opacity-30 scale-150 group-hover:scale-[1.8] transition-transform duration-500"></div>
                        </div>

                        <!-- Title -->
                        <h4 class="text-xl font-bold text-secondary-900 mb-3 group-hover:text-primary-600 transition-colors duration-300">
                            {{ $value['title'] }}
                        </h4>

                        <!-- Description -->
                        <p class="text-secondary-600 text-sm leading-relaxed group-hover:text-secondary-800 transition-colors duration-300">
                            {{ $value['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Team Section -->
        <div class="mt-20" x-data="animateOnScroll()" x-intersect="$el.classList.add('animate-fadeInUp')">
            <div class="text-center mb-12">
                <h3 class="text-2xl md:text-3xl font-bold text-secondary-900 mb-4">
                    {{ __('ทีมผู้เชี่ยวชาญ') }}
                </h3>
                <p class="text-secondary-600 max-w-2xl mx-auto">
                    {{ __('พบกับทีมช่างผู้เชี่ยวชาญที่มีประสบการณ์และความเชี่ยวชาญในงานเชื่อมแต่ละประเภท') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $teamMembers = [
                        [
                            'name' => __('ช่างโอ'),
                            'position' => __('หัวหน้าช่างเชื่อม TIG'),
                            'experience' => __('12 ปีประสบการณ์'),
                            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                            'certifications' => ['AWS D1.1', 'ASME IX']
                        ],
                        [
                            'name' => __('ช่างบิน'),
                            'position' => __('ผู้เชี่ยวชาญโครงสร้าง'),
                            'experience' => __('15 ปีประสบการณ์'),
                            'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                            'certifications' => ['CWI', 'ISO 9606']
                        ],
                        [
                            'name' => __('ช่างเต๋า'),
                            'position' => __('ผู้เชี่ยวชาญการซ่อมแซม'),
                            'experience' => __('10 ปีประสบการณ์'),
                            'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                            'certifications' => ['NDT Level II', 'MIG/MAG']
                        ]
                    ];
                @endphp

                @foreach($teamMembers as $index => $member)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 group"
                         x-data="animateOnScroll()"
                         x-intersect="setTimeout(() => $el.classList.add('animate-fadeInUp'), {{ $index * 200 }})">

                        <!-- Image -->
                        <div class="relative overflow-hidden">
                            <img src="{{ $member['image'] }}"
                                 alt="{{ $member['name'] }}"
                                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-secondary-900 mb-2">{{ $member['name'] }}</h4>
                            <p class="text-primary-600 font-semibold mb-2">{{ $member['position'] }}</p>
                            <p class="text-secondary-600 text-sm mb-4">{{ $member['experience'] }}</p>

                            <!-- Certifications -->
                            <div class="flex flex-wrap gap-2">
                                @foreach($member['certifications'] as $cert)
                                    <span class="bg-primary-100 text-primary-600 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $cert }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
