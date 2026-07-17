<script setup>
import { useCartStore } from '../Stores/cart';

import { Link } from '@inertiajs/vue3';

const cart = useCartStore();

const formatPrice = (price) => {
    return price.toLocaleString();
};
</script>

<template>
    <div
        v-if="cart.isOpen"
        class="fixed inset-0 z-[100] overflow-hidden"
    >
        <!-- Backdrop -->
        <div
            @click="cart.toggleCart"
            class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"
        ></div>

        <div class="absolute inset-y-0 right-0 max-w-md w-full flex">
            <div class="relative w-screen max-w-md bg-surface border-l border-line shadow-2xl flex flex-col">
                <!-- Header -->
                <div class="p-6 border-b border-line flex items-center justify-between">
                    <h2 class="text-xl font-heading font-extrabold text-ink">Your <span class="text-red">Cart</span></h2>
                    <button @click="cart.toggleCart" class="w-10 h-10 rounded-lg hover:bg-surface-muted text-ink transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Items -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <div v-if="cart.items.length === 0" class="flex flex-col items-center justify-center h-full text-muted">
                        <i class="fas fa-shopping-basket text-5xl mb-4"></i>
                        <p class="font-bold">Your cart is empty</p>
                    </div>

                    <div v-for="item in cart.items" :key="item.id" class="flex gap-4 bg-surface-muted p-3 rounded-xl border border-line group">
                        <div class="w-[72px] h-[72px] rounded-lg overflow-hidden bg-surface shrink-0">
                            <img :src="item.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=200'" class="w-full h-full object-cover" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-sm text-ink truncate group-hover:text-red transition">{{ item.name }}</h4>
                            <p class="text-red font-extrabold text-sm mb-2">Ksh {{ formatPrice(item.price) }}</p>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center bg-surface rounded-lg p-1 border border-line">
                                    <button @click="cart.updateQuantity(item.id, item.quantity - 1)" class="w-6 h-6 flex items-center justify-center hover:bg-surface-muted rounded text-ink">-</button>
                                    <span class="w-8 text-center text-xs font-bold text-ink">{{ item.quantity }}</span>
                                    <button @click="cart.updateQuantity(item.id, item.quantity + 1)" class="w-6 h-6 flex items-center justify-center hover:bg-surface-muted rounded text-ink">+</button>
                                </div>
                                <button @click="cart.removeItem(item.id)" class="text-muted hover:text-red transition">
                                    <i class="far fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-line space-y-4 bg-surface-muted">
                    <div class="flex items-center justify-between text-base font-bold text-ink">
                        <span>Total</span>
                        <span class="text-red text-xl font-extrabold">Ksh {{ formatPrice(cart.totalPrice) }}</span>
                    </div>

                    <Link
                        :href="route('checkout')"
                        v-if="cart.items.length > 0"
                        @click="cart.toggleCart"
                        class="w-full py-3.5 rounded-xl bg-red hover:bg-red-dark text-white font-bold transition shadow-lg shadow-red/20 block text-center"
                    >
                        Checkout Now
                    </Link>

                    <button
                        @click="cart.toggleCart"
                        class="w-full py-3.5 rounded-xl bg-surface border border-line hover:bg-surface-muted text-ink font-bold transition"
                    >
                        Continue Shopping
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
