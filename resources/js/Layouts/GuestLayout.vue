<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-charcoal text-white font-sans flex flex-col items-center justify-center p-6 selection:bg-red selection:text-white relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
            <div class="absolute -top-1/4 -right-1/4 w-[600px] h-[600px] bg-red/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-1/4 -left-1/4 w-[600px] h-[600px] bg-red/5 rounded-full blur-[100px]"></div>
        </div>

        <div class="w-full sm:max-w-md z-10">
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-12">
                <Link href="/" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 bg-red rounded-2xl flex items-center justify-center transition group-hover:scale-110 group-hover:rotate-12 duration-500">
                        <i class="fas fa-microchip text-white text-2xl"></i>
                    </div>
                    <span class="text-3xl font-heading font-extrabold tracking-tighter uppercase">
                        Lap<span class="text-red">tech</span>
                    </span>
                </Link>
                <div class="mt-4 h-1 w-12 bg-red/30 rounded-full"></div>
            </div>

            <!-- Content Card -->
            <div class="bg-charcoal-light border border-white/10 rounded-[2.5rem] p-10 shadow-2xl backdrop-blur-sm">
                <slot />
            </div>

            <!-- Footer Link -->
            <div class="mt-8 text-center text-white/40 text-sm">
                <Link :href="route('welcome')" class="hover:text-red transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Hub
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
