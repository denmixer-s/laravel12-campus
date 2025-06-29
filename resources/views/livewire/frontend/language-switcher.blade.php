{{-- resources/views/livewire/frontend/language-switcher.blade.php --}}
<div
    x-data="{
        open: @entangle('isOpen'),
        translating: @entangle('translating')
    }"
    x-init="
        $watch('open', value => {
            if (value) {
                $nextTick(() => {
                    document.addEventListener('click', (e) => {
                        if (!$el.contains(e.target)) {
                            open = false;
                        }
                    }, { once: true });
                });
            }
        });
    "
    class="language-switcher-custom relative no-translate notranslate"
    translate="no"
>
    <div class="dropdown-container">
        <!-- Dropdown Trigger Button -->
        <button
            wire:click="toggle"
            @click.stop
            :disabled="translating"
            class="dropdown-trigger flex items-center space-x-2 px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
        >
            <!-- Current Flag -->
            <span class="flag-icon {{ $this->getCurrentFlag() }}"></span>

            <!-- Current Language -->
            <span class="text-sm font-medium">{{ $this->getCurrentName() }}</span>

            <!-- Loading Spinner หรือ Arrow -->
            <div class="flex items-center">
                <svg
                    x-show="!translating"
                    class="dropdown-arrow w-3 h-3 transition-transform duration-200"
                    :class="{ 'rotate-180': open }"
                    viewBox="0 0 12 8"
                >
                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="1.5" fill="none"/>
                </svg>

                <svg
                    x-show="translating"
                    class="animate-spin w-3 h-3"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </button>

        <!-- Dropdown Options -->
        <div
            x-show="open && !translating"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="dropdown-options absolute top-full right-0 mt-2 w-full min-w-32 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
            @click.away="open = false"
        >
            @foreach($languages as $code => $language)
                <button
                    wire:click="switchLanguage('{{ $code }}')"
                    class="dropdown-option w-full flex items-center space-x-2 px-3 py-2 text-left hover:bg-gray-50 first:rounded-t-lg last:rounded-b-lg transition-colors duration-150 {{ $currentLang === $code ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}"
                >
                    <span class="flag-icon {{ $language['flag'] }}"></span>
                    <span class="text-sm font-medium">{{ $language['name'] }}</span>

                    @if($currentLang === $code)
                        <svg class="w-4 h-4 ml-auto text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- JavaScript สำหรับจัดการ Translation --}}
    <script>
        document.addEventListener('livewire:init', () => {
            // ฟัง event การเปลี่ยนภาษา
            Livewire.on('language-changed', (data) => {
                console.log('Language changed:', data);

                // Handle both array and object formats
                let locale;
                if (Array.isArray(data) && data.length > 0) {
                    locale = data[0].locale || data[0].language;
                } else if (typeof data === 'object') {
                    locale = data.locale || data.language;
                } else {
                    console.error('Invalid language change data:', data);
                    return;
                }

                // เรียกใช้ PageTranslator ถ้ามี
                if (window.pageTranslator && typeof window.pageTranslator.translatePage === 'function') {
                    window.pageTranslator.translatePage(locale);
                } else {
                    console.error('PageTranslator not available');
                }
            });

            // ฟัง notification events
            Livewire.on('notify', (data) => {
                let message, type;
                if (Array.isArray(data) && data.length > 0) {
                    message = data[0].message;
                    type = data[0].type;
                } else if (typeof data === 'object') {
                    message = data.message;
                    type = data.type;
                }

                if (message) {
                    showNotification(message, type);
                }
            });
        });

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' :
                           type === 'error' ? 'bg-red-500' : 'bg-blue-500';

            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full opacity-0`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // แสดง notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
            }, 100);

            // ซ่อน notification หลัง 3 วินาที
            setTimeout(() => {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>

    {{-- CSS Styles --}}
    <style>
        .flag-icon {
            width: 20px;
            height: 15px;
            border-radius: 2px;
            background-size: cover;
            display: inline-block;
            flex-shrink: 0;
        }

        .flag-th {
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><rect fill="%23ED1C24" width="900" height="600"/><rect fill="%23fff" y="100" width="900" height="100"/><rect fill="%23241D4F" y="200" width="900" height="200"/><rect fill="%23fff" y="400" width="900" height="100"/></svg>');
        }

        .flag-en {
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23012169" width="1200" height="600"/><path d="M0 0L1200 600M1200 0L0 600" stroke="%23fff" stroke-width="120"/><path d="M0 0L1200 600M1200 0L0 600" stroke="%23C8102E" stroke-width="80"/><path d="M600 0V600M0 300H1200" stroke="%23fff" stroke-width="200"/><path d="M600 0V600M0 300H1200" stroke="%23C8102E" stroke-width="120"/></svg>');
        }

        .dropdown-trigger:hover {
            background-color: #f9fafb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .dropdown-option:hover {
            background-color: #f3f4f6;
        }

        .dropdown-arrow {
            transition: transform 0.2s ease-in-out;
        }

        /* Animation สำหรับ dropdown */
        .dropdown-options {
            animation: slideDown 0.2s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Loading state */
        .dropdown-trigger:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .language-switcher-custom {
                position: relative;
            }

            .dropdown-options {
                left: 0;
                right: 0;
                width: auto;
                min-width: auto;
            }
        }
    </style>
</div>
