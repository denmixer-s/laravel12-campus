<!-- Header/Navigation -->
<header class="bg-white/90 backdrop-blur-sm shadow-lg fixed w-full top-0 z-50 transition-all duration-300">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="text-2xl font-bold text-primary hover:text-orange-600 transition-colors duration-300">
                        <i class="fas fa-tools mr-2"></i>
                        {{ config('app.name', 'SAKON') }}
                    </div>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8">
                <livewire:sakon.menus.frontend-menu location="header" variant="default" :show-icons="false"
                    :show-breadcrumbs="false" :cache-enabled="true" :css-classes="[
                        'nav' => 'flex items-center space-x-8',
                        'link' =>
                            'text-gray-700 hover:text-primary transition duration-300 font-medium relative after:absolute after:w-0 after:h-0.5 after:bg-primary after:left-0 after:-bottom-1 after:transition-all after:duration-300 hover:after:w-full',
                        'active' => 'text-primary after:w-full',
                    ]" />
            </div>

            <!-- Right Side - Language Switcher & Mobile Menu -->
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="language-switcher-custom relative">
                    <div class="dropdown-container">
                        <button id="languageDropdown"
                            class="dropdown-trigger flex items-center space-x-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-300 group">
                            <span id="currentFlag" class="flag-icon flag-{{ app()->getLocale() }}"></span>
                            <span id="currentLang" class="text-sm font-medium">
                                {{ app()->getLocale() == 'th' ? 'ไทย' : 'English' }}
                            </span>
                            <svg class="dropdown-arrow w-3 h-3 transform transition-transform group-hover:rotate-180"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        {{-- <div id="languageOptions"
                            class="dropdown-options absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg min-w-full z-50 opacity-0 invisible transform -translate-y-2 transition-all duration-300">
                            @foreach (['th' => 'ไทย', 'en' => 'English'] as $locale => $name)
                                <a href="{{ route('language.switch', $locale) }}"
                                    class="dropdown-option flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer transition-colors duration-200 {{ app()->getLocale() == $locale ? 'bg-primary/10 text-primary' : '' }}"
                                    data-lang="{{ $locale }}">
                                    <span class="flag-icon flag-{{ $locale }}"></span>
                                    <span class="text-sm">{{ $name }}</span>
                                    @if (app()->getLocale() == $locale)
                                        <i class="fas fa-check ml-auto text-primary text-xs"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div> --}}
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <div class="lg:hidden">
                    <button id="mobileMenuToggle"
                        class="text-gray-700 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded-lg p-2 transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="sr-only">{{ __('Toggle mobile menu') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="lg:hidden hidden mt-4 pb-4 border-t border-gray-200 animate-fadeIn">
            <div class="flex flex-col space-y-4 pt-4">
                <livewire:sakon.menus.frontend-menu location="mobile" :css-classes="[
                    'nav' => 'flex flex-col space-y-4',
                    'link' =>
                        'text-gray-700 hover:text-primary transition duration-300 py-2 border-l-4 border-transparent hover:border-primary hover:pl-2',
                    'active' => 'text-primary border-primary pl-2',
                ]" />
            </div>
        </div>
    </nav>
</header>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Language Switcher
            const languageDropdown = document.getElementById('languageDropdown');
            const languageOptions = document.getElementById('languageOptions');
            const languageSwitcher = document.querySelector('.language-switcher-custom');

            if (languageDropdown && languageOptions) {
                languageDropdown.addEventListener('click', (e) => {
                    e.stopPropagation();
                    languageSwitcher.classList.toggle('active');

                    if (languageSwitcher.classList.contains('active')) {
                        languageOptions.classList.remove('opacity-0', 'invisible', '-translate-y-2');
                        languageOptions.classList.add('opacity-100', 'visible', 'translate-y-0');
                    } else {
                        languageOptions.classList.add('opacity-0', 'invisible', '-translate-y-2');
                        languageOptions.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.language-switcher-custom')) {
                        languageSwitcher.classList.remove('active');
                        languageOptions.classList.add('opacity-0', 'invisible', '-translate-y-2');
                        languageOptions.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    }
                });
            }
        });
    </script>
@endpush
