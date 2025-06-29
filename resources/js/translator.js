class PageTranslator {
    constructor() {
        this.currentLang = localStorage.getItem('selectedLanguage') || 'th';
        this.translatedCache = new Map();
        this.init();
    }

    init() {
        this.setupLanguageSelector();
        this.markTranslatableElements();
        this.setupCustomLanguageSelector(); // สำหรับ custom dropdown
        this.markTranslatableElements();
        // ลบการ loadSavedLanguage ออกก่อน เพื่อป้องกัน error
        // this.loadSavedLanguage();
    }

    setupLanguageSelector() {
        const selector = document.getElementById('languageSelector');
        if (selector) {
            selector.value = this.currentLang;
            selector.addEventListener('change', (e) => {
                this.translatePage(e.target.value);
            });
        }
    }

    setupCustomLanguageSelector() {
        const dropdown = document.getElementById('languageDropdown');
        const options = document.getElementById('languageOptions');
        const currentFlag = document.getElementById('currentFlag');
        const currentLang = document.getElementById('currentLang');

        if (!dropdown) return; // ถ้าใช้ select ธรรมดา

        // Set initial state
        this.updateCurrentLanguage(this.currentLang);

        // Toggle dropdown
        dropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('active');
            options.classList.toggle('show');
        });

        // Handle option selection
        document.querySelectorAll('.dropdown-option').forEach(option => {
            option.addEventListener('click', (e) => {
                const lang = option.dataset.lang;
                this.updateCurrentLanguage(lang);
                this.translatePage(lang);

                // Close dropdown
                dropdown.classList.remove('active');
                options.classList.remove('show');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            dropdown.classList.remove('active');
            options.classList.remove('show');
        });
    }

    updateCurrentLanguage(lang) {
        const currentFlag = document.getElementById('currentFlag');
        const currentLang = document.getElementById('currentLang');
        const options = document.querySelectorAll('.dropdown-option');

        if (!currentFlag || !currentLang) return;

        // Update current display
        if (lang === 'th') {
            currentFlag.className = 'flag-icon flag-th';
            currentLang.textContent = 'ไทย';
        } else if (lang === 'en') {
            currentFlag.className = 'flag-icon flag-en';
            currentLang.textContent = 'English';
        }

        // Update active states
        options.forEach(option => {
            option.classList.toggle('active', option.dataset.lang === lang);
        });
    }

    markTranslatableElements() {
        const selectors = [
            'h1, h2, h3, h4, h5, h6',
            'p:not(.no-translate)',
            'span:not(.no-translate)',
            'button:not(.no-translate)',
            'label:not(.no-translate)',
            '.translatable'
        ];

        selectors.forEach(selector => {
            document.querySelectorAll(selector).forEach(element => {
                if (this.shouldTranslate(element)) {
                    element.setAttribute('data-translate', 'true');
                    element.setAttribute('data-original', element.textContent.trim());
                }
            });
        });
    }

    shouldTranslate(element) {
        const text = element.textContent.trim();

        if (!text || text.length < 2) return false;
        if (element.classList.contains('no-translate')) return false;
        if (element.closest('.no-translate')) return false;
        if (element.closest('script')) return false;
        if (element.closest('style')) return false;
        if (/^\d+$/.test(text)) return false;
        if (/^[!@#$%^&*(),.?":{}|<>]+$/.test(text)) return false;
        if (text.length < 5) return false; // ข้อความสั้นๆ
        if (/^[A-Z\s]+$/.test(text)) return false; // ตัวพิมพ์ใหญ่ทั้งหมด
        if (/\d{4}/.test(text)) return false; // มีปี

        return true;
    }

    async translatePage(targetLang) {
        console.log('Translating to:', targetLang);

        if (targetLang === 'th') {
            this.restoreOriginalText();
            this.currentLang = targetLang;
            localStorage.setItem('selectedLanguage', targetLang);
            return;
        }

        this.showLoadingIndicator();

        try {
            const elementsToTranslate = document.querySelectorAll('[data-translate="true"]');
            const textsToTranslate = [];
            const elements = [];

            elementsToTranslate.forEach(element => {
                const originalText = element.getAttribute('data-original');
                if (originalText && originalText.trim()) {
                    const cacheKey = `${originalText}-${targetLang}`;
                    if (!this.translatedCache.has(cacheKey)) {
                        textsToTranslate.push(originalText);
                        elements.push(element);
                    }
                }
            });

            if (textsToTranslate.length > 0) {
                console.log(`Total texts to translate: ${textsToTranslate.length}`);

                // แบ่ง batch เป็น 30 รายการต่อครั้ง (ลดลงจาก 50)
                const batchSize = 30;
                const allTranslations = [];
                let processedElements = [];
                let successfulBatches = 0;

                for (let i = 0; i < textsToTranslate.length; i += batchSize) {
                    const batch = textsToTranslate.slice(i, i + batchSize);
                    const batchElements = elements.slice(i, i + batchSize);
                    const batchNumber = Math.floor(i / batchSize) + 1;
                    const totalBatches = Math.ceil(textsToTranslate.length / batchSize);

                    console.log(`Processing batch ${batchNumber}/${totalBatches} (${batch.length} items)`);
                    this.updateLoadingProgress(batchNumber, totalBatches);

                    try {
                        const result = await this.batchTranslate(batch, targetLang);

                        if (result.success && result.translations) {
                            allTranslations.push(...result.translations);
                            processedElements.push(...batchElements);
                            successfulBatches++;
                            console.log(`Batch ${batchNumber} successful`);
                        } else {
                            console.warn(`Batch ${batchNumber} failed: ${result.message || 'Unknown error'}`);
                            break; // หยุดเมื่อ batch ล้มเหลว
                        }
                    } catch (error) {
                        console.warn(`Batch ${batchNumber} error:`, error.message);
                        if (error.message.includes('quota')) {
                            this.showError(`แปลได้เพียงบางส่วน (${successfulBatches}/${totalBatches} กลุ่ม) เนื่องจากเกิน quota วันนี้`);
                        }
                        break; // หยุดเมื่อเกิด error
                    }

                    // หน่วงเวลาระหว่าง batch
                    await new Promise(resolve => setTimeout(resolve, 500));
                }

                if (allTranslations.length > 0) {
                    console.log(`Applying ${allTranslations.length} translations to ${processedElements.length} elements`);
                    this.applyTranslations(processedElements, allTranslations, targetLang);

                    if (successfulBatches < Math.ceil(textsToTranslate.length / batchSize)) {
                        this.showError(`แปลเสร็จบางส่วน (${successfulBatches}/${Math.ceil(textsToTranslate.length / batchSize)} กลุ่ม)`);
                    }
                }
            }

            this.applyCachedTranslations(targetLang);

            this.currentLang = targetLang;
            localStorage.setItem('selectedLanguage', targetLang);

        } catch (error) {
            console.error('Translation failed:', error);
            this.showError('การแปลภาษาล้มเหลว กรุณาลองใหม่อีกครั้ง');
        } finally {
            this.hideLoadingIndicator();
        }
    }

    async batchTranslate(texts, targetLang) {
        console.log('Sending request to API...');

        const response = await fetch('/api/translate-batch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                texts: texts,
                target_language: targetLang,
                source_language: 'th'
            })
        });

        console.log('Response status:', response.status);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('API Error Response:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const responseText = await response.text();
            console.error('Non-JSON Response:', responseText);
            throw new Error('Server returned non-JSON response');
        }

        return await response.json();
    }

    applyTranslations(elements, translations, targetLang) {
        elements.forEach((element, index) => {
            if (translations[index]) {
                const originalText = element.getAttribute('data-original');
                const translatedText = translations[index];

                element.textContent = translatedText;
                this.translatedCache.set(`${originalText}-${targetLang}`, translatedText);

                console.log(`Translated: "${originalText}" -> "${translatedText}"`);
            }
        });
    }

    applyCachedTranslations(targetLang) {
        document.querySelectorAll('[data-translate="true"]').forEach(element => {
            const originalText = element.getAttribute('data-original');
            const cacheKey = `${originalText}-${targetLang}`;

            if (this.translatedCache.has(cacheKey)) {
                element.textContent = this.translatedCache.get(cacheKey);
            }
        });
    }

    restoreOriginalText() {
        document.querySelectorAll('[data-translate="true"]').forEach(element => {
            const originalText = element.getAttribute('data-original');
            if (originalText) {
                element.textContent = originalText;
            }
        });
    }

    showLoadingIndicator() {
        this.hideLoadingIndicator(); // ลบ loader เก่าก่อน

        const loader = document.createElement('div');
        loader.id = 'translation-loader';
        loader.innerHTML = `
        <div class="translation-loading">
            <div class="spinner"></div>
            <span id="loading-text">กำลังแปลภาษา...</span>
            <div id="progress-text" style="font-size: 12px; margin-top: 10px;"></div>
        </div>
    `;
        document.body.appendChild(loader);
    }

    updateLoadingProgress(current, total) {
        const progressText = document.getElementById('progress-text');
        if (progressText) {
            progressText.textContent = `กำลังประมวลผล ${current}/${total} กลุ่ม`;
        }
    }

    hideLoadingIndicator() {
        const loader = document.getElementById('translation-loader');
        if (loader) loader.remove();
    }

    showError(message) {
        alert(message);
        this.hideLoadingIndicator();
    }

}

document.addEventListener('DOMContentLoaded', () => {
    console.log('Page Translator initialized');
    window.pageTranslator = new PageTranslator();
});