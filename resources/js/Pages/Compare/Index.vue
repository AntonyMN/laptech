<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    products: Array,
});

const remove = (id) => {
    router.delete(route('compare.remove', id));
};

const clear = () => {
    router.delete(route('compare.clear'));
};
</script>

<template>
    <Head title="Compare Products - Laptech" />

    <div class="min-h-screen bg-charcoal text-white font-sans">
        <Navbar />

        <main class="max-w-7xl mx-auto py-20 px-6">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h1 class="text-5xl font-black mb-4">Comparison <span class="text-red">Matrix</span></h1>
                    <p class="text-white/40">Side-by-side analysis of your selected hardware ecosystem.</p>
                </div>
                <button v-if="products.length > 0" @click="clear" class="text-sm font-bold text-red hover:text-red-light transition uppercase tracking-widest">
                    Clear All
                </button>
            </div>

            <div v-if="products.length === 0" class="bg-charcoal-light border border-white/5 rounded-[3rem] p-20 text-center">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-balance-scale text-white/20 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold mb-4">No products selected</h2>
                <p class="text-white/40 mb-8 max-w-md mx-auto">Add products to your comparison matrix to see technical differences and find the perfect fit.</p>
                <Link :href="route('products.index')" class="bg-red hover:bg-red-light text-white px-8 py-4 rounded-2xl font-black transition shadow-xl shadow-red/20 inline-block">
                    Browse Inventory
                </Link>
            </div>

            <div v-else class="overflow-x-auto">
                <div class="min-w-[800px] bg-charcoal-light border border-white/5 rounded-[3rem] overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-10 bg-white/5 border-b border-white/5 w-1/4">Feature</th>
                                <th v-for="product in products" :key="product.id" class="p-10 bg-white/5 border-b border-white/5 relative group">
                                    <button @click="remove(product.id)" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-red/10 text-red opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    <img :src="product.image" class="h-32 object-contain mb-6 mx-auto" />
                                    <h3 class="font-bold text-center leading-tight">{{ product.name }}</h3>
                                    <p class="text-red font-black text-center mt-2">Ksh {{ product.price.toLocaleString() }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <!-- Category -->
                            <tr>
                                <td class="p-10 font-bold uppercase text-[10px] tracking-widest text-white/30">Category</td>
                                <td v-for="product in products" :key="product.id" class="p-10 text-white/60 text-center">
                                    {{ product.category.name }}
                                </td>
                            </tr>
                            <!-- Description -->
                            <tr>
                                <td class="p-10 font-bold uppercase text-[10px] tracking-widest text-white/30">Summary</td>
                                <td v-for="product in products" :key="product.id" class="p-10 text-sm text-white/40 leading-relaxed text-center">
                                    {{ product.description.substring(0, 100) }}...
                                </td>
                            </tr>
                            <!-- Dynamic Specifications -->
                            <tr v-for="specKey in [...new Set(products.flatMap(p => Object.keys(p.specifications || {})))]" :key="specKey">
                                <td class="p-10 font-bold uppercase text-[10px] tracking-widest text-white/30">{{ specKey }}</td>
                                <td v-for="product in products" :key="product.id" class="p-10 text-white/60 text-center">
                                    {{ product.specifications?.[specKey] || '—' }}
                                </td>
                            </tr>
                            <!-- Action -->
                            <tr>
                                <td class="p-10 border-t border-white/5"></td>
                                <td v-for="product in products" :key="product.id" class="p-10 border-t border-white/5 text-center">
                                    <Link :href="route('products.show', product.slug)" class="bg-white/5 hover:bg-red text-white px-6 py-3 rounded-xl font-bold transition text-xs">
                                        View Details
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
