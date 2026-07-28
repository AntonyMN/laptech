<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useCartStore } from '../Stores/cart';
import { useTheme } from '../composables/useTheme';
import CartSidebar from './CartSidebar.vue';
import WhatsAppFAB from './WhatsAppFAB.vue';

const cart = useCartStore();
const page = usePage();
const { isDark, initTheme, toggleTheme } = useTheme();
onMounted(initTheme);
const isMobileMenuOpen = ref(false);
const searchQuery = ref('');
const selectedCategory = ref('');

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: () => usePage().props.canLogin
    },
    canRegister: {
        type: Boolean,
        default: () => usePage().props.canRegister
    },
});

const categories = computed(() => page.props.navCategories || []);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const submitSearch = () => {
    const params = {};
    if (searchQuery.value.trim()) {
        params.search = searchQuery.value;
    }
    if (selectedCategory.value) {
        params.category = selectedCategory.value;
    }
    router.get(route('products.index'), params);
    isMobileMenuOpen.value = false;
};

const filterByCategory = (id) => {
    selectedCategory.value = id;
    submitSearch();
};

// Sync search bar filters when URL / Page changes
watch(() => page.url, (newUrl) => {
    if (typeof window !== 'undefined') {
        const urlObj = new URL(newUrl, window.location.origin);
        searchQuery.value = urlObj.searchParams.get('search') || '';
        selectedCategory.value = urlObj.searchParams.get('category') || '';
    }
}, { immediate: true });

const phone = '+254 722 964566';
const whatsapp = 'https://wa.me/254722964566';
</script>

<template>
    <CartSidebar />
    <WhatsAppFAB />

    <!-- Flash Notifications -->
    <div v-if="page.props.flash?.success" class="fixed top-24 right-6 z-[60] p-4 bg-white border border-emerald-200 text-emerald-700 rounded-xl shadow-xl flex items-center gap-3 animate-in slide-in-from-right duration-500">
        <i class="fas fa-check-circle text-emerald-500"></i>
        <span class="font-semibold text-sm">{{ page.props.flash.success }}</span>
    </div>
    <div v-if="page.props.flash?.error" class="fixed top-24 right-6 z-[60] p-4 bg-white border border-red/30 text-red-dark rounded-xl shadow-xl flex items-center gap-3 animate-in slide-in-from-right duration-500">
        <i class="fas fa-exclamation-circle text-red"></i>
        <span class="font-semibold text-sm">{{ page.props.flash.error }}</span>
    </div>

    <header class="sticky top-0 z-50">
        <!-- Tier 1: Utility bar -->
        <div class="hidden md:block bg-charcoal text-white/80 text-xs">
            <div class="max-w-7xl mx-auto px-6 h-9 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <a :href="`tel:${phone.replace(/\s/g, '')}`" class="flex items-center gap-2 hover:text-white transition">
                        <i class="fas fa-phone-alt text-red"></i> {{ phone }}
                    </a>
                    <a :href="whatsapp" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-white transition">
                        <i class="fab fa-whatsapp text-green-400"></i> WhatsApp
                    </a>
                    <span class="flex items-center gap-2">
                        <i class="far fa-clock text-white/40"></i> Mon–Sat 9am–7pm
                    </span>
                </div>
                <div class="flex items-center gap-2 font-semibold text-white">
                    <i class="fas fa-truck text-red"></i> Free delivery within Nairobi
                </div>
            </div>
        </div>

        <!-- Tier 2: Main bar -->
        <div class="glass-nav border-b border-line">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center gap-4">
                <!-- Logo -->
                <Link :href="route('welcome')" class="flex items-center shrink-0 group">
                    <img src="/images/logo.png" alt="Laptech" class="h-10 w-auto transition group-hover:scale-105 duration-300">
                </Link>

                <!-- Search -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-auto">
                    <div class="flex w-full items-center bg-surface-muted border border-line rounded-full overflow-hidden focus-within:border-red transition">
                        
                        <!-- Categories Dropdown inside Search -->
                        <div class="relative shrink-0 border-r border-line bg-surface/50">
                            <select
                                v-model="selectedCategory"
                                @change="submitSearch"
                                class="bg-transparent border-none text-xs font-semibold text-muted focus:ring-0 pl-4 pr-9 py-2.5 cursor-pointer appearance-none outline-none"
                            >
                                <option value="">All Categories</option>
                                <option
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    :value="cat.id"
                                >
                                    {{ cat.name }}
                                </option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-muted text-[10px] pointer-events-none"></i>
                        </div>

                        <i class="fas fa-search ml-4 text-muted text-sm"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="submitSearch"
                            type="text"
                            placeholder="Search laptops, accessories, services…"
                            class="flex-1 bg-transparent border-none focus:ring-0 px-3 py-2 text-sm text-ink placeholder:text-muted"
                        />
                        <button @click="submitSearch" class="bg-red hover:bg-red-dark text-white text-sm font-bold px-5 py-2.5 transition">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-1 sm:gap-2 ml-auto">
                    <button @click="toggleTheme" :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'" :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'" class="w-10 h-10 flex items-center justify-center text-ink hover:text-red rounded-full hover:bg-surface-muted transition">
                        <i class="fas" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <Link v-if="page.props.auth?.user" :href="route('wishlist.index')" class="hidden sm:flex w-10 h-10 items-center justify-center text-ink hover:text-red rounded-full hover:bg-surface-muted transition" title="Wishlist">
                        <i class="far fa-heart text-lg"></i>
                    </Link>

                    <button @click="cart.toggleCart" class="relative w-10 h-10 flex items-center justify-center text-ink hover:text-red rounded-full hover:bg-surface-muted transition" title="Cart">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span v-if="cart.totalItems > 0" class="absolute top-0 right-0 bg-red text-white text-[10px] h-[18px] min-w-[18px] px-1 rounded-full flex items-center justify-center font-bold">
                            {{ cart.totalItems }}
                        </span>
                    </button>

                    <div v-if="canLogin" class="hidden sm:block ml-1">
                        <Link v-if="page.props.auth?.user" :href="route('dashboard')" class="flex items-center gap-2 bg-red hover:bg-red-dark text-white px-4 py-2 rounded-full text-sm font-bold transition">
                            <i class="fas fa-user-circle"></i> Account
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-bold text-ink hover:text-red transition">Log in</Link>
                            <Link v-if="canRegister" :href="route('register')" class="ml-3 bg-red hover:bg-red-dark text-white px-4 py-2 rounded-full text-sm font-bold transition">
                                Register
                            </Link>
                        </template>
                    </div>

                    <button @click="toggleMobileMenu" class="md:hidden w-10 h-10 flex items-center justify-center text-ink text-xl">
                        <i class="fas" :class="isMobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tier 3: Category nav -->
        <div class="hidden md:block glass-nav border-b border-line">
            <div class="max-w-7xl mx-auto px-6 h-11 flex items-center gap-1 text-sm font-semibold overflow-x-auto">
                <Link :href="route('products.index')" class="flex items-center gap-2 px-3 py-2 rounded-lg text-ink hover:text-red hover:bg-surface-muted transition whitespace-nowrap">
                    <i class="fas fa-th-large text-red"></i> All Products
                </Link>
                <span class="mx-1 h-4 w-px bg-line"></span>
                <Link :href="route('services.index')" class="px-3 py-2 rounded-lg text-muted hover:text-red hover:bg-surface-muted transition whitespace-nowrap">Services</Link>
                <Link :href="route('blog.index')" class="px-3 py-2 rounded-lg text-muted hover:text-red hover:bg-surface-muted transition whitespace-nowrap">Blog</Link>
                <Link :href="route('compare.index')" class="px-3 py-2 rounded-lg text-muted hover:text-red hover:bg-surface-muted transition whitespace-nowrap">Compare</Link>
                <Link :href="route('quotes.create')" class="ml-auto px-4 py-2 rounded-lg text-red hover:bg-red/10 font-bold transition whitespace-nowrap">
                    Request a Quote <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </Link>
            </div>
        </div>

        <!-- Mobile drawer -->
        <div v-if="isMobileMenuOpen" class="md:hidden bg-surface border-b border-line shadow-lg animate-in slide-in-from-top duration-300">
            <div class="p-5 space-y-5">
                <!-- Search -->
                <div class="flex items-center bg-surface-muted border border-line rounded-full overflow-hidden">
                    <i class="fas fa-search ml-4 text-muted text-sm"></i>
                    <input
                        v-model="searchQuery"
                        @keyup.enter="submitSearch"
                        type="text"
                        placeholder="Search products…"
                        class="flex-1 bg-transparent border-none focus:ring-0 px-3 py-2 text-sm text-ink placeholder:text-muted"
                    />
                    <button @click="submitSearch" class="bg-red text-white text-sm font-bold px-5 py-2.5">Go</button>
                </div>

                <div class="flex flex-col gap-1 text-sm font-semibold">
                    <Link :href="route('products.index')" class="py-2 text-ink hover:text-red transition">All Products</Link>
                    <button
                        v-for="cat in categories"
                        :key="cat.id"
                        @click="filterByCategory(cat.id)"
                        class="text-left py-2 text-muted hover:text-red transition"
                    >
                        {{ cat.name }}
                    </button>
                    <Link :href="route('services.index')" class="py-2 text-ink hover:text-red transition">Services</Link>
                    <Link :href="route('blog.index')" class="py-2 text-ink hover:text-red transition">Blog</Link>
                    <Link :href="route('compare.index')" class="py-2 text-ink hover:text-red transition">Compare</Link>
                    <Link v-if="page.props.auth?.user" :href="route('wishlist.index')" class="py-2 text-ink hover:text-red transition">Wishlist</Link>
                    <Link :href="route('quotes.create')" class="py-2 text-red font-bold transition">Request a Quote</Link>
                    <button @click="toggleTheme" class="flex items-center gap-3 py-2 text-ink hover:text-red transition text-left">
                        <i class="fas" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                        {{ isDark ? 'Light mode' : 'Dark mode' }}
                    </button>
                </div>

                <div class="pt-4 border-t border-line">
                    <div v-if="page.props.auth?.user">
                        <Link :href="route('dashboard')" @click="isMobileMenuOpen = false" class="block bg-red hover:bg-red-dark text-white px-6 py-3 rounded-xl font-bold text-center transition">
                            My Account
                        </Link>
                    </div>
                    <div v-else class="flex gap-3">
                        <Link :href="route('login')" @click="isMobileMenuOpen = false" class="flex-1 py-3 rounded-xl border border-line text-ink text-center font-bold">Log in</Link>
                        <Link :href="route('register')" @click="isMobileMenuOpen = false" class="flex-1 py-3 rounded-xl bg-red text-white text-center font-bold">Register</Link>
                    </div>
                </div>

                <div class="pt-4 border-t border-line flex items-center justify-between text-xs text-muted">
                    <a :href="`tel:${phone.replace(/\s/g, '')}`" class="flex items-center gap-2"><i class="fas fa-phone-alt text-red"></i> {{ phone }}</a>
                    <a :href="whatsapp" target="_blank" rel="noopener" class="flex items-center gap-2"><i class="fab fa-whatsapp text-green-500"></i> WhatsApp</a>
                </div>
            </div>
        </div>
    </header>
</template>
