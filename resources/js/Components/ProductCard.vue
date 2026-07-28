<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { useCartStore } from '../Stores/cart';

const cart = useCartStore();
const page = usePage();

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const placeholder = 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=500';

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

const addToCart = () => cart.addItem(props.product);
const toggleWishlist = () => router.post(route('wishlist.toggle', props.product.id), {}, { preserveScroll: true });
const addToCompare = () => router.post(route('compare.add', props.product.id), {}, { preserveScroll: true });
</script>

<template>
    <div class="group relative gradient-ring flex flex-col bg-surface border border-line rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-black/5 hover:border-red/40 transition duration-300">
        <!-- Image -->
        <Link :href="route('products.show', product.slug)" class="block relative aspect-square overflow-hidden bg-surface-muted">
            <img
                :src="product.image || placeholder"
                :alt="product.name"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
            />
            <!-- Status badge -->
            <span
                v-if="product.status"
                :class="getStatusClasses(product.status)"
                class="absolute top-3 left-3 px-2.5 py-1 border rounded-full text-[10px] font-bold uppercase tracking-wide"
            >
                {{ product.status }}
            </span>

            <!-- Quick actions -->
            <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition duration-300">
                <button
                    v-if="page.props.auth?.user"
                    @click.prevent="toggleWishlist"
                    title="Add to wishlist"
                    class="w-9 h-9 bg-surface/90 backdrop-blur border border-line text-ink rounded-full flex items-center justify-center hover:bg-red hover:text-white hover:border-red transition"
                >
                    <i class="fas fa-heart text-xs"></i>
                </button>
                <button
                    @click.prevent="addToCompare"
                    title="Compare"
                    class="w-9 h-9 bg-surface/90 backdrop-blur border border-line text-ink rounded-full flex items-center justify-center hover:bg-red hover:text-white hover:border-red transition"
                >
                    <i class="fas fa-balance-scale text-xs"></i>
                </button>
            </div>
        </Link>

        <!-- Body -->
        <div class="flex flex-col flex-1 p-4">
            <span class="text-[11px] font-semibold uppercase tracking-wide text-red mb-1">{{ product.category?.name }}</span>
            <Link :href="route('products.show', product.slug)" class="block">
                <h3 class="text-sm font-heading font-bold text-ink leading-snug line-clamp-2 min-h-[2.5rem] group-hover:text-red transition">
                    {{ product.name }}
                </h3>
            </Link>

            <div class="mt-3 flex items-end justify-between gap-2">
                <span class="text-lg font-heading font-extrabold text-ink">Ksh {{ product.price.toLocaleString() }}</span>
                <button
                    @click="addToCart"
                    title="Add to cart"
                    class="shrink-0 inline-flex items-center gap-1.5 bg-red hover:bg-red-dark text-white text-xs font-bold px-3 py-2 rounded-lg transition shadow-sm"
                >
                    <i class="fas fa-cart-plus"></i>
                    <span class="hidden sm:inline">Add</span>
                </button>
            </div>
        </div>
    </div>
</template>
