<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';

const page = usePage();

const seo = computed(() => {
    const baseUrl = page.props.appUrl || '';
    return {
        title: 'IT Services — Repair, Setup & Support | Laptech Electronics',
        description: 'Professional IT services including laptop & PC repair, network setup, CCTV installation and hardware upgrades. Certified technicians in Nairobi, Kenya.',
        url: `${baseUrl}/services`,
        image: `${baseUrl}/favicon.png`,
    };
});

const props = defineProps({
    services: Array,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');

const applyFilters = () => {
    router.get(route('services.index'), {
        search: search.value,
        category: category.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    search.value = '';
    category.value = '';
    applyFilters();
};
</script>

<template>
    <Head :title="seo.title">
        <meta name="description" :content="seo.description" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:url" :content="seo.url" />
        <meta property="og:image" :content="seo.image" />
        <meta property="og:type" content="website" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="seo.title" />
        <meta name="twitter:description" :content="seo.description" />
        <meta name="twitter:image" :content="seo.image" />
    </Head>

    <div class="min-h-screen bg-page text-ink font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <div class="bg-surface mesh-bg border-b border-line">
            <div class="max-w-7xl mx-auto px-6 py-12 text-center max-w-2xl">
                <h1 class="text-3xl md:text-4xl font-heading font-extrabold mb-3">Our <span class="text-gradient">Services</span></h1>
                <p class="text-muted mb-8">Professional IT solutions delivered by certified technicians.</p>
                <div class="relative max-w-xl mx-auto">
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search services (e.g. Repair, Setup, CCTV)…"
                        class="w-full bg-surface-muted border border-line rounded-full pl-12 pr-28 py-3 text-ink placeholder:text-muted focus:border-red focus:ring-0 transition"
                    />
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-muted"></i>
                    <button @click="applyFilters" class="absolute right-2 top-1/2 -translate-y-1/2 bg-red hover:bg-red-dark text-white px-5 py-2 rounded-full text-sm font-bold transition">
                        Search
                    </button>
                </div>
            </div>
        </div>

        <main class="max-w-7xl mx-auto py-10 px-6">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters -->
                <aside class="w-full lg:w-64 shrink-0 space-y-6">
                    <div class="bg-surface border border-line rounded-2xl p-5">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-muted mb-4">Categories</h3>
                        <div class="space-y-1">
                            <button
                                @click="category = ''; applyFilters()"
                                :class="category === '' ? 'bg-red text-white' : 'text-muted hover:bg-surface-muted hover:text-ink'"
                                class="w-full text-left px-3 py-2 rounded-lg transition font-semibold text-sm"
                            >
                                All Services
                            </button>
                            <button
                                v-for="cat in categories"
                                :key="cat.id"
                                @click="category = cat.id; applyFilters()"
                                :class="category === cat.id ? 'bg-red text-white' : 'text-muted hover:bg-surface-muted hover:text-ink'"
                                class="w-full text-left px-3 py-2 rounded-lg transition font-semibold text-sm"
                            >
                                {{ cat.name }}
                            </button>
                        </div>
                    </div>

                    <div class="p-6 bg-red/5 border border-red/20 rounded-2xl">
                        <h4 class="font-bold mb-2 text-ink">Custom Project?</h4>
                        <p class="text-xs text-muted leading-relaxed mb-4">Don't see what you need? Our technicians can build a custom solution for you.</p>
                        <Link :href="route('quotes.create')" class="block text-center w-full py-2.5 bg-red hover:bg-red-dark text-white rounded-lg text-xs font-bold uppercase tracking-widest transition">
                            Request a Quote
                        </Link>
                    </div>

                    <button @click="clearFilters" class="w-full py-3 border border-line rounded-xl text-xs font-bold text-muted hover:text-red hover:border-red/40 transition uppercase tracking-widest">
                        Reset Filters
                    </button>
                </aside>

                <!-- Results -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-heading font-bold">Services</h2>
                        <div class="text-muted text-sm">{{ services.length }} available</div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6">
                        <Link v-for="service in services" :key="service.id" :href="route('services.show', service.slug)" class="group relative gradient-ring p-8 bg-surface border border-line rounded-2xl hover:border-red/40 hover:shadow-lg transition overflow-hidden">
                            <i :class="service.icon" class="absolute -right-3 -bottom-3 text-surface-muted text-8xl transition group-hover:text-red/10"></i>
                            <div class="relative z-10">
                                <div class="w-14 h-14 bg-red/10 group-hover:bg-brand-gradient rounded-xl flex items-center justify-center mb-5 transition">
                                    <i :class="service.icon" class="text-red group-hover:text-white text-2xl transition"></i>
                                </div>
                                <div class="text-xs font-bold text-red uppercase tracking-widest mb-2">
                                    {{ service.category?.name }}
                                </div>
                                <h3 class="text-lg font-heading font-bold mb-3 group-hover:text-red transition">{{ service.name }}</h3>
                                <p class="text-muted text-sm leading-relaxed mb-5 line-clamp-3">
                                    {{ service.description }}
                                </p>
                                <div class="flex items-center gap-2 text-red font-bold text-sm">
                                    Request a Quote <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                                </div>
                            </div>
                        </Link>

                        <div v-if="services.length === 0" class="col-span-full py-24 text-center">
                            <i class="fas fa-tools text-5xl text-line mb-5"></i>
                            <p class="text-muted font-semibold text-lg">No services match your search</p>
                            <button @click="clearFilters" class="mt-4 text-red font-bold hover:underline">Clear all filters</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
