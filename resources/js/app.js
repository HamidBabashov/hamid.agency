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

window.Alpine = Alpine;
Alpine.start();
