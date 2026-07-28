<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useTheme } from '../composables/useTheme';

const showingNavigationDropdown = ref(false);
const { isDark, initTheme, toggleTheme } = useTheme();
onMounted(initTheme);
</script>

<template>
    <div class="min-h-screen bg-page text-ink font-sans flex">
        <!-- Sidebar Navigation -->
        <aside class="w-20 lg:w-64 border-r border-line bg-surface flex flex-col">
            <div class="p-5 lg:p-6 mb-4">
                <Link :href="route('welcome')" class="flex items-center gap-3 group">
                    <img src="/images/logo.png" alt="Laptech" class="h-10 w-auto transition group-hover:scale-105 duration-300">
                </Link>
            </div>

            <nav class="flex-1 px-3 space-y-1">
                <Link :href="route('dashboard')" :class="route().current('dashboard') ? 'bg-red text-white shadow-lg shadow-red/20' : 'text-muted hover:bg-surface-muted hover:text-ink'" class="flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fas fa-th-large w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Dashboard</span>
                </Link>
                <Link :href="route('products.index')" class="flex items-center gap-4 px-4 py-3 rounded-xl text-muted hover:bg-surface-muted hover:text-ink transition">
                    <i class="fas fa-shopping-cart w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Shop</span>
                </Link>
                <Link :href="route('services.index')" :class="route().current('services.index') ? 'bg-red text-white' : 'text-muted hover:bg-surface-muted hover:text-ink'" class="flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fas fa-tools w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Services</span>
                </Link>

                <Link v-if="$page.props.auth.user.is_admin" :href="route('admin.dashboard')" class="flex items-center gap-4 px-4 py-3 rounded-xl text-red hover:bg-red/10 transition">
                    <i class="fas fa-user-shield w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Admin</span>
                </Link>

                <Link :href="route('profile.edit')" :class="route().current('profile.edit') ? 'bg-red text-white' : 'text-muted hover:bg-surface-muted hover:text-ink'" class="flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fas fa-user-cog w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Settings</span>
                </Link>
            </nav>

            <div class="p-3 lg:p-5 mt-auto">
                <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center gap-4 px-4 py-3 text-red hover:bg-red/10 rounded-xl transition">
                    <i class="fas fa-sign-out-alt w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Log out</span>
                </Link>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <header class="h-16 border-b border-line flex items-center justify-between px-6 bg-surface z-20">
                <h2 class="text-lg font-heading font-bold hidden sm:block">
                    <slot name="header" />
                </h2>

                <div class="flex items-center gap-4">
                    <button @click="toggleTheme" :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'" :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'" class="w-10 h-10 flex items-center justify-center text-muted hover:text-red rounded-xl hover:bg-surface-muted transition">
                        <i class="fas" :class="isDark ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    <div class="flex flex-col items-end hidden sm:flex">
                        <span class="text-sm font-bold">{{ $page.props.auth.user.name }}</span>
                        <span class="text-[10px] text-red font-bold uppercase tracking-widest">Active</span>
                    </div>
                    <div class="w-10 h-10 bg-red/10 rounded-xl flex items-center justify-center border border-red/20">
                        <i class="fas fa-user text-red text-sm"></i>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-10">
                <div class="max-w-6xl mx-auto">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>
