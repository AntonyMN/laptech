<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="bg-charcoal-dark pt-24 pb-12 px-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-12 mb-16">
            <div class="col-span-2">
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-red rounded flex items-center justify-center">
                        <i class="fas fa-microchip text-white"></i>
                    </div>
                    <span class="text-2xl font-heading font-extrabold tracking-tighter uppercase">
                        Lap<span class="text-red">tech</span>
                    </span>
                </div>
                <p class="text-white/40 max-w-sm leading-relaxed">
                    Premium technology solutions for the modern enterprise. We provide high-performance hardware and expert IT services.
                </p>
            </div>
            <div>
                <h4 class="font-bold mb-6 uppercase tracking-widest text-xs text-white/50">Company</h4>
                <ul class="space-y-4 text-white/70 text-sm">
                    <li><Link :href="route('welcome')" class="hover:text-red transition">Home Intelligence</Link></li>
                    <li><Link :href="route('products.index')" class="hover:text-red transition">Hardware Inventory</Link></li>
                    <li><Link :href="route('services.index')" class="hover:text-red transition">Service Hub</Link></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6 uppercase tracking-widest text-xs text-white/50">Support</h4>
                <ul class="space-y-4 text-white/70 text-sm">
                    <li><a href="#" class="hover:text-red transition">Knowledge Base</a></li>
                    <li><a href="#" class="hover:text-red transition">Contact Support</a></li>
                    <li><a href="#" class="hover:text-red transition">Warranty Signal</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 text-white/30 text-sm">
            <p>&copy; 2026 Laptech Solutions. All rights reserved.</p>
            <div class="flex gap-6">
                <i class="fab fa-twitter hover:text-red transition cursor-pointer"></i>
                <i class="fab fa-linkedin hover:text-red transition cursor-pointer"></i>
                <i class="fab fa-github hover:text-red transition cursor-pointer"></i>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
