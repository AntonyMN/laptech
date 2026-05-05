<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-charcoal text-white font-sans flex">
        <!-- Sidebar Navigation -->
        <aside class="w-20 lg:w-72 border-r border-white/5 bg-charcoal-dark flex flex-col transition-all duration-500">
            <div class="p-6 lg:p-8 mb-8">
                <Link :href="route('welcome')" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-red rounded-xl flex items-center justify-center transition group-hover:scale-110 duration-500 shadow-lg shadow-red/20">
                        <i class="fas fa-microchip text-white text-lg"></i>
                    </div>
                    <span class="hidden lg:block text-xl font-heading font-extrabold tracking-tighter uppercase">
                        Lap<span class="text-red">tech</span>
                    </span>
                </Link>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <Link :href="route('dashboard')" :class="route().current('dashboard') ? 'bg-red text-white shadow-lg shadow-red/20' : 'text-white/40 hover:bg-white/5 hover:text-white'" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition group">
                    <i class="fas fa-th-large w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Dashboard</span>
                </Link>
                <Link :href="route('welcome')" class="flex items-center gap-4 px-4 py-4 rounded-2xl text-white/40 hover:bg-white/5 hover:text-white transition group">
                    <i class="fas fa-shopping-cart w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Hardware Hub</span>
                </Link>
                <Link :href="route('services.index')" :class="route().current('services.index') ? 'bg-red text-white' : 'text-white/40 hover:bg-white/5 hover:text-white'" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition group">
                    <i class="fas fa-tools w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Service Hub</span>
                </Link>

                <Link :href="route('profile.edit')" :class="route().current('profile.edit') ? 'bg-red text-white' : 'text-white/40 hover:bg-white/5 hover:text-white'" class="flex items-center gap-4 px-4 py-4 rounded-2xl transition group">
                    <i class="fas fa-user-cog w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Settings</span>
                </Link>
            </nav>

            <div class="p-4 lg:p-8 mt-auto">
                <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center gap-4 px-4 py-4 text-red-400 hover:bg-red-400/10 rounded-2xl transition group">
                    <i class="fas fa-sign-out-alt w-6 text-center text-lg"></i>
                    <span class="hidden lg:block font-bold">Initialize Logout</span>
                </Link>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <header class="h-20 border-b border-white/5 flex items-center justify-between px-8 bg-charcoal/50 backdrop-blur-xl z-20">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-heading font-black hidden sm:block">
                        <slot name="header" />
                    </h2>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex flex-col items-end hidden sm:flex">
                        <span class="text-sm font-bold">{{ $page.props.auth.user.name }}</span>
                        <span class="text-[10px] text-red font-black uppercase tracking-widest">Active Session</span>
                    </div>
                    <div class="w-10 h-10 bg-red/20 rounded-xl flex items-center justify-center border border-red/30">
                        <i class="fas fa-user text-red text-sm"></i>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8 lg:p-12 custom-scrollbar">
                <div class="max-w-6xl mx-auto">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(227, 27, 35, 0.3);
}
</style>
