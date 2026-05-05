<script setup>
import { Head, Link } from '@inertiajs/vue3';

import { ref } from 'vue';
import { useCartStore } from '../../Stores/cart';
import Navbar from '../../Components/Navbar.vue';


const cart = useCartStore();


const props = defineProps({
    product: Object,
    relatedProducts: Array,
});

const quantity = ref(1);

const addToCart = () => {
    cart.addItem(props.product, quantity.value);
};

const activeImage = ref(props.product.images?.[0] || props.product.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=800');


</script>

<template>
    <Head :title="product.name + ' - Laptech'" />

    <div class="min-h-screen bg-charcoal text-white font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="max-w-7xl mx-auto py-16 px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-start">
                <!-- Product Image -->
                <div class="space-y-6">
                    <div class="bg-charcoal-light border border-white/10 rounded-[2rem] overflow-hidden aspect-square group">
                        <img 
                            :src="activeImage" 
                            :alt="product.name"
                            class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-700 group-hover:scale-105"
                        />
                    </div>
                    <!-- Thumbnails -->
                    <div v-if="product.images && product.images.length > 0" class="grid grid-cols-5 gap-4">
                        <div 
                            v-for="(img, idx) in product.images" 
                            :key="idx" 
                            @click="activeImage = img"
                            :class="activeImage === img ? 'border-red' : 'border-white/10 opacity-40 hover:opacity-100'"
                            class="aspect-square bg-charcoal-light border rounded-xl cursor-pointer transition overflow-hidden"
                        >
                            <img :src="img" class="w-full h-full object-cover" />
                        </div>
                    </div>

                </div>

                <!-- Product Details -->
                <div class="space-y-10">
                    <div>
                        <span class="px-3 py-1 bg-red/20 text-red-bright rounded-full text-xs font-bold uppercase tracking-widest mb-4 inline-block">
                            {{ product.category?.name }}
                        </span>
                        <h1 class="text-5xl font-heading font-black mb-4">{{ product.name }}</h1>
                        <div class="flex items-center gap-4 text-white/40">
                            <div class="flex text-red text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span>(4.8 • 120 reviews)</span>
                        </div>
                    </div>

                    <div class="text-5xl font-heading font-black text-red">
                        Ksh {{ product.price.toLocaleString() }}
                    </div>

                    <p class="text-lg text-white/60 leading-relaxed">
                        {{ product.description }}
                    </p>

                    <div v-if="product.specifications" class="bg-white/5 rounded-2xl p-6 border border-white/5">
                        <h3 class="font-bold mb-4 uppercase tracking-widest text-xs text-white/40">Technical Specs</h3>
                        <div class="grid grid-cols-2 gap-y-4">
                            <div v-for="(val, key) in product.specifications" :key="key">
                                <div class="text-white/30 text-xs uppercase font-bold">{{ key }}</div>
                                <div class="font-semibold">{{ val }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <div class="flex items-center bg-charcoal-light border border-white/10 rounded-2xl p-1 w-fit">
                            <button @click="quantity > 1 ? quantity-- : null" class="w-12 h-12 flex items-center justify-center hover:bg-white/5 rounded-xl transition">-</button>
                            <input v-model="quantity" type="number" class="bg-transparent border-none focus:ring-0 w-16 text-center font-bold" />
                            <button @click="quantity++" class="w-12 h-12 flex items-center justify-center hover:bg-white/5 rounded-xl transition">+</button>
                        </div>
                        <button @click="addToCart" class="flex-1 bg-red hover:bg-red-light text-white font-black py-4 rounded-2xl transition shadow-xl shadow-red/20 text-lg">
                            Add to Cart
                        </button>
                        <button class="w-16 h-16 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl flex items-center justify-center transition">
                            <i class="far fa-heart text-xl"></i>
                        </button>
                    </div>

                    <div class="pt-8 border-t border-white/5 flex items-center gap-8 text-sm text-white/40">
                        <div class="flex items-center gap-2"><i class="fas fa-truck text-red"></i> Express Delivery</div>
                        <div class="flex items-center gap-2"><i class="fas fa-shield-alt text-red"></i> 2 Year Warranty</div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <section class="mt-32">
                <h2 class="text-3xl font-heading font-black mb-12">Related <span class="text-red">Hardware</span></h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <Link v-for="rp in relatedProducts" :key="rp.id" :href="route('products.show', rp.slug)" class="group bg-charcoal-light border border-white/5 rounded-3xl p-6 hover:border-red/50 transition duration-500">
                        <div class="aspect-video bg-charcoal rounded-2xl mb-6 overflow-hidden">
                            <img :src="rp.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=400'" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-80" />
                        </div>
                        <h3 class="font-bold text-lg mb-2 group-hover:text-red transition">{{ rp.name }}</h3>
                        <div class="text-red font-black">Ksh {{ rp.price.toLocaleString() }}</div>
                    </Link>
                </div>
            </section>
        </main>

        <Footer />
        <CartSidebar />
    </div>
</template>


<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
