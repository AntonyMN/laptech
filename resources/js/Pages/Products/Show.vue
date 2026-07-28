<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';

import { ref, computed } from 'vue';
import { useCartStore } from '../../Stores/cart';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import ProductCard from '../../Components/ProductCard.vue';


const cart = useCartStore();
const page = usePage();

const props = defineProps({
    product: Object,
    relatedProducts: Array,
});

const seo = computed(() => {
    let baseUrl = page.props.appUrl || 'https://laptech.co.ke';
    if (baseUrl.includes('localhost') && typeof window !== 'undefined') {
        baseUrl = window.location.origin;
    } else if (baseUrl.includes('localhost')) {
        baseUrl = 'https://laptech.co.ke';
    }
    if (baseUrl.endsWith('/')) {
        baseUrl = baseUrl.slice(0, -1);
    }

    const imagePath = props.product.image
        ? (props.product.image.startsWith('/') ? props.product.image : `/${props.product.image}`)
        : '/favicon.png';

    const productImage = imagePath.startsWith('http') ? imagePath : `${baseUrl}${imagePath}`;

    return {
        title: `${props.product.name} — Laptech Electronics`,
        description: (props.product.description || '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim().substring(0, 160) || `Buy ${props.product.name} at Laptech Electronics, Nairobi. Quality hardware with nationwide delivery.`,
        url: `${baseUrl}/products/${props.product.slug}`,
        image: productImage,
    };
});

const quantity = ref(1);

const addToCart = () => {
    cart.addItem(props.product, quantity.value);
};

const activeImage = ref(props.product.images?.[0] || props.product.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=800');

const getStatusClasses = (status) => {
    switch (status) {
        case 'Brand new':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'Ex-UK':
            return 'bg-purple-50 text-purple-700 border-purple-200';
        case 'Certified Refurbished':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        default:
            return 'bg-surface-muted text-muted border-line';
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
        <meta property="og:type" content="product" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="seo.title" />
        <meta name="twitter:description" :content="seo.description" />
        <meta name="twitter:image" :content="seo.image" />
    </Head>

    <div class="min-h-screen bg-page text-ink font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="max-w-7xl mx-auto py-10 px-6">
            <nav class="text-xs text-muted mb-6">
                <Link :href="route('welcome')" class="hover:text-red">Home</Link>
                <span class="mx-2">/</span>
                <Link :href="route('products.index')" class="hover:text-red">Shop</Link>
                <span class="mx-2">/</span>
                <span class="text-ink font-semibold">{{ product.name }}</span>
            </nav>

            <div class="grid lg:grid-cols-2 gap-12 items-start">
                <!-- Product Image -->
                <div class="space-y-4">
                    <div class="bg-surface border border-line rounded-2xl overflow-hidden aspect-square">
                        <img
                            :src="activeImage"
                            :alt="product.name"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <!-- Thumbnails -->
                    <div v-if="product.images && product.images.length > 0" class="grid grid-cols-5 gap-3">
                        <div
                            v-for="(img, idx) in product.images"
                            :key="idx"
                            @click="activeImage = img"
                            :class="activeImage === img ? 'border-red' : 'border-line opacity-60 hover:opacity-100'"
                            class="aspect-square bg-surface border rounded-lg cursor-pointer transition overflow-hidden"
                        >
                            <img :src="img" class="w-full h-full object-cover" />
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="space-y-6">
                    <div>
                        <div class="flex flex-wrap gap-2 mb-3 items-center">
                            <span class="px-3 py-1 bg-red/10 text-red rounded-full text-xs font-bold uppercase tracking-widest inline-block">
                                {{ product.category?.name }}
                            </span>
                            <span v-if="product.status" :class="getStatusClasses(product.status)" class="px-3 py-1 border rounded-full text-xs font-bold uppercase tracking-widest inline-block">
                                {{ product.status }}
                            </span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-heading font-extrabold mb-3">{{ product.name }}</h1>
                        <div class="flex items-center gap-3 text-muted text-sm">
                            <div class="flex text-red">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span>(4.8 • 120 reviews)</span>
                        </div>
                    </div>

                    <div class="text-4xl font-heading font-extrabold text-ink">
                        Ksh {{ product.price.toLocaleString() }}
                    </div>

                    <div class="rich-text text-muted leading-relaxed" v-html="product.description"></div>

                    <div v-if="product.specifications" class="bg-surface rounded-2xl p-6 border border-line">
                        <h3 class="font-bold mb-4 uppercase tracking-widest text-xs text-muted">Technical Specs</h3>
                        <div class="grid grid-cols-2 gap-y-4">
                            <div v-for="(val, key) in product.specifications" :key="key">
                                <div class="text-muted text-xs uppercase font-bold">{{ key }}</div>
                                <div class="font-semibold text-ink">{{ val }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <div class="flex items-center bg-surface border border-line rounded-xl p-1 w-fit">
                            <button @click="quantity > 1 ? quantity-- : null" class="w-11 h-11 flex items-center justify-center hover:bg-surface-muted rounded-lg transition">-</button>
                            <input v-model="quantity" type="number" class="bg-transparent border-none focus:ring-0 w-14 text-center font-bold text-ink" />
                            <button @click="quantity++" class="w-11 h-11 flex items-center justify-center hover:bg-surface-muted rounded-lg transition">+</button>
                        </div>
                        <button @click="addToCart" class="flex-1 bg-red hover:bg-red-dark text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-red/20">
                            <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                        </button>
                        <button v-if="$page.props.auth.user" @click="router.post(route('wishlist.toggle', product.id))" class="w-14 h-14 bg-surface hover:bg-red hover:text-white border border-line rounded-xl flex items-center justify-center transition" title="Add to Wishlist">
                            <i class="far fa-heart text-lg"></i>
                        </button>
                        <button @click="router.post(route('compare.add', product.id))" class="w-14 h-14 bg-surface hover:bg-red hover:text-white border border-line rounded-xl flex items-center justify-center transition" title="Add to Compare">
                            <i class="fas fa-balance-scale text-lg"></i>
                        </button>
                    </div>

                    <div class="pt-6 border-t border-line flex flex-wrap items-center gap-6 text-sm text-muted">
                        <div class="flex items-center gap-2"><i class="fas fa-truck text-red"></i> Nationwide Delivery</div>
                        <div class="flex items-center gap-2"><i class="fas fa-shield-alt text-red"></i> Genuine Warranty</div>
                        <div class="flex items-center gap-2"><i class="fas fa-undo text-red"></i> 7-Day Returns</div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <section v-if="relatedProducts?.length" class="mt-20">
                <h2 class="text-2xl font-heading font-extrabold mb-6">Related Products</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                    <ProductCard v-for="rp in relatedProducts" :key="rp.id" :product="rp" />
                </div>
            </section>
        </main>

        <Footer />
    </div>
</template>

<style scoped>
/* Rich-text (WYSIWYG) product descriptions rendered via v-html */
.rich-text :deep(h1),
.rich-text :deep(h2),
.rich-text :deep(h3) {
    color: var(--color-ink);
    font-family: var(--font-heading);
    font-weight: 700;
    line-height: 1.25;
    margin: 1.25rem 0 0.5rem;
}
.rich-text :deep(h1) { font-size: 1.5rem; }
.rich-text :deep(h2) { font-size: 1.25rem; }
.rich-text :deep(h3) { font-size: 1.1rem; }
.rich-text :deep(p) { margin: 0.5rem 0; }
.rich-text :deep(ul),
.rich-text :deep(ol) { margin: 0.5rem 0 0.5rem 1.25rem; }
.rich-text :deep(ul) { list-style: disc; }
.rich-text :deep(ol) { list-style: decimal; }
.rich-text :deep(li) { margin: 0.25rem 0; }
.rich-text :deep(a) { color: var(--color-red); text-decoration: underline; }
.rich-text :deep(strong) { color: var(--color-ink); font-weight: 700; }
.rich-text :deep(blockquote) {
    border-left: 3px solid var(--color-red);
    padding-left: 1rem;
    margin: 0.75rem 0;
    font-style: italic;
}
.rich-text :deep(img) { max-width: 100%; border-radius: 0.75rem; margin: 0.75rem 0; }
.rich-text :deep(pre) {
    background: var(--color-surface-muted);
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 0.75rem 0;
}
</style>
