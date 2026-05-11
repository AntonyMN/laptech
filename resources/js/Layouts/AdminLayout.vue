<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: String
});
</script>

<template>
    <Head :title="title + ' - Admin Laptech'" />

    <div class="min-h-screen bg-charcoal text-white font-sans flex">
        <!-- Sidebar -->
        <aside class="w-72 border-r border-white/5 bg-charcoal-dark p-8 flex flex-col gap-12 shrink-0">
            <Link :href="route('welcome')" class="flex flex-col gap-4 group">
                <img src="/images/logo.png" alt="Laptech Logo" class="h-12 w-auto p-2 rounded-xl bg-white transition group-hover:scale-105 duration-500">
                <span class="text-sm font-black text-red uppercase tracking-[0.2em] text-center">Admin Console</span>
            </Link>

            <nav class="space-y-2">
                <Link :href="route('admin.dashboard')" :class="route().current('admin.dashboard') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition font-bold">
                    <i class="fas fa-th-large w-6"></i> Dashboard
                </Link>
                <Link :href="route('admin.products.index')" :class="route().current('admin.products.*') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition font-bold">
                    <i class="fas fa-box w-6"></i> Products
                </Link>
                <Link :href="route('admin.product-categories.index')" :class="route().current('admin.product-categories.*') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition group">
                    <i class="fas fa-tags w-5"></i> <span class="font-bold text-sm">Product Categories</span>
                </Link>
                <Link :href="route('admin.blog-posts.index')" :class="route().current('admin.blog-posts.*') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition group">
                    <i class="fas fa-feather w-5"></i> <span class="font-bold text-sm">Blog Posts</span>
                </Link>
                <Link :href="route('admin.blog-categories.index')" :class="route().current('admin.blog-categories.*') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition group">
                    <i class="fas fa-folder-open w-5"></i> <span class="font-bold text-sm">Blog Categories</span>
                </Link>
                <Link :href="route('admin.services.index')" :class="route().current('admin.services.*') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition font-bold">
                    <i class="fas fa-tools w-6"></i> Services
                </Link>
                <Link :href="route('admin.service-categories.index')" :class="route().current('admin.service-categories.*') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition font-bold">
                    <i class="fas fa-network-wired w-6"></i> Service Categories
                </Link>
                <Link :href="route('admin.orders.index')" :class="route().current('admin.orders.index') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition font-bold">
                    <i class="fas fa-shopping-bag w-6"></i> Orders
                </Link>
                <Link :href="route('admin.quotes.index')" :class="route().current('admin.quotes.index') ? 'bg-red text-white shadow-lg shadow-red/20' : 'hover:bg-white/5 text-white/40 hover:text-white'" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition font-bold">
                    <i class="fas fa-file-invoice-dollar w-6"></i> Quotes
                </Link>
            </nav>

            <div class="mt-auto">
                <Link :href="route('logout')" method="post" as="button" class="w-full text-left flex items-center gap-4 px-6 py-4 text-red-400 hover:bg-red-400/10 rounded-2xl transition font-bold">
                    <i class="fas fa-sign-out-alt w-6"></i> Logout
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-12 overflow-y-auto">
            <header class="flex items-center justify-between mb-12">
                <div>
                    <h1 class="text-4xl font-heading font-black">{{ title }}</h1>
                    <div class="h-1 w-20 bg-red mt-4"></div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="font-bold">Admin User</div>
                            <div class="text-xs text-red uppercase font-bold tracking-widest">Super Admin</div>
                        </div>
                        <div class="w-12 h-12 bg-red rounded-xl flex items-center justify-center font-black text-white">A</div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div v-if="$page.props.flash.success" class="mb-8 p-6 bg-green-500/10 border border-green-500/20 text-green-500 rounded-[2rem] flex items-center gap-4 animate-in fade-in slide-in-from-top duration-500">
                <i class="fas fa-check-circle text-2xl"></i>
                <span class="font-bold">{{ $page.props.flash.success }}</span>
            </div>
            <div v-if="$page.props.flash.error" class="mb-8 p-6 bg-red-500/10 border border-red-500/20 text-red-500 rounded-[2rem] flex items-center gap-4 animate-in fade-in slide-in-from-top duration-500">
                <i class="fas fa-exclamation-circle text-2xl"></i>
                <span class="font-bold">{{ $page.props.flash.error }}</span>
            </div>

            <slot />
        </main>
    </div>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
