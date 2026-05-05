<script setup>
import { Head, Link, router } from '@inertiajs/vue3';


import { ref, computed } from 'vue';
import { useCartStore } from '../Stores/cart';
import CartSidebar from '../Components/CartSidebar.vue';
import Footer from '../Components/Footer.vue';



import Navbar from '../Components/Navbar.vue';

const cart = useCartStore();


const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    categories: Array,
    featuredProducts: Array,
    featuredServices: Array,
});

const searchQuery = ref('');

const filteredCategories = computed(() => {
    if (!searchQuery.value) return props.categories;
    return props.categories.filter(cat => 
        cat.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const searchProducts = () => {
    if (searchQuery.value.trim()) {
        router.get(route('products.index'), { search: searchQuery.value });
    }
};

const filterByCategory = (id) => {
    router.get(route('products.index'), { category: id });
};


</script>

<template>
    <Head title="Innovative Tech Hub - Laptech" />
    
    <div class="min-h-screen bg-charcoal text-white font-sans selection:bg-red selection:text-white">
        <!-- Navigation -->
        <Navbar :canLogin="canLogin" :canRegister="canRegister" />

        <!-- Hero Section -->
        <header class="relative overflow-hidden pt-16 pb-32 px-6">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_50%_50%,rgba(227,27,35,0.15),transparent_70%)]"></div>
            
            <div class="max-w-7xl mx-auto relative z-10 grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block px-4 py-1 rounded-full bg-red/20 text-red-bright font-bold text-sm mb-6 uppercase tracking-widest">
                        Future-Ready Solutions
                    </span>
                    <h1 class="text-6xl md:text-7xl font-heading font-extrabold leading-none mb-8">
                        The Innovative <br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red to-red-bright">Tech Hub.</span>
                    </h1>
                    <p class="text-xl text-white/70 mb-10 max-w-lg leading-relaxed">
                        Discover cutting-edge hardware and seamless IT services designed for high-performance environments.
                    </p>

                    <!-- Search Bar -->
                    <div class="flex items-center bg-white/5 border border-white/10 rounded-2xl p-2 max-w-xl group focus-within:border-red/50 transition">
                        <i class="fas fa-search ml-4 text-white/30 group-focus-within:text-red"></i>
                        <input 
                            v-model="searchQuery"
                            @keyup.enter="searchProducts"
                            type="text" 
                            placeholder="Search high-end hardware or services..." 
                            class="bg-transparent border-none focus:ring-0 flex-1 px-4 py-3 text-lg placeholder:text-white/20"
                        />
                        <button @click="searchProducts" class="bg-red hover:bg-red-light text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-red/20">
                            Search
                        </button>
                    </div>

                    <!-- Hero Categories -->
                    <div class="mt-10 flex flex-wrap gap-3">
                        <span 
                            v-for="cat in categories" 
                            :key="cat.id" 
                            @click="filterByCategory(cat.id)"
                            class="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-sm border border-white/5 cursor-pointer transition"
                        >
                            {{ cat.name }}
                        </span>
                    </div>


                </div>

                <div class="relative">
                    <div class="absolute -inset-4 bg-red/20 blur-3xl rounded-full"></div>
                    <Link :href="route('products.show', 'next-gen-server-rack')" class="block relative bg-charcoal-light border border-white/10 rounded-3xl p-4 shadow-2xl overflow-hidden group hover:border-red transition duration-500">
                        <img 
                            src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&q=80&w=800" 
                            alt="Tech Hub" 
                            class="rounded-2xl grayscale group-hover:grayscale-0 transition-all duration-700"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-charcoal via-transparent to-transparent opacity-60"></div>
                        <div class="absolute bottom-8 left-8 right-8 p-6 bg-charcoal/80 backdrop-blur border border-white/10 rounded-2xl group-hover:bg-red/90 transition duration-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-heading font-bold text-lg">Next-Gen Server Rack</h3>
                                    <p class="text-sm text-red-bright group-hover:text-white transition">Available for Installation</p>
                                </div>
                                <i class="fas fa-arrow-right text-red group-hover:text-white transition"></i>
                            </div>
                        </div>
                    </Link>
                </div>

            </div>
        </header>

        <!-- Featured Products -->
        <section id="products" class="py-24 px-6 bg-charcoal-light">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-end justify-between mb-16">
                    <div>
                        <h2 class="text-4xl font-heading font-extrabold mb-4">Featured <span class="text-red">Products</span></h2>
                        <div class="h-1 w-20 bg-red"></div>
                    </div>
                    <Link :href="route('products.index')" class="text-red font-bold hover:underline">View All Inventory <i class="fas fa-chevron-right ml-2 text-xs"></i></Link>

                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="product in featuredProducts" :key="product.id" class="group bg-charcoal border border-white/5 rounded-3xl overflow-hidden hover:border-red/50 transition duration-500">
                        <Link :href="route('products.show', product.slug)">
                            <div class="relative h-64 bg-charcoal-dark overflow-hidden">
                                <img :src="product.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=500'" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-80 group-hover:opacity-100" />
                                <div class="absolute top-4 left-4 px-3 py-1 bg-red rounded-full text-xs font-bold uppercase tracking-tighter text-white">
                                    {{ product.category?.name }}
                                </div>
                            </div>
                        </Link>
                        <div class="p-8">
                            <Link :href="route('products.show', product.slug)">
                                <h3 class="text-2xl font-heading font-bold mb-2 group-hover:text-red transition">{{ product.name }}</h3>
                            </Link>
                            <p class="text-white/50 text-sm mb-6 line-clamp-2">{{ product.description }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-heading font-black text-white">Ksh {{ product.price.toLocaleString() }}</span>
                                <button @click="cart.addItem(product)" class="w-12 h-12 bg-white/5 hover:bg-red rounded-2xl flex items-center justify-center transition text-white">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Featured Services -->
        <section id="services" class="py-24 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-4xl font-heading font-extrabold mb-4">Expert <span class="text-red">Services</span></h2>
                    <p class="text-white/50 max-w-2xl mx-auto">Professional IT solutions delivered by certified technicians to power your growth.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <Link v-for="service in featuredServices" :key="service.id" :href="route('services.show', service.slug)" class="p-10 bg-white/5 border border-white/10 rounded-3xl hover:bg-red group transition-all duration-500 cursor-pointer block">
                        <div class="w-16 h-16 bg-red group-hover:bg-white rounded-2xl flex items-center justify-center mb-8 transition shadow-lg shadow-red/20">
                            <i :class="service.icon" class="text-white group-hover:text-red text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-heading font-bold mb-4 group-hover:text-white transition">{{ service.name }}</h3>
                        <p class="text-white/50 group-hover:text-white/80 transition leading-relaxed">
                            {{ service.description }}
                        </p>
                        <Link :href="route('quotes.create')" class="mt-8 flex items-center gap-2 text-red group-hover:text-white font-bold transition">
                            Request Quote <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                        </Link>
                    </Link>
                </div>

            </div>
        </section>

        <!-- Quote/CTA Section -->
        <section id="quote" class="py-24 px-6 relative overflow-hidden">
            <div class="max-w-5xl mx-auto bg-gradient-to-br from-red to-red-dark rounded-[3rem] p-12 md:p-20 text-center relative z-10 shadow-3xl">
                <h2 class="text-4xl md:text-5xl font-heading font-extrabold mb-8 text-white">Ready to elevate your infrastructure?</h2>
                <p class="text-xl text-white/80 mb-12 max-w-2xl mx-auto leading-relaxed">
                    Get a bespoke quote for your project within 24 hours. Our specialists are standing by.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <Link :href="route('quotes.create')" class="bg-white text-charcoal hover:bg-charcoal hover:text-white px-10 py-4 rounded-2xl font-black text-lg transition shadow-xl">
                        Get a Free Quote
                    </Link>
                    <Link :href="route('quotes.create')" class="border-2 border-white/20 hover:border-white px-10 py-4 rounded-2xl font-black text-lg transition">
                        Contact Sales
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <Footer />
        
        <!-- Cart Sidebar -->

        <CartSidebar />
    </div>
</template>


<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
.shadow-3xl {
    box-shadow: 0 35px 60px -15px rgba(227, 27, 35, 0.3);
}
</style>
