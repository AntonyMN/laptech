<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const categories = computed(() => page.props.navCategories || []);
</script>

<template>
    <!-- Trust / payment strip -->
    <section class="bg-surface border-t border-line">
        <div class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex items-center gap-3">
                <i class="fas fa-truck text-red text-2xl"></i>
                <div>
                    <p class="text-sm font-bold text-ink">Nationwide Delivery</p>
                    <p class="text-xs text-muted">Delivered in 1–3 business days</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <i class="fas fa-undo text-red text-2xl"></i>
                <div>
                    <p class="text-sm font-bold text-ink">7-Day Returns</p>
                    <p class="text-xs text-muted">Shop with confidence</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <i class="fas fa-shield-alt text-red text-2xl"></i>
                <div>
                    <p class="text-sm font-bold text-ink">Genuine Warranty</p>
                    <p class="text-xs text-muted">Certified quality products</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <i class="fas fa-headset text-red text-2xl"></i>
                <div>
                    <p class="text-sm font-bold text-ink">Expert Support</p>
                    <p class="text-xs text-muted">Mon–Sat 9am–7pm</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main footer -->
    <footer class="bg-charcoal text-white/70 pt-16 pb-8 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
            <div class="lg:col-span-2">
                <Link :href="route('welcome')" class="inline-block mb-5">
                    <img src="/images/logo.png" alt="Laptech" class="h-12 w-auto p-2 rounded-lg bg-white">
                </Link>
                <p class="text-sm leading-relaxed text-white/50 max-w-sm mb-6">
                    Your trusted store for laptops, desktops, accessories and expert IT services in Nairobi. Quality hardware, honest prices, reliable support.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-red flex items-center justify-center transition"><i class="fab fa-facebook-f text-sm"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-red flex items-center justify-center transition"><i class="fab fa-instagram text-sm"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 hover:bg-red flex items-center justify-center transition"><i class="fab fa-tiktok text-sm"></i></a>
                    <a href="https://wa.me/254722964566" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 hover:bg-red flex items-center justify-center transition"><i class="fab fa-whatsapp text-sm"></i></a>
                </div>
            </div>

            <div>
                <h4 class="font-bold mb-5 uppercase tracking-widest text-xs text-white/40">Shop</h4>
                <ul class="space-y-3 text-sm">
                    <li><Link :href="route('products.index')" class="hover:text-red transition">All Products</Link></li>
                    <li v-for="cat in categories.slice(0, 5)" :key="cat.id">
                        <Link :href="route('products.index', { category: cat.id })" class="hover:text-red transition">{{ cat.name }}</Link>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-5 uppercase tracking-widest text-xs text-white/40">Customer Service</h4>
                <ul class="space-y-3 text-sm">
                    <li><Link :href="route('services.index')" class="hover:text-red transition">Services</Link></li>
                    <li><Link :href="route('quotes.create')" class="hover:text-red transition">Request a Quote</Link></li>
                    <li><Link :href="route('blog.index')" class="hover:text-red transition">Blog</Link></li>
                    <li><Link :href="route('compare.index')" class="hover:text-red transition">Compare Products</Link></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-5 uppercase tracking-widest text-xs text-white/40">Contact</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt text-red mt-1"></i> Nairobi, Kenya</li>
                    <li class="flex items-center gap-3"><i class="fas fa-phone-alt text-red"></i> <a href="tel:+254722964566" class="hover:text-red transition">+254 722 964566</a></li>
                    <li class="flex items-center gap-3"><i class="fas fa-envelope text-red"></i> <a href="mailto:info@laptech.co.ke" class="hover:text-red transition">info@laptech.co.ke</a></li>
                </ul>
            </div>
        </div>

        <!-- Payment methods -->
        <div class="max-w-7xl mx-auto pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-xs text-white/40">&copy; 2026 Laptech Electronics. All rights reserved.</p>
            <div class="flex items-center gap-2 text-white/70">
                <span class="text-[11px] uppercase tracking-widest text-white/30 mr-1">We accept</span>
                <span class="px-2 py-1 rounded bg-white/10 text-[11px] font-bold">M-Pesa</span>
                <span class="px-2 py-1 rounded bg-white/10 text-[11px] font-bold">Airtel</span>
                <i class="fab fa-cc-visa text-2xl"></i>
                <i class="fab fa-cc-mastercard text-2xl"></i>
            </div>
        </div>
    </footer>
</template>
