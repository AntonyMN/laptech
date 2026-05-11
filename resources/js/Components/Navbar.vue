<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCartStore } from '../Stores/cart';
import CartSidebar from './CartSidebar.vue';

const cart = useCartStore();
const isMobileMenuOpen = ref(false);

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
</script>

<template>
    <CartSidebar />
    <nav class="sticky top-0 z-50 bg-charcoal/80 backdrop-blur-md border-b border-white/10 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo -->
            <Link :href="route('welcome')" class="flex items-center gap-2 group">
                <img src="/images/logo.png" alt="Laptech Logo" class="h-12 w-auto p-2 rounded-xl bg-white transition group-hover:scale-105 duration-500">
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
                <button @click="toggleMobileMenu" class="md:hidden text-2xl hover:text-red transition">
                    <i class="fas" :class="isMobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div v-if="isMobileMenuOpen" class="md:hidden absolute top-full left-0 w-full bg-charcoal border-b border-white/10 p-6 space-y-6 animate-in slide-in-from-top duration-300">
            <div class="flex flex-col gap-4 font-semibold">
                <Link :href="route('products.index')" @click="isMobileMenuOpen = false" class="hover:text-red transition">Hardware Hub</Link>
                <Link :href="route('services.index')" @click="isMobileMenuOpen = false" class="hover:text-red transition">Service Hub</Link>
                <Link :href="route('quotes.create')" @click="isMobileMenuOpen = false" class="hover:text-red transition">Request Quote</Link>
            </div>
            
            <div class="pt-6 border-t border-white/10">
                <div v-if="$page.props.auth.user" class="space-y-4">
                    <p class="text-white/50 text-sm uppercase tracking-widest font-black">Account</p>
                    <Link :href="route('dashboard')" @click="isMobileMenuOpen = false" class="block bg-red hover:bg-red-light px-6 py-3 rounded-xl font-bold transition text-center">
                        Intelligence Hub
                    </Link>
                </div>
                <div v-else class="flex flex-col gap-4">
                    <Link :href="route('login')" @click="isMobileMenuOpen = false" class="block w-full py-3 rounded-xl border border-white/10 text-center font-bold">Log in</Link>
                    <Link :href="route('register')" @click="isMobileMenuOpen = false" class="block w-full py-3 rounded-xl bg-red text-center font-bold">Join Hub</Link>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
