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
            class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
        ></div>

        <div class="absolute inset-y-0 right-0 max-w-md w-full flex">
            <div class="relative w-screen max-w-md bg-charcoal border-l border-white/10 shadow-2xl flex flex-col">
                <!-- Header -->
                <div class="p-8 border-b border-white/5 flex items-center justify-between">
                    <h2 class="text-2xl font-heading font-black">Your <span class="text-red">Cart</span></h2>
                    <button @click="cart.toggleCart" class="w-10 h-10 rounded-xl hover:bg-white/5 transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Items -->
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <div v-if="cart.items.length === 0" class="flex flex-col items-center justify-center h-full text-white/20">
                        <i class="fas fa-shopping-basket text-6xl mb-4"></i>
                        <p class="font-bold">Your cart is empty</p>
                    </div>

                    <div v-for="item in cart.items" :key="item.id" class="flex gap-4 bg-white/5 p-4 rounded-2xl border border-white/5 group">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-charcoal-dark shrink-0">
                            <img :src="item.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=200'" class="w-full h-full object-cover" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold truncate group-hover:text-red transition">{{ item.name }}</h4>
                            <p class="text-red font-black text-sm mb-2">Ksh {{ formatPrice(item.price) }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center bg-charcoal rounded-lg p-1 border border-white/10">
                                    <button @click="cart.updateQuantity(item.id, item.quantity - 1)" class="w-6 h-6 flex items-center justify-center hover:bg-white/5 rounded">-</button>
                                    <span class="w-8 text-center text-xs font-bold">{{ item.quantity }}</span>
                                    <button @click="cart.updateQuantity(item.id, item.quantity + 1)" class="w-6 h-6 flex items-center justify-center hover:bg-white/5 rounded">+</button>
                                </div>
                                <button @click="cart.removeItem(item.id)" class="text-white/20 hover:text-red-500 transition">
                                    <i class="far fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-8 border-t border-white/10 space-y-6 bg-charcoal-light">
                    <div class="flex items-center justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-red text-2xl font-black">Ksh {{ formatPrice(cart.totalPrice) }}</span>
                    </div>
                    
                    <Link 
                        :href="route('checkout')"
                        v-if="cart.items.length > 0"
                        @click="cart.toggleCart"
                        class="w-full py-4 rounded-2xl bg-red hover:bg-red-light text-white font-black transition shadow-xl shadow-red/20 block text-center"
                    >
                        Checkout Now
                    </Link>

                    <button 
                        @click="cart.toggleCart"
                        class="w-full py-4 rounded-2xl bg-white/5 hover:bg-white/10 text-white font-bold transition"
                    >
                        Continue Shopping
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
