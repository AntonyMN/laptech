<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-page text-ink font-sans flex flex-col items-center justify-center p-6 selection:bg-red selection:text-white">
        <div class="w-full sm:max-w-md">
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-8">
                <Link href="/" class="flex items-center gap-3 group">
                    <img src="/images/logo.png" alt="Laptech" class="h-12 w-auto transition group-hover:scale-105 duration-300" />
                </Link>
                <div class="mt-4 h-1 w-12 bg-red/30 rounded-full"></div>
            </div>

            <!-- Content Card -->
            <div class="bg-surface border border-line rounded-3xl p-8 md:p-10 shadow-xl">
                <slot />
            </div>

            <!-- Footer Link -->
            <div class="mt-6 text-center text-muted text-sm">
                <Link :href="route('welcome')" class="hover:text-red transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to store
                </Link>
            </div>
        </div>
    </div>
</template>
