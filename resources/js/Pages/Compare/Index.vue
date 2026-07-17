<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const page = usePage();

const seo = computed(() => {
    const baseUrl = page.props.appUrl || '';
    return {
        title: 'Compare Products — Laptech Electronics',
        description: 'Compare laptops, desktops and accessories side by side to find the perfect fit at Laptech Electronics.',
        url: `${baseUrl}/compare`,
        image: `${baseUrl}/favicon.png`,
    };
});

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
    <Head :title="seo.title">
        <meta name="description" :content="seo.description" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:url" :content="seo.url" />
        <meta property="og:image" :content="seo.image" />
        <meta property="og:type" content="website" />
    </Head>

    <div class="min-h-screen bg-page text-ink font-sans">
        <Navbar />

        <main class="max-w-7xl mx-auto py-12 px-6">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-heading font-extrabold mb-2">Compare <span class="text-red">Products</span></h1>
                    <p class="text-muted">Side-by-side comparison of your selected products.</p>
                </div>
                <button v-if="products.length > 0" @click="clear" class="text-sm font-bold text-red hover:text-red-dark transition uppercase tracking-widest">
                    Clear All
                </button>
            </div>

            <div v-if="products.length === 0" class="bg-surface border border-line rounded-2xl p-16 text-center">
                <div class="w-20 h-20 bg-surface-muted rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-balance-scale text-muted text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold mb-3">No products selected</h2>
                <p class="text-muted mb-8 max-w-md mx-auto">Add products to compare their specs and find the perfect fit.</p>
                <Link :href="route('products.index')" class="bg-red hover:bg-red-dark text-white px-8 py-3.5 rounded-full font-bold transition inline-block">
                    Browse Products
                </Link>
            </div>

            <div v-else class="overflow-x-auto">
                <div class="min-w-[800px] bg-surface border border-line rounded-2xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-6 bg-surface-muted border-b border-line w-1/4 text-sm font-bold">Feature</th>
                                <th v-for="product in products" :key="product.id" class="p-6 bg-surface-muted border-b border-line relative group">
                                    <button @click="remove(product.id)" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-red/10 text-red hover:bg-red hover:text-white transition flex items-center justify-center">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                    <img :src="product.image" class="h-28 object-contain mb-4 mx-auto" />
                                    <h3 class="font-bold text-center leading-tight text-ink">{{ product.name }}</h3>
                                    <p class="text-red font-extrabold text-center mt-2">Ksh {{ product.price.toLocaleString() }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            <tr>
                                <td class="p-6 font-bold uppercase text-[10px] tracking-widest text-muted">Category</td>
                                <td v-for="product in products" :key="product.id" class="p-6 text-ink text-center text-sm">
                                    {{ product.category.name }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-6 font-bold uppercase text-[10px] tracking-widest text-muted">Summary</td>
                                <td v-for="product in products" :key="product.id" class="p-6 text-sm text-muted leading-relaxed text-center">
                                    {{ product.description.substring(0, 100) }}...
                                </td>
                            </tr>
                            <tr v-for="specKey in [...new Set(products.flatMap(p => Object.keys(p.specifications || {})))]" :key="specKey">
                                <td class="p-6 font-bold uppercase text-[10px] tracking-widest text-muted">{{ specKey }}</td>
                                <td v-for="product in products" :key="product.id" class="p-6 text-ink text-center text-sm">
                                    {{ product.specifications?.[specKey] || '—' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-6 border-t border-line"></td>
                                <td v-for="product in products" :key="product.id" class="p-6 border-t border-line text-center">
                                    <Link :href="route('products.show', product.slug)" class="bg-red hover:bg-red-dark text-white px-6 py-2.5 rounded-lg font-bold transition text-xs inline-block">
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
