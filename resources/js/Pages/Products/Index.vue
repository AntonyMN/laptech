<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import ProductCard from '../../Components/ProductCard.vue';

const page = usePage();

const seo = computed(() => {
    const baseUrl = page.props.appUrl || '';
    return {
        title: 'Shop Laptops, Desktops & Accessories | Laptech Electronics',
        description: 'Browse quality laptops, desktops, accessories and CCTV from Laptech Electronics Nairobi. Brand new & certified refurbished with nationwide delivery.',
        url: `${baseUrl}/products`,
        image: `${baseUrl}/favicon.png`,
    };
});

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');
const status = ref(props.filters.status || '');
const min_price = ref(props.filters.min_price || '');
const max_price = ref(props.filters.max_price || '');
const sort = ref(props.filters.sort || 'latest');

const statuses = ['Brand new', 'Ex-UK', 'Certified Refurbished'];

const sortOptions = [
    { value: 'latest', label: 'Latest arrivals' },
    { value: 'price_asc', label: 'Price: low to high' },
    { value: 'price_desc', label: 'Price: high to low' },
    { value: 'name_asc', label: 'Name: A to Z' },
    { value: 'name_desc', label: 'Name: Z to A' },
];

const applyFilters = () => {
    router.get(route('products.index'), {
        search: search.value,
        category: category.value,
        status: status.value,
        min_price: min_price.value,
        max_price: max_price.value,
        sort: sort.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    search.value = '';
    category.value = '';
    status.value = '';
    min_price.value = '';
    max_price.value = '';
    sort.value = 'latest';
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

        <!-- Page header -->
        <div class="bg-surface border-b border-line">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <nav class="text-xs text-muted mb-3">
                    <Link :href="route('welcome')" class="hover:text-red">Home</Link>
                    <span class="mx-2">/</span>
                    <span class="text-ink font-semibold">Shop</span>
                </nav>
                <h1 class="text-3xl font-heading font-extrabold">All Products</h1>
                <div class="relative mt-6 max-w-xl">
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search products…"
                        class="w-full bg-surface-muted border border-line rounded-full pl-12 pr-28 py-3 text-ink placeholder:text-muted focus:border-red focus:ring-0 transition"
                    />
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-muted"></i>
                    <button @click="applyFilters" class="absolute right-2 top-1/2 -translate-y-1/2 bg-red hover:bg-red-dark text-white px-5 py-2 rounded-full text-sm font-bold transition">
                        Search
                    </button>
                </div>
            </div>
        </div>

        <main class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters -->
                <aside class="w-full lg:w-64 shrink-0 space-y-8">
                    <div class="bg-surface border border-line rounded-2xl p-5">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-muted mb-4">Categories</h3>
                        <div class="space-y-1">
                            <button
                                @click="category = ''; applyFilters()"
                                :class="category === '' ? 'bg-red text-white' : 'text-muted hover:bg-surface-muted hover:text-ink'"
                                class="w-full text-left px-3 py-2 rounded-lg transition font-semibold text-sm"
                            >
                                All Categories
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

                    <div class="bg-surface border border-line rounded-2xl p-5">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-muted mb-4">Condition</h3>
                        <div class="space-y-1">
                            <button
                                @click="status = ''; applyFilters()"
                                :class="status === '' ? 'bg-red text-white' : 'text-muted hover:bg-surface-muted hover:text-ink'"
                                class="w-full text-left px-3 py-2 rounded-lg transition font-semibold text-sm"
                            >
                                All Conditions
                            </button>
                            <button
                                v-for="s in statuses"
                                :key="s"
                                @click="status = s; applyFilters()"
                                :class="status === s ? 'bg-red text-white' : 'text-muted hover:bg-surface-muted hover:text-ink'"
                                class="w-full text-left px-3 py-2 rounded-lg transition font-semibold text-sm"
                            >
                                {{ s }}
                            </button>
                        </div>
                    </div>

                    <div class="bg-surface border border-line rounded-2xl p-5">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-muted mb-4">Price Range</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <input v-model="min_price" type="number" placeholder="Min" class="bg-surface-muted border border-line rounded-lg px-3 py-2 text-sm focus:border-red focus:ring-0" />
                            <input v-model="max_price" type="number" placeholder="Max" class="bg-surface-muted border border-line rounded-lg px-3 py-2 text-sm focus:border-red focus:ring-0" />
                        </div>
                        <button @click="applyFilters" class="w-full mt-4 bg-ink hover:bg-charcoal text-white py-2 rounded-lg text-xs font-bold transition uppercase tracking-widest">Apply</button>
                    </div>

                    <button @click="clearFilters" class="w-full py-3 border border-line rounded-xl text-xs font-bold text-muted hover:text-red hover:border-red/40 transition uppercase tracking-widest">
                        Reset Filters
                    </button>
                </aside>

                <!-- Results -->
                <div class="flex-1">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div class="flex items-baseline gap-3">
                            <h2 class="text-lg font-heading font-bold">Products</h2>
                            <span class="text-muted text-sm">{{ products.total || products.data.length }} items</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="sort" class="text-xs font-bold uppercase tracking-widest text-muted">Sort by</label>
                            <div class="relative">
                                <select
                                    id="sort"
                                    v-model="sort"
                                    @change="applyFilters"
                                    class="appearance-none bg-surface border border-line rounded-full pl-4 pr-10 py-2 text-sm font-semibold text-ink focus:border-red focus:ring-0 cursor-pointer transition"
                                >
                                    <option v-for="opt in sortOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-muted text-[10px] pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-5">
                        <ProductCard v-for="product in products.data" :key="product.id" :product="product" />

                        <div v-if="!products.data || products.data.length === 0" class="col-span-full py-24 text-center">
                            <i class="fas fa-box-open text-5xl text-line mb-5"></i>
                            <p class="text-muted font-semibold text-lg">No products match your filters</p>
                            <button @click="clearFilters" class="mt-4 text-red font-bold hover:underline">Clear all filters</button>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="products.links && products.links.length > 3" class="mt-12 flex flex-wrap justify-center gap-2">
                        <component
                            :is="link.url ? Link : 'span'"
                            v-for="(link, index) in products.links"
                            :key="index"
                            :href="link.url"
                            v-html="link.label"
                            :class="[
                                'px-4 py-2.5 rounded-lg font-bold text-sm transition border select-none',
                                link.active
                                    ? 'bg-red border-red text-white'
                                    : 'bg-surface border-line text-muted hover:text-ink hover:border-red/40',
                                !link.url ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'
                            ]"
                        />
                    </div>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
