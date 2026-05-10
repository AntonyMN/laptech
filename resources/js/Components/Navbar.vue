<script setup>
import { Link } from '@inertiajs/vue3';
import { useCartStore } from '../Stores/cart';

const cart = useCartStore();

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <nav class="sticky top-0 z-50 bg-charcoal/80 backdrop-blur-md border-b border-white/10 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <Link :href="route('welcome')" class="flex items-center gap-2 group">
                <img src="/images/logo.png" alt="Laptech Logo" class="h-12 w-auto transition group-hover:scale-105 duration-500">
            </Link>

            <!-- Main Nav -->
            <div class="hidden md:flex items-center gap-8 font-semibold">
                <Link :href="route('products.index')" :class="route().current('products.index') ? 'text-red' : 'hover:text-red'" class="transition">Hardware Hub</Link>
                <Link :href="route('services.index')" :class="route().current('services.index') ? 'text-red' : 'hover:text-red'" class="transition">Service Hub</Link>
                <Link :href="route('quotes.create')" :class="route().current('quotes.create') ? 'text-red' : 'hover:text-red'" class="transition">Request Quote</Link>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <!-- Cart -->
                <div @click="cart.toggleCart" class="relative cursor-pointer group p-2 hover:bg-white/5 rounded-xl transition">
                    <i class="fas fa-shopping-cart text-xl group-hover:text-red transition"></i>
                    <span v-if="cart.totalItems > 0" class="absolute -top-1 -right-1 bg-red text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-lg">
                        {{ cart.totalItems }}
                    </span>
                </div>

                <!-- Auth -->
                <div v-if="canLogin" class="hidden sm:block">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="bg-red hover:bg-red-light px-6 py-2 rounded-full font-bold transition">
                        Intelligence Hub
                    </Link>
                    <template v-else>
                        <Link :href="route('login')" class="hover:text-red transition font-bold">Log in</Link>
                        <Link v-if="canRegister" :href="route('register')" class="ml-4 bg-red hover:bg-red-light px-6 py-2 rounded-full font-bold transition">
                            Join Hub
                        </Link>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-2xl hover:text-red transition">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
