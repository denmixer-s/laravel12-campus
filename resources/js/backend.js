import './bootstrap';

// Custom JavaScript for the application
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('[data-flash-message]');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 500);
        }, 5000);
    });
});

// Livewire hooks และ Alpine.js setup
document.addEventListener('livewire:init', () => {
    console.log('🔥 SAKON Backend พร้อมใช้งาน! (Livewire + Alpine.js)');
    
    // Global Livewire event listeners
    Livewire.on('notify', (event) => {
        console.log('Notification:', event);
        if (window.showNotification) {
            window.showNotification(event.message, event.type || 'info');
        }
    });

    // Livewire navigation events
    Livewire.on('navigating', () => {
        console.log('กำลังเปลี่ยนหน้า...');
        // แสดง loading indicator
    });

    Livewire.on('navigated', () => {
        console.log('เปลี่ยนหน้าเสร็จแล้ว');
        // ซ่อน loading indicator
    });
});

// Backend-specific Alpine components
document.addEventListener('livewire:init', () => {
    // Admin Dashboard Stats
    Alpine.data('dashboardStats', () => ({
        loading: false,
        stats: {},
        
        async loadStats() {
            this.loading = true;
            try {
                const response = await axios.get('/api/admin/stats');
                this.stats = response.data;
            } catch (error) {
                console.error('Error loading stats:', error);
            } finally {
                this.loading = false;
            }
        },

        init() {
            this.loadStats();
        }
    }));

    // Data Table Component
    Alpine.data('dataTable', () => ({
        search: '',
        sortBy: 'id',
        sortDirection: 'desc',
        perPage: 10,
        currentPage: 1,
        
        sort(column) {
            if (this.sortBy === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDirection = 'asc';
            }
            this.currentPage = 1;
        },

        getSortIcon(column) {
            if (this.sortBy !== column) return 'fas fa-sort text-gray-400';
            return this.sortDirection === 'asc' 
                ? 'fas fa-sort-up text-blue-500' 
                : 'fas fa-sort-down text-blue-500';
        }
    }));

    // Modal Component
    Alpine.data('modal', () => ({
        open: false,
        
        show() {
            this.open = true;
            document.body.style.overflow = 'hidden';
        },

        hide() {
            this.open = false;
            document.body.style.overflow = '';
        },

        init() {
            // ปิด modal เมื่อกด ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.open) {
                    this.hide();
                }
            });
        }
    }));

    // Sidebar Component
    Alpine.data('sidebar', () => ({
        open: localStorage.getItem('sidebarOpen') === 'true',
        collapsed: localStorage.getItem('sidebarCollapsed') === 'true',

        toggle() {
            this.open = !this.open;
            localStorage.setItem('sidebarOpen', this.open);
        },

        collapse() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebarCollapsed', this.collapsed);
        }
    }));

    // Notification Component
    Alpine.data('notifications', () => ({
        items: [],
        
        add(message, type = 'info', duration = 5000) {
            const id = Date.now();
            this.items.push({ id, message, type });
            
            if (duration > 0) {
                setTimeout(() => this.remove(id), duration);
            }
        },

        remove(id) {
            const index = this.items.findIndex(item => item.id === id);
            if (index > -1) {
                this.items.splice(index, 1);
            }
        },

        clear() {
            this.items = [];
        }
    }));

    // Form Validation Component
    Alpine.data('formValidator', () => ({
        errors: {},
        touched: {},

        setErrors(errors) {
            this.errors = errors;
        },

        clearErrors() {
            this.errors = {};
        },

        markTouched(field) {
            this.touched[field] = true;
        },

        hasError(field) {
            return this.errors[field] && this.touched[field];
        },

        getError(field) {
            return this.errors[field] ? this.errors[field][0] : '';
        }
    }));
});

// Global utility functions สำหรับ backend
window.confirmDelete = function(message = 'คุณต้องการลบข้อมูลนี้หรือไม่?') {
    return confirm(message);
};

window.showNotification = function(message, type = 'info') {
    // ใช้ Alpine store หรือ component
    const event = new CustomEvent('notification', {
        detail: { message, type }
    });
    document.dispatchEvent(event);
};

// Export for global use (ไม่ต้อง export Alpine เพราะมาจาก Livewire แล้ว)
export default {};