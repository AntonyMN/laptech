<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import { useCartStore } from '@/Stores/cart';

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
    <Head title="My Wishlist - Laptech" />

    <div class="min-h-screen bg-charcoal text-white font-sans">
        <Navbar />

        <main class="max-w-7xl mx-auto py-20 px-6">
            <div class="mb-12">
                <h1 class="text-5xl font-black mb-4">My <span class="text-red">Wishlist</span></h1>
                <p class="text-white/40">Saved items for your future technical ecosystem.</p>
            </div>

            <div v-if="wishlistItems.length === 0" class="bg-charcoal-light border border-white/5 rounded-[3rem] p-20 text-center">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-white/20 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-4">Your wishlist is empty</h2>
                <p class="text-white/40 mb-8 max-w-md mx-auto">Explore our inventory and save your favorite hardware for later.</p>
                <Link :href="route('products.index')" class="bg-red hover:bg-red-light text-white px-8 py-4 rounded-2xl font-black transition shadow-xl shadow-red/20 inline-block">
                    Start Shopping
                </Link>
            </div>

            <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="item in wishlistItems" :key="item.id" class="group bg-charcoal-light border border-white/5 rounded-3xl overflow-hidden hover:border-red/50 transition duration-500">
                    <div class="relative h-64 bg-charcoal-dark overflow-hidden">
                        <img :src="item.product.image" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-80 group-hover:opacity-100" />
                        <button @click="toggleWishlist(item.product.id)" class="absolute top-4 right-4 w-10 h-10 bg-red text-white rounded-xl flex items-center justify-center shadow-lg shadow-red/20 transition hover:bg-red-light">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-2">{{ item.product.name }}</h3>
                        <p class="text-white/40 text-sm mb-6 line-clamp-2">{{ item.product.description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-black">Ksh {{ item.product.price.toLocaleString() }}</span>
                            <div class="flex gap-2">
                                <button @click="cart.addItem(item.product)" class="bg-red hover:bg-red-light text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-red/20">
                                    Add to Cart
                                </button>
                                <Link :href="route('products.show', item.product.slug)" class="w-12 h-12 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center transition">
                                    <i class="fas fa-eye"></i>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
