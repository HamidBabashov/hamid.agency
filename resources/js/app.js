import Alpine from 'alpinejs';

// Qaranlıq rejim: localStorage-da yadda saxlanılır
Alpine.store('theme', {
    dark: document.documentElement.classList.contains('dark'),
    toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        try {
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        } catch (e) {
            /* storage əlçatan deyil */
        }
    },
});

// Hosting qiymət cədvəli üçün aylıq/illik keçid
Alpine.data('pricing', () => ({
    yearly: false,
}));

// Domen axtarışı
Alpine.data('domainSearch', (endpoint, initial = '') => ({
    query: initial,
    loading: false,
    results: [],
    error: '',
    async search() {
        this.error = '';
        const q = this.query.trim();
        if (!q) return;
        this.loading = true;
        this.results = [];
        try {
            const res = await fetch(endpoint + '?domain=' + encodeURIComponent(q), {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await res.json();
            if (!res.ok) {
                this.error = data.message || (data.errors && Object.values(data.errors)[0][0]) || '';
            } else {
                this.results = data.results;
            }
        } catch (e) {
            this.error = document.documentElement.dataset.errorGeneric || 'Error';
        } finally {
            this.loading = false;
        }
    },
}));

// Scroll zamanı görünmə: [data-reveal] elementləri ekrana girəndə .is-visible alır
function initReveal() {
    window.__revealReady = true;
    const items = document.querySelectorAll('[data-reveal]');
    if (!('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-visible'));
        return;
    }
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.08 },
    );
    items.forEach((el) => observer.observe(el));
}
document.addEventListener('DOMContentLoaded', initReveal);

// Header: səhifə aşağı sürüşdürüləndə kölgə
Alpine.data('siteHeader', () => ({
    open: false,
    scrolled: false,
    init() {
        const update = () => (this.scrolled = window.scrollY > 12);
        update();
        window.addEventListener('scroll', update, { passive: true });
    },
}));

window.Alpine = Alpine;
Alpine.start();
