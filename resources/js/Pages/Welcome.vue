<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import ProductCard from '../Components/ProductCard.vue';

const page = usePage();

const seo = computed(() => {
    const baseUrl = page.props.appUrl || '';
    return {
        title: 'Laptech Electronics — Laptops, Desktops & IT Services in Nairobi',
        description: 'Shop quality laptops, desktops, accessories and CCTV at Laptech Electronics, Nairobi. Certified refurbished & brand new, nationwide delivery, expert repair services.',
        url: `${baseUrl}`,
        image: `${baseUrl}/favicon.png`,
    };
});

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    categories: Array,
    serverProducts: Array,
    featuredProducts: Array,
    hotDeals: Array,
    featuredServices: Array,
});

const searchQuery = ref('');

// Servers get top billing across the homepage
const serverCategoryId = computed(() => props.serverProducts?.[0]?.category?.id || null);

// Show the Servers category tile first
const sortedCategories = computed(() => {
    const cats = [...(props.categories || [])];
    return cats.sort((a, b) => {
        if (a.name === 'Servers') return -1;
        if (b.name === 'Servers') return 1;
        return 0;
    });
});

// Hero carousel — feature servers first when available, else featured products
const heroSlides = computed(() => {
    const source = props.serverProducts?.length ? props.serverProducts : (props.featuredProducts || []);
    return source.slice(0, 5);
});
const currentSlide = ref(0);
let slideTimer = null;

const goToSlide = (i) => {
    if (!heroSlides.value.length) return;
    currentSlide.value = (i + heroSlides.value.length) % heroSlides.value.length;
    restartTimer();
};
const nextSlide = () => goToSlide(currentSlide.value + 1);
const prevSlide = () => goToSlide(currentSlide.value - 1);

const restartTimer = () => {
    if (slideTimer) clearInterval(slideTimer);
    if (heroSlides.value.length > 1) {
        slideTimer = setInterval(() => {
            currentSlide.value = (currentSlide.value + 1) % heroSlides.value.length;
        }, 4500);
    }
};

onMounted(restartTimer);
onBeforeUnmount(() => slideTimer && clearInterval(slideTimer));

const heroProduct = computed(() => heroSlides.value[currentSlide.value] || props.featuredProducts?.[0] || null);

const categoryIcons = {
    'Laptops': 'fa-laptop',
    'Desktops': 'fa-desktop',
    'Desktop Computers': 'fa-desktop',
    'Accessories': 'fa-headphones',
    'CCTV': 'fa-video',
    'Monitors': 'fa-tv',
    'Printers': 'fa-print',
    'Phones': 'fa-mobile-alt',
    'Networking': 'fa-network-wired',
    'Servers': 'fa-server',
    'Storage': 'fa-hdd',
};
const iconFor = (name) => categoryIcons[name] || 'fa-microchip';

const searchProducts = () => {
    if (searchQuery.value.trim()) {
        router.get(route('products.index'), { search: searchQuery.value });
    }
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
        <Navbar :canLogin="canLogin" :canRegister="canRegister" />

        <!-- Hero -->
        <section class="relative overflow-hidden bg-surface border-b border-line">
            <!-- Decorative background -->
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-32 -right-24 w-[38rem] h-[38rem] bg-red/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-32 w-[32rem] h-[32rem] bg-red/5 rounded-full blur-3xl"></div>
                <div class="absolute inset-0 opacity-[0.4]" style="background-image:radial-gradient(var(--color-line) 1px,transparent 1px);background-size:22px 22px;-webkit-mask-image:radial-gradient(ellipse 80% 60% at 50% 40%,#000 40%,transparent 100%);mask-image:radial-gradient(ellipse 80% 60% at 50% 40%,#000 40%,transparent 100%);"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-6 py-12 lg:py-16 grid lg:grid-cols-12 gap-10 items-center">
                <!-- Left -->
                <div class="lg:col-span-6">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-surface border border-line shadow-sm text-red font-bold text-xs uppercase tracking-widest mb-5">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-red opacity-75 animate-ping"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red"></span>
                        </span>
                        New stock just landed
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-heading font-extrabold leading-[1.05] mb-5">
                        Tech that keeps<br class="hidden sm:block" />
                        you <span class="relative inline-block text-red">
                            ahead.
                            <svg class="absolute -bottom-1 left-0 w-full" height="8" viewBox="0 0 200 8" preserveAspectRatio="none"><path d="M2 6 Q100 0 198 6" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/></svg>
                        </span>
                    </h1>
                    <p class="text-lg text-muted mb-7 max-w-lg leading-relaxed">
                        Laptops, desktops, accessories and expert IT services — brand new and certified refurbished, delivered across Kenya.
                    </p>

                    <!-- Search -->
                    <div class="flex items-center bg-surface border border-line rounded-full overflow-hidden max-w-lg focus-within:border-red focus-within:shadow-lg transition mb-5 shadow-sm">
                        <i class="fas fa-search ml-5 text-muted"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="searchProducts"
                            type="text"
                            placeholder="Search laptops, accessories, services…"
                            class="flex-1 bg-transparent border-none focus:ring-0 px-4 py-3.5 text-ink placeholder:text-muted"
                        />
                        <button @click="searchProducts" class="bg-red hover:bg-red-dark text-white px-7 py-3.5 font-bold transition">
                            Search
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 mb-8">
                        <Link :href="route('products.index')" class="inline-flex items-center gap-2 bg-red hover:bg-red-dark text-white px-6 py-3 rounded-full font-bold transition shadow-lg shadow-red/20">
                            Start Shopping <i class="fas fa-arrow-right"></i>
                        </Link>
                        <Link :href="route('services.index')" class="inline-flex items-center gap-2 text-ink hover:text-red px-4 py-3 font-bold transition">
                            <span class="w-9 h-9 rounded-full bg-surface border border-line flex items-center justify-center"><i class="fas fa-play text-xs"></i></span>
                            Our Services
                        </Link>
                    </div>

                    <!-- Inline trust badges -->
                    <div class="flex flex-wrap gap-x-6 gap-y-3 text-sm text-muted">
                        <span class="inline-flex items-center gap-2"><i class="fas fa-truck text-red"></i> Free Nairobi delivery</span>
                        <span class="inline-flex items-center gap-2"><i class="fas fa-shield-alt text-red"></i> Genuine warranty</span>
                        <span class="inline-flex items-center gap-2"><i class="fas fa-undo text-red"></i> 7-day returns</span>
                    </div>
                </div>

                <!-- Right: rotating showcase -->
                <div class="lg:col-span-6">
                    <div v-if="heroProduct" class="relative">
                        <!-- Floating stat chips -->
                        <div class="hidden sm:flex absolute -top-4 -left-4 z-20 items-center gap-2 bg-surface border border-line rounded-full pl-2 pr-4 py-2 shadow-lg">
                            <span class="w-8 h-8 rounded-full bg-red/10 flex items-center justify-center"><i class="fas fa-bolt text-red text-xs"></i></span>
                            <span class="text-xs font-bold text-ink leading-tight">Featured<br><span class="text-muted font-medium">picks</span></span>
                        </div>
                        <div class="hidden sm:flex absolute -bottom-4 -right-3 z-20 items-center gap-2 bg-charcoal text-white rounded-full pl-2 pr-4 py-2 shadow-lg">
                            <span class="w-8 h-8 rounded-full bg-red flex items-center justify-center"><i class="fas fa-check text-white text-xs"></i></span>
                            <span class="text-xs font-bold leading-tight">In stock<br><span class="text-white/60 font-medium">ready to ship</span></span>
                        </div>

                        <!-- Card -->
                        <div class="relative bg-surface border border-line rounded-3xl p-5 sm:p-7 shadow-xl overflow-hidden">
                            <Link :href="route('products.show', heroProduct.slug)" class="block group">
                                <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-surface-muted mb-5">
                                    <transition name="fade" mode="out-in">
                                        <img :key="heroProduct.id" :src="heroProduct.image || '/images/hero.png'" :alt="heroProduct.name" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700" />
                                    </transition>
                                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-red text-white text-[11px] font-bold uppercase tracking-wide shadow">Featured</span>
                                </div>
                            </Link>
                            <div class="flex items-end justify-between gap-4">
                                <div class="min-w-0">
                                    <span class="text-xs font-semibold uppercase tracking-widest text-red">{{ heroProduct.category?.name }}</span>
                                    <Link :href="route('products.show', heroProduct.slug)">
                                        <h3 class="text-lg sm:text-xl font-heading font-bold mt-1 mb-1 truncate hover:text-red transition">{{ heroProduct.name }}</h3>
                                    </Link>
                                    <span class="text-2xl font-heading font-extrabold">Ksh {{ heroProduct.price.toLocaleString() }}</span>
                                </div>
                                <Link :href="route('products.show', heroProduct.slug)" class="shrink-0 inline-flex items-center gap-2 bg-red hover:bg-red-dark text-white px-5 py-3 rounded-full font-bold transition shadow-lg shadow-red/20">
                                    Shop <i class="fas fa-arrow-right"></i>
                                </Link>
                            </div>

                            <!-- Carousel controls -->
                            <div v-if="heroSlides.length > 1" class="flex items-center justify-between mt-5 pt-4 border-t border-line">
                                <div class="flex gap-2">
                                    <button
                                        v-for="(s, i) in heroSlides"
                                        :key="s.id"
                                        @click="goToSlide(i)"
                                        :aria-label="`Slide ${i + 1}`"
                                        :class="i === currentSlide ? 'w-6 bg-red' : 'w-2 bg-line hover:bg-muted'"
                                        class="h-2 rounded-full transition-all duration-300"
                                    ></button>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="prevSlide" aria-label="Previous" class="w-9 h-9 rounded-full border border-line text-ink hover:bg-red hover:text-white hover:border-red transition flex items-center justify-center">
                                        <i class="fas fa-chevron-left text-xs"></i>
                                    </button>
                                    <button @click="nextSlide" aria-label="Next" class="w-9 h-9 rounded-full border border-line text-ink hover:bg-red hover:text-white hover:border-red transition flex items-center justify-center">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="relative">
                        <img src="/images/hero.png" alt="Laptech" class="w-full rounded-3xl border border-line shadow-xl" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Enterprise Servers (our specialty) -->
        <section v-if="serverProducts?.length" class="bg-charcoal text-white">
            <div class="max-w-7xl mx-auto px-6 py-14">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                    <div>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red/20 text-red-bright font-bold text-xs uppercase tracking-widest mb-4">
                            <i class="fas fa-server"></i> Our specialty
                        </span>
                        <h2 class="text-3xl md:text-4xl font-heading font-extrabold">Enterprise <span class="text-red-bright">Servers</span></h2>
                        <p class="text-white/60 mt-2 max-w-xl">Rack, tower and blade servers from Dell, HPE and Lenovo — configured, tested and backed by warranty.</p>
                    </div>
                    <Link
                        :href="serverCategoryId ? route('products.index', { category: serverCategoryId }) : route('products.index')"
                        class="shrink-0 inline-flex items-center gap-2 bg-red hover:bg-red-dark text-white px-6 py-3 rounded-full font-bold transition shadow-lg shadow-red/20"
                    >
                        Shop all servers <i class="fas fa-arrow-right"></i>
                    </Link>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                    <ProductCard v-for="server in serverProducts" :key="server.id" :product="server" />
                </div>
            </div>
        </section>

        <!-- Category tiles -->
        <section class="max-w-7xl mx-auto px-6 py-12">
            <h2 class="text-2xl font-heading font-extrabold mb-6">Shop by Category</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <Link
                    v-for="cat in sortedCategories"
                    :key="cat.id"
                    :href="route('products.index', { category: cat.id })"
                    class="group flex flex-col items-center justify-center gap-3 bg-surface border border-line rounded-2xl p-6 hover:border-red hover:shadow-lg transition"
                >
                    <div class="w-14 h-14 rounded-full bg-red/10 group-hover:bg-red flex items-center justify-center transition">
                        <i :class="`fas ${iconFor(cat.name)}`" class="text-red group-hover:text-white text-xl transition"></i>
                    </div>
                    <span class="text-sm font-bold text-center text-ink group-hover:text-red transition">{{ cat.name }}</span>
                </Link>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-heading font-extrabold">Featured Products</h2>
                    <div class="h-1 w-16 bg-red mt-2 rounded-full"></div>
                </div>
                <Link :href="route('products.index')" class="text-red font-bold text-sm hover:underline whitespace-nowrap">
                    View all <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </Link>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
            </div>
        </section>

        <!-- Hot Deals (horizontal scroll) -->
        <section v-if="hotDeals?.length" class="bg-surface border-y border-line py-10 mt-6">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-end justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-heading font-extrabold flex items-center gap-2">
                            <i class="fas fa-fire text-red"></i> Hot Deals
                        </h2>
                        <div class="h-1 w-16 bg-red mt-2 rounded-full"></div>
                    </div>
                    <Link :href="route('products.index')" class="text-red font-bold text-sm hover:underline whitespace-nowrap">
                        View all <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </Link>
                </div>
                <div class="flex gap-5 overflow-x-auto pb-4 snap-x -mx-2 px-2">
                    <div v-for="product in hotDeals" :key="product.id" class="snap-start shrink-0 w-56">
                        <ProductCard :product="product" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section v-if="featuredServices?.length" class="max-w-7xl mx-auto px-6 py-14">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-heading font-extrabold mb-3">Expert Services</h2>
                <p class="text-muted max-w-2xl mx-auto">Professional IT solutions delivered by certified technicians.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <Link
                    v-for="service in featuredServices"
                    :key="service.id"
                    :href="route('services.show', service.slug)"
                    class="group p-8 bg-surface border border-line rounded-2xl hover:border-red hover:shadow-lg transition"
                >
                    <div class="w-14 h-14 bg-red/10 group-hover:bg-red rounded-xl flex items-center justify-center mb-5 transition">
                        <i :class="service.icon" class="text-red group-hover:text-white text-2xl transition"></i>
                    </div>
                    <h3 class="text-lg font-heading font-bold mb-2 group-hover:text-red transition">{{ service.name }}</h3>
                    <p class="text-sm text-muted leading-relaxed line-clamp-3">{{ service.description }}</p>
                    <span class="mt-5 inline-flex items-center gap-2 text-red font-bold text-sm">
                        Learn more <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                    </span>
                </Link>
            </div>
        </section>

        <!-- CTA band -->
        <section class="px-6 pb-16">
            <div class="max-w-6xl mx-auto bg-gradient-to-br from-red to-red-dark rounded-3xl p-10 md:p-16 text-center text-white shadow-xl">
                <h2 class="text-3xl md:text-4xl font-heading font-extrabold mb-4">Need a custom quote?</h2>
                <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">
                    Tell us what you need and our specialists will get back to you within 24 hours.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link :href="route('quotes.create')" class="bg-white text-red hover:bg-charcoal hover:text-white px-8 py-3.5 rounded-full font-bold transition">
                        Get a Free Quote
                    </Link>
                    <a href="https://wa.me/254722964566" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border-2 border-white/40 hover:border-white px-8 py-3.5 rounded-full font-bold transition">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
        </section>

        <Footer />
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
