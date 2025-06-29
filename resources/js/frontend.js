/**
 * Frontend JS for SAKON Welding Services
 * ใช้กับ Livewire Components สำหรับลูกค้า
 * Alpine.js มาจาก Livewire
 * Updated with Performance Optimizations
 */

import axios from 'axios';

// Configure Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Setup CSRF token when DOM ready
document.addEventListener('DOMContentLoaded', function() {
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }
});

// Global state for preventing translation conflicts
let isTranslating = false;
let translationTimeout = null;

// ใช้ Alpine.js จาก Livewire สำหรับ Frontend Components
document.addEventListener('livewire:init', () => {
    console.log('🔥 SAKON Frontend พร้อมใช้งาน! (Livewire Components)');

    // Navigation Component (unchanged)
    Alpine.data('navigation', () => ({
        scrolled: false,
        mobileMenuOpen: false,

        init() {
            this.updateNavigation();
            window.addEventListener('scroll', () => this.updateNavigation());
            window.addEventListener('resize', () => this.handleResize());
        },

        updateNavigation() {
            this.scrolled = window.scrollY > 20;
        },

        handleResize() {
            if (window.innerWidth >= 1024) {
                this.mobileMenuOpen = false;
                document.body.style.overflow = '';
            }
        },

        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
            document.body.style.overflow = this.mobileMenuOpen ? 'hidden' : '';
        },

        closeMobileMenu() {
            this.mobileMenuOpen = false;
            document.body.style.overflow = '';
        }
    }));

    // ⚡ PERFORMANCE OPTIMIZED Page Translation Component
    Alpine.data('pageTranslator', () => ({
        currentLang: localStorage.getItem('selectedLanguage') || 'th',
        translatedCache: new Map(),
        isProcessing: false,
        translationProgress: 0,
        batchSize: 8, // ลดจาก 20 → 8 สำหรับความเร็ว
        maxConcurrentBatches: 2, // ทำ 2 batches พร้อมกัน

        init() {
            console.log('🌍 PageTranslator initialized with language:', this.currentLang);
            this.markTranslatableElements();
            this.applyCachedTranslations(this.currentLang);
        },

        // 🎯 OPTIMIZED: เลือกเฉพาะ elements สำคัญ
        markTranslatableElements() {
            const prioritySelectors = [
                'nav a:not(.no-translate):not(.notranslate)',     // เมนู navigation
                'h1:not(.no-translate):not(.notranslate)',        // หัวข้อหลัก
                'h2:not(.no-translate):not(.notranslate)',        // หัวข้อรอง
                'h3:not(.no-translate):not(.notranslate)',        // หัวข้อย่อย
                '.btn:not(.no-translate):not(.notranslate)',      // ปุ่ม
                'button:not(.no-translate):not(.notranslate)',    // ปุ่มทั่วไป
                '.card-title:not(.no-translate):not(.notranslate)', // หัวข้อการ์ด
                '.menu-item:not(.no-translate):not(.notranslate)', // รายการเมนู
                '.breadcrumb:not(.no-translate):not(.notranslate)', // breadcrumb
                '.badge:not(.no-translate):not(.notranslate)',    // ป้ายกำกับ
                '.alert:not(.no-translate):not(.notranslate)',    // ข้อความแจ้งเตือน
                'label:not(.no-translate):not(.notranslate)',     // labels
                '.translatable'                                   // elements ที่มี class พิเศษ
            ];

            let elementCount = 0;
            prioritySelectors.forEach(selector => {
                document.querySelectorAll(selector).forEach(element => {
                    if (this.shouldTranslate(element)) {
                        element.setAttribute('data-translate', 'true');
                        if (!element.getAttribute('data-original')) {
                            element.setAttribute('data-original', element.textContent.trim());
                        }
                        elementCount++;
                    }
                });
            });

            console.log(`📝 Marked ${elementCount} priority elements for translation`);
            return elementCount;
        },

        shouldTranslate(element) {
            const text = element.textContent.trim();

            // Basic checks
            if (!text || text.length < 2 || text.length > 200) return false; // จำกัดความยาว

            // Skip elements with translation-blocking classes
            if (element.classList.contains('no-translate') ||
                element.classList.contains('notranslate') ||
                element.hasAttribute('translate') && element.getAttribute('translate') === 'no') {
                return false;
            }

            // Skip if parent has no-translate
            if (element.closest('.no-translate') || element.closest('.notranslate')) {
                return false;
            }

            // Skip script, style, input elements
            const tagName = element.tagName.toLowerCase();
            if (['script', 'style', 'input', 'textarea', 'select', 'code', 'pre'].includes(tagName)) {
                return false;
            }

            // Skip pure numbers, symbols, dates
            if (/^\d+$/.test(text) ||
                /^[!@#$%^&*(),.?":{}|<>]+$/.test(text) ||
                /^\d{1,2}\/\d{1,2}\/\d{4}$/.test(text) ||
                /^\d{4}-\d{2}-\d{2}$/.test(text)) {
                return false;
            }

            // 🎯 OPTIMIZED: Skip elements with complex nested HTML
            if (element.children.length > 2) {
                return false; // ข้าม elements ที่ซับซ้อน
            }

            return true;
        },

        async translatePage(targetLang) {
            // Prevent multiple concurrent translations
            if (this.isProcessing || isTranslating) {
                console.log('🚫 Translation already in progress, skipping...');
                return;
            }

            this.isProcessing = true;
            isTranslating = true;
            this.translationProgress = 0;

            console.log('🌍 Starting translation to:', targetLang);

            try {
                if (targetLang === 'th') {
                    console.log('🔄 Restoring to Thai (original text)');
                    this.restoreOriginalText();
                    this.currentLang = 'th';
                    localStorage.setItem('selectedLanguage', 'th');
                    document.documentElement.lang = 'th';
                    window.showNotification('เปลี่ยนเป็นภาษาไทยแล้ว', 'success');
                } else {
                    await this.performTranslation(targetLang);
                }

            } catch (error) {
                console.error('❌ Translation failed:', error);
                this.handleTranslationError(error);
            } finally {
                this.isProcessing = false;
                isTranslating = false;
                this.translationProgress = 0;
            }
        },

        // 🚀 OPTIMIZED: การแปลแบบ concurrent processing
        async performTranslation(targetLang) {
            const elementsToTranslate = document.querySelectorAll('[data-translate="true"]');
            const textsToTranslate = [];
            const elements = [];

            // Collect texts that need translation + กรองข้อความซ้ำ
            const uniqueTexts = new Set();
            elementsToTranslate.forEach(element => {
                const originalText = element.getAttribute('data-original');
                if (originalText && !uniqueTexts.has(originalText)) {
                    const cacheKey = `${originalText}-${targetLang}`;
                    if (!this.translatedCache.has(cacheKey)) {
                        uniqueTexts.add(originalText);
                        textsToTranslate.push(originalText);
                        elements.push(element);
                    }
                }
            });

            console.log(`🔄 Reduced from ${elementsToTranslate.length} to ${textsToTranslate.length} unique texts`);

            if (textsToTranslate.length === 0) {
                console.log('✅ All content already cached, applying translations...');
                this.applyCachedTranslations(targetLang);
                this.updateLanguageState(targetLang);
                window.showNotification(`เปลี่ยนเป็น${targetLang === 'en' ? 'ภาษาอังกฤษ' : 'ภาษาอื่น'}แล้ว`, 'success');
                return;
            }

            // 📊 Show progress notification
            this.showProgressNotification(textsToTranslate.length);

            // Split into smaller batches
            const batches = this.createBatches(textsToTranslate, this.batchSize);
            console.log(`📦 Created ${batches.length} batches (max ${this.batchSize} items each)`);

            const allTranslations = new Map();
            let completedBatches = 0;

            // 🚀 Process batches concurrently
            await this.processBatchesConcurrently(batches, targetLang, allTranslations, (completed) => {
                completedBatches = completed;
                this.translationProgress = Math.round((completed / batches.length) * 100);
                this.updateProgressNotification(completed, batches.length);
            });

            // Check results
            if (allTranslations.size === 0) {
                throw new Error('การแปลภาษาล้มเหลว ไม่สามารถแปลได้เลย');
            }

            // Apply all translations
            this.applyTranslationsFromMap(allTranslations, targetLang);
            this.applyCachedTranslations(targetLang);
            this.updateLanguageState(targetLang);

            console.log(`✅ Successfully translated ${allTranslations.size}/${textsToTranslate.length} texts`);
            this.hideProgressNotification();
        },

        // 🚀 NEW: Concurrent batch processing
        async processBatchesConcurrently(batches, targetLang, allTranslations, progressCallback) {
            const concurrentLimit = this.maxConcurrentBatches;
            let completedBatches = 0;

            // แบ่งงานเป็นกลุม ๆ ตาม concurrent limit
            for (let i = 0; i < batches.length; i += concurrentLimit) {
                const currentBatches = batches.slice(i, i + concurrentLimit);

                // ประมวลผล batches พร้อมกัน
                const promises = currentBatches.map(async (batch, index) => {
                    const batchIndex = i + index + 1;
                    console.log(`📦 Processing batch ${batchIndex}/${batches.length} (${batch.length} items)`);

                    try {
                        const response = await this.callTranslateAPI(batch, targetLang);

                        if (response.success && response.translations) {
                            // เก็บผลลัพธ์
                            batch.forEach((text, textIndex) => {
                                if (response.translations[textIndex]) {
                                    allTranslations.set(text, response.translations[textIndex]);
                                    this.translatedCache.set(`${text}-${targetLang}`, response.translations[textIndex]);
                                }
                            });

                            console.log(`✅ Batch ${batchIndex} completed: ${response.translations.length} translations`);
                            return true;
                        } else {
                            throw new Error(response.message || 'Invalid API response');
                        }

                    } catch (error) {
                        console.error(`❌ Batch ${batchIndex} failed:`, error);
                        return false;
                    }
                });

                // รอให้ batches ในกลุ่มนี้เสร็จ
                await Promise.allSettled(promises);
                completedBatches += currentBatches.length;
                progressCallback(completedBatches);

                // หน่วงเวลาเล็กน้อยระหว่างกลุ่ม
                if (i + concurrentLimit < batches.length) {
                    await new Promise(resolve => setTimeout(resolve, 300));
                }
            }
        },

        createBatches(texts, batchSize) {
            const batches = [];
            for (let i = 0; i < texts.length; i += batchSize) {
                batches.push(texts.slice(i, i + batchSize));
            }
            return batches;
        },

        // 📊 Progress notifications
        showProgressNotification(totalTexts) {
            const estimatedTime = Math.ceil(totalTexts / this.batchSize) * 3;
            window.showNotification(
                `กำลังแปลภาษา... ${totalTexts} รายการ (ประมาณ ${estimatedTime} วินาที)`,
                'info',
                estimatedTime * 1000
            );
        },

        updateProgressNotification(completed, total) {
            const percentage = Math.round((completed / total) * 100);
            window.showNotification(
                `แปลภาษา... ${percentage}% (${completed}/${total} ชุด)`,
                'info',
                3000
            );
        },

        hideProgressNotification() {
            window.showNotification('✅ แปลภาษาเสร็จสิ้น!', 'success', 2000);
        },

        handleTranslationError(error) {
            let errorMessage = 'การแปลภาษาล้มเหลว กรุณาลองใหม่อีกครั้ง';

            if (error.message.includes('quota') || error.message.includes('เกิน')) {
                errorMessage = error.message;
            } else if (error.message.includes('CSRF')) {
                errorMessage = 'กรุณารีเฟรชหน้าเว็บแล้วลองใหม่';
            }

            window.showNotification(errorMessage, 'error');
        },

        // 🔧 UPDATED: API call with better error handling
        async callTranslateAPI(texts, targetLang) {
            // ดึง CSRF token แบบ fallback
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            if (!csrfToken) {
                csrfToken = window.livewire?.token || window.Livewire?.first()?.token;
            }

            if (!csrfToken) {
                const cookies = document.cookie.split(';');
                const xsrfCookie = cookies.find(c => c.trim().startsWith('XSRF-TOKEN='));
                if (xsrfCookie) {
                    csrfToken = decodeURIComponent(xsrfCookie.split('=')[1]);
                }
            }

            if (!csrfToken) {
                throw new Error('CSRF token not found. Please refresh the page.');
            }

            console.log('🌐 Calling translation API:', {
                count: texts.length,
                target: targetLang,
                sample: texts[0]?.substring(0, 30) + '...'
            });

            const requestBody = {
                texts: texts,
                target_language: targetLang,
                source_language: 'th'
            };

            // ✅ ใช้ route ที่ถูกต้อง
            const response = await fetch('/api/translate-batch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(requestBody),
                timeout: 15000 // เพิ่ม timeout
            });

            console.log(`📡 Response status: ${response.status}`);

            if (!response.ok) {
                let errorData;
                try {
                    errorData = await response.json();
                } catch (e) {
                    const errorText = await response.text();
                    throw new Error(`Server error (${response.status}): ${errorText.substring(0, 200)}`);
                }

                if (response.status === 422) {
                    const errors = errorData.errors ?
                        Object.entries(errorData.errors).map(([field, messages]) =>
                            `${field}: ${messages.join(', ')}`).join('\n') :
                        errorData.message;
                    throw new Error(`Validation error: ${errors}`);
                } else if (response.status === 429) {
                    throw new Error(`เกินโควต้าการแปลแล้ว กรุณาลองใหม่ภายหลัง`);
                } else if (response.status === 419) {
                    throw new Error('CSRF Token หมดอายุ กรุณารีเฟรชหน้าเว็บ');
                } else {
                    throw new Error(errorData?.message || `Translation API error: ${response.status}`);
                }
            }

            const result = await response.json();
            console.log('✅ API Response received:', {
                success: result.success,
                translationsCount: result.translations?.length || Object.keys(result.translations || {}).length
            });

            if (!result.success) {
                throw new Error(result.message || 'Translation API returned failure');
            }

            return result;
        },

        // Helper methods
        applyTranslationsFromMap(translationsMap, targetLang) {
            const elementsToTranslate = document.querySelectorAll('[data-translate="true"]');
            let appliedCount = 0;

            elementsToTranslate.forEach(element => {
                const originalText = element.getAttribute('data-original');
                if (originalText && translationsMap.has(originalText)) {
                    element.textContent = translationsMap.get(originalText);
                    appliedCount++;
                }
            });

            console.log(`🔧 Applied ${appliedCount} fresh translations`);
        },

        applyCachedTranslations(targetLang) {
            if (targetLang === 'th') {
                this.restoreOriginalText();
                return;
            }

            let appliedCount = 0;
            document.querySelectorAll('[data-translate="true"]').forEach(element => {
                const originalText = element.getAttribute('data-original');
                if (originalText) {
                    const cacheKey = `${originalText}-${targetLang}`;
                    if (this.translatedCache.has(cacheKey)) {
                        element.textContent = this.translatedCache.get(cacheKey);
                        appliedCount++;
                    }
                }
            });

            console.log(`✅ Applied ${appliedCount} cached translations`);
        },

        restoreOriginalText() {
            document.querySelectorAll('[data-translate="true"]').forEach(element => {
                const originalText = element.getAttribute('data-original');
                if (originalText) {
                    element.textContent = originalText;
                }
            });
        },

        updateLanguageState(targetLang) {
            this.currentLang = targetLang;
            localStorage.setItem('selectedLanguage', targetLang);
            document.documentElement.lang = targetLang;

            const langName = targetLang === 'en' ? 'ภาษาอังกฤษ' : 'ภาษาอื่น';
            window.showNotification(`แปลเป็น${langName}สำเร็จ!`, 'success');
        },

        // Public method for external calls
        setLanguage(lang) {
            return this.translatePage(lang);
        }
    }));

    // Statistics Counter Component (unchanged)
    Alpine.data('statisticsCounter', () => ({
        counters: [],

        init() {
            this.counters = Array.from(this.$el.querySelectorAll('.stats-counter'));
            this.setupCounterAnimation();
        },

        setupCounterAnimation() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateCounters();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(this.$el);
        },

        animateCounters() {
            this.counters.forEach((counter, index) => {
                const target = parseInt(counter.dataset.target);
                const suffix = counter.dataset.suffix || '';

                setTimeout(() => {
                    this.animateCounter(target, suffix, counter);
                }, index * 200);
            });
        },

        animateCounter(target, suffix, element) {
            const duration = 2000;
            const start = performance.now();

            const updateCounter = (currentTime) => {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                const easedProgress = this.easeOutCubic(progress);
                const currentValue = Math.floor(target * easedProgress);

                element.textContent = currentValue + suffix;

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target + suffix;
                }
            };

            requestAnimationFrame(updateCounter);
        },

        easeOutCubic(t) {
            return 1 - Math.pow(1 - t, 3);
        }
    }));

    // Contact Form Component (unchanged)
    Alpine.data('contactForm', () => ({
        formData: {
            name: '',
            email: '',
            phone: '',
            subject: '',
            message: ''
        },
        loading: false,
        errors: {},

        async submitForm() {
            this.loading = true;
            this.errors = {};

            try {
                const response = await window.axios.post('/api/contact', this.formData);

                if (response.data.success) {
                    this.showNotification('ส่งข้อความสำเร็จแล้ว! เราจะติดต่อกลับไปเร็วๆ นี้', 'success');
                    this.resetForm();
                }
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    this.showNotification('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง', 'error');
                }
            } finally {
                this.loading = false;
            }
        },

        resetForm() {
            this.formData = {
                name: '',
                email: '',
                phone: '',
                subject: '',
                message: ''
            };
        },

        showNotification(message, type) {
            window.showNotification?.(message, type);
        }
    }));

    // Animate on Scroll Component (unchanged)
    Alpine.data('animateOnScroll', (options = {}) => ({
        isVisible: false,
        hasAnimated: false,

        init() {
            const defaultOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px',
                once: true
            };

            const config = { ...defaultOptions, ...options };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && (!this.hasAnimated || !config.once)) {
                        this.isVisible = true;
                        this.hasAnimated = true;

                        if (config.once) {
                            observer.unobserve(this.$el);
                        }
                    } else if (!config.once) {
                        this.isVisible = false;
                    }
                });
            }, config);

            observer.observe(this.$el);
        }
    }));

    // Global Alpine Store (unchanged)
    Alpine.store('app', {
        loading: false,
        theme: localStorage.getItem('theme') || 'light',
        language: document.documentElement.lang || 'th',

        setLoading(state) {
            this.loading = state;
        },

        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        },

        setLanguage(lang) {
            this.language = lang;
            document.documentElement.lang = lang;
        },

        init() {
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
            document.documentElement.lang = this.language;
        }
    });

    // Initialize Alpine store
    Alpine.store('app').init();

    // 🚀 OPTIMIZED Global Translator
    window.pageTranslator = {
        setLanguage: (lang) => {
            console.log('🌍 Global translator called for language:', lang);

            // Clear any existing timeouts
            if (translationTimeout) {
                clearTimeout(translationTimeout);
            }

            // Debounce translation calls
            translationTimeout = setTimeout(() => {
                const translatorElements = document.querySelectorAll('[x-data*="pageTranslator"]');
                if (translatorElements.length > 0) {
                    const translatorEl = translatorElements[0];
                    if (translatorEl._x_dataStack && translatorEl._x_dataStack[0]) {
                        translatorEl._x_dataStack[0].translatePage(lang);
                    }
                } else {
                    console.warn('No pageTranslator elements found');
                }
            }, 100);
        },

        translatePage: (lang) => {
            console.log('🌍 Direct translatePage called for language:', lang);

            const translatorElements = document.querySelectorAll('[x-data*="pageTranslator"]');
            if (translatorElements.length > 0) {
                const translatorEl = translatorElements[0];
                if (translatorEl._x_dataStack && translatorEl._x_dataStack[0]) {
                    translatorEl._x_dataStack[0].translatePage(lang);
                } else {
                    console.error('PageTranslator instance not found');
                }
            } else {
                console.error('No pageTranslator elements found');
            }
        }
    };

    // Livewire Event Handlers
    Livewire.on('notify', (data) => {
        if (data.message) {
            window.showNotification(data.message, data.type || 'info');
        }
    });

    // 🚀 OPTIMIZED Language change handler
    Livewire.on('language-changed', (data) => {
        console.log('📡 Language change event received:', data);

        if (isTranslating) {
            console.log('🚫 Translation in progress, ignoring language change event');
            return;
        }

        // Handle both array and object formats
        let locale, from;
        if (Array.isArray(data) && data.length > 0) {
            locale = data[0].locale || data[0].language;
            from = data[0].from;
        } else if (typeof data === 'object') {
            locale = data.locale || data.language;
            from = data.from;
        }

        console.log('📝 Extracted data:', { locale, from });

        if (locale && window.pageTranslator && typeof window.pageTranslator.setLanguage === 'function') {
            window.pageTranslator.setLanguage(locale);
        } else {
            console.error('❌ Invalid language data or pageTranslator not available', { locale, data });
        }
    });
});

// Improved Global Notification Function (unchanged)
window.showNotification = function(message, type = 'info', duration = null) {
    // Remove existing notifications of the same type
    document.querySelectorAll(`.notification-${type}`).forEach(n => n.remove());

    const notification = document.createElement('div');
    notification.className = `notification-${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;

    const icon = type === 'success' ? 'fa-check-circle' :
                 type === 'error' ? 'fa-exclamation-circle' :
                 type === 'warning' ? 'fa-exclamation-triangle' :
                 'fa-info-circle';

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${icon} mr-2 flex-shrink-0"></i>
            <span class="text-sm">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 hover:opacity-70 flex-shrink-0">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto hide based on type or custom duration
    const hideDelay = duration || (type === 'error' ? 8000 : type === 'warning' ? 6000 : 4000);
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, hideDelay);
};

// Livewire Navigation Events
document.addEventListener('livewire:init', () => {
    Livewire.on('navigating', () => {
        Alpine.store('app').setLoading(true);
    });

    Livewire.on('navigated', () => {
        Alpine.store('app').setLoading(false);

        // Re-mark translatable elements after navigation
        setTimeout(() => {
            const translatorElements = document.querySelectorAll('[x-data*="pageTranslator"]');
            translatorElements.forEach(el => {
                if (el._x_dataStack && el._x_dataStack[0] && el._x_dataStack[0].markTranslatableElements) {
                    el._x_dataStack[0].markTranslatableElements();
                }
            });
        }, 100);
    });
});

// DOM ready handlers
document.addEventListener('DOMContentLoaded', () => {
    // Setup smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
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

    // Handle external links
    document.querySelectorAll('a[target="_blank"]').forEach(link => {
        if (!link.rel.includes('noopener')) {
            link.rel += ' noopener noreferrer';
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Close any open dropdowns or modals
            document.querySelectorAll('[x-show]').forEach(element => {
                if (element.style.display !== 'none') {
                    element.dispatchEvent(new CustomEvent('close'));
                }
            });
        }
    });

    // Image lazy loading
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('opacity-0');
                    img.classList.add('opacity-100');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }
});

// Console welcome message
console.log(`
🔥 SAKON Welding Services
Built with Laravel 12 + Livewire 3.6 + Alpine.js + Tailwind CSS 4.1
Translation System: Enhanced & Optimized with Performance Boost
Performance optimized and ready for production!
`);

export default {};
