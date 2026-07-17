<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import { useCartStore } from '@/Stores/cart';

const page = usePage();

const seo = computed(() => {
    const baseUrl = page.props.appUrl || '';
    return {
        title: 'My Wishlist — Laptech Electronics',
        description: 'Your saved products on Laptech Electronics.',
        url: `${baseUrl}/wishlist`,
        image: `${baseUrl}/favicon.png`,
    };
});

const props = defineProps({
    wishlistItems: Array,
});

const cart = useCartStore();

const toggleWishlist = (productId) => {
    router.post(route('wishlist.toggle', productId), {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="seo.title">
        <meta name="description" :content="seo.description" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:url" :content="seo.url" />
        <meta property="og:image" :content="seo.image" />
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <div class="min-h-screen bg-page text-ink font-sans">
        <Navbar />

        <main class="max-w-7xl mx-auto py-12 px-6">
            <div class="mb-8">
                <h1 class="text-3xl font-heading font-extrabold mb-2">My <span class="text-red">Wishlist</span></h1>
                <p class="text-muted">Products you've saved for later.</p>
            </div>

            <div v-if="wishlistItems.length === 0" class="bg-surface border border-line rounded-2xl p-16 text-center">
                <div class="w-20 h-20 bg-surface-muted rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="far fa-heart text-muted text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold mb-3">Your wishlist is empty</h2>
                <p class="text-muted mb-8 max-w-md mx-auto">Browse our products and save your favorites for later.</p>
                <Link :href="route('products.index')" class="bg-red hover:bg-red-dark text-white px-8 py-3.5 rounded-full font-bold transition inline-block">
                    Start Shopping
                </Link>
            </div>

            <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                <div v-for="item in wishlistItems" :key="item.id" class="group flex flex-col bg-surface border border-line rounded-2xl overflow-hidden hover:shadow-lg hover:border-red/40 transition">
                    <div class="relative aspect-square bg-surface-muted overflow-hidden">
                        <Link :href="route('products.show', item.product.slug)">
                            <img :src="item.product.image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                        </Link>
                        <button @click="toggleWishlist(item.product.id)" title="Remove from wishlist" class="absolute top-3 right-3 w-9 h-9 bg-red text-white rounded-full flex items-center justify-center shadow transition hover:bg-red-dark">
                            <i class="fas fa-heart text-xs"></i>
                        </button>
                    </div>
                    <div class="flex flex-col flex-1 p-4">
                        <span class="text-[11px] font-semibold uppercase tracking-wide text-red mb-1">{{ item.product.category?.name }}</span>
                        <Link :href="route('products.show', item.product.slug)">
                            <h3 class="text-sm font-heading font-bold leading-snug line-clamp-2 min-h-[2.5rem] group-hover:text-red transition">{{ item.product.name }}</h3>
                        </Link>
                        <div class="mt-3 flex items-end justify-between gap-2">
                            <span class="text-lg font-heading font-extrabold">Ksh {{ item.product.price.toLocaleString() }}</span>
                            <button @click="cart.addItem(item.product)" class="shrink-0 inline-flex items-center gap-1.5 bg-red hover:bg-red-dark text-white text-xs font-bold px-3 py-2 rounded-lg transition">
                                <i class="fas fa-cart-plus"></i> <span class="hidden sm:inline">Add</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
