/**
 * Bootstrap JS for SAKON
 * รองรับ Livewire และใช้ Alpine.js จาก Livewire
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

// Console branding
console.log(`
🔥 SAKON Welding Services
Built with Laravel 12 + Livewire 3.6 + Alpine.js + Tailwind CSS 4.1
Performance optimized and ready for production!
`);

// ไม่ต้อง export Alpine เพราะมาจาก Livewire แล้ว
export default {};