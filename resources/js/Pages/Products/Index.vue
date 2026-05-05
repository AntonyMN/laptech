<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import CartSidebar from '../../Components/CartSidebar.vue';


const props = defineProps({
    products: Array,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');
const min_price = ref(props.filters.min_price || '');
const max_price = ref(props.filters.max_price || '');

const applyFilters = () => {
    router.get(route('products.index'), {
        search: search.value,
        category: category.value,
        min_price: min_price.value,
        max_price: max_price.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

watch([search, category, min_price, max_price], () => {
    // debounce would be better, but for now:
    // applyFilters(); 
});

const clearFilters = () => {
    search.value = '';
    category.value = '';
    min_price.value = '';
    max_price.value = '';
    applyFilters();
};
</script>

<template>
    <Head title="Browse Hardware - Laptech" />

    <div class="min-h-screen bg-charcoal text-white font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="max-w-7xl mx-auto py-12 px-6">
            <!-- Search Section -->
            <div class="mb-16 text-center max-w-2xl mx-auto">
                <h1 class="text-5xl font-heading font-black mb-6 italic tracking-tighter">Hardware <span class="text-red">Hub.</span></h1>
                <p class="text-white/40 font-bold mb-10">Discover cutting-edge inventory for high-performance ecosystems.</p>
                <div class="relative group">
                    <input 
                        v-model="search" 
                        @keyup.enter="applyFilters"
                        type="text" 
                        placeholder="Search high-end hardware..." 
                        class="w-full bg-white/5 border-white/10 rounded-2xl px-14 py-5 focus:border-red focus:ring-red transition text-lg"
                    />
                    <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-white/20 group-focus-within:text-red transition"></i>
                    <button @click="applyFilters" class="absolute right-3 top-1/2 -translate-y-1/2 bg-red hover:bg-red-light px-6 py-2 rounded-xl font-bold transition shadow-lg shadow-red/20">
                        Discover
                    </button>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Filters Sidebar -->
                <aside class="w-full lg:w-72 shrink-0 space-y-10">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-white/20 mb-6">Categories</h3>
                        <div class="space-y-2">
                            <button 
                                @click="category = ''; applyFilters()"
                                :class="category === '' ? 'bg-red text-white' : 'hover:bg-white/5 text-white/40 hover:text-white'"
                                class="w-full text-left px-4 py-3 rounded-xl transition font-bold text-sm"
                            >
                                All Categories
                            </button>
                            <button 
                                v-for="cat in categories" 
                                :key="cat.id"
                                @click="category = cat.id; applyFilters()"
                                :class="category === cat.id ? 'bg-red text-white' : 'hover:bg-white/5 text-white/40 hover:text-white'"
                                class="w-full text-left px-4 py-3 rounded-xl transition font-bold text-sm"
                            >
                                {{ cat.name }}
                            </button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-white/20 mb-6">Price Range</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <input v-model="min_price" type="number" placeholder="Min" class="bg-white/5 border-white/10 rounded-xl px-4 py-2 text-sm focus:border-red focus:ring-red" />
                            <input v-model="max_price" type="number" placeholder="Max" class="bg-white/5 border-white/10 rounded-xl px-4 py-2 text-sm focus:border-red focus:ring-red" />
                        </div>
                        <button @click="applyFilters" class="w-full mt-4 bg-white/5 hover:bg-white/10 py-2 rounded-xl text-xs font-bold transition uppercase tracking-widest">Apply Range</button>
                    </div>

                    <button @click="clearFilters" class="w-full py-4 border border-white/5 rounded-2xl text-xs font-bold text-white/20 hover:text-red-400 hover:border-red-400/20 transition uppercase tracking-widest">
                        Reset All Filters
                    </button>
                </aside>

                <!-- Results Grid -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="text-3xl font-heading font-black">Hardware <span class="text-red">Collection</span></h2>
                        <div class="text-white/20 text-sm font-bold">{{ products.length }} items discovered</div>
                    </div>

                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-8">
                        <div v-for="product in products" :key="product.id" class="group bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden hover:border-red/50 transition duration-500 shadow-2xl">
                            <div class="aspect-square relative overflow-hidden bg-charcoal">
                                <img :src="product.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=400'" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-80" />
                                <div class="absolute inset-0 bg-gradient-to-t from-charcoal via-transparent to-transparent opacity-60"></div>
                                <div class="absolute top-6 left-6">
                                    <span class="px-3 py-1 bg-red/20 backdrop-blur-md border border-red/30 rounded-full text-[10px] font-black text-red uppercase tracking-widest">
                                        {{ product.category?.name }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-8">
                                <Link :href="route('products.show', product.slug)">
                                    <h3 class="text-xl font-bold mb-2 group-hover:text-red transition">{{ product.name }}</h3>
                                </Link>
                                <div class="text-2xl font-black text-red mb-6">Ksh {{ product.price.toLocaleString() }}</div>
                                <Link :href="route('products.show', product.slug)" class="block w-full text-center py-4 rounded-2xl bg-white/5 hover:bg-red text-white font-bold transition group-hover:shadow-lg group-hover:shadow-red/20">
                                    View Specification
                                </Link>
                            </div>
                        </div>

                        <div v-if="products.length === 0" class="col-span-full py-32 text-center">
                            <i class="fas fa-search text-6xl text-white/5 mb-6"></i>
                            <p class="text-white/20 font-bold text-xl uppercase tracking-widest italic">No hardware matches your parameters</p>
                            <button @click="clearFilters" class="mt-6 text-red font-black hover:underline">Clear all filters</button>
                        </div>
                    </div>
                </div>

            </div>
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
