<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
});
</script>

<template>
    <Head title="Order Details — Laptech" />

    <AuthenticatedLayout>
        <template #header>
            Order <span class="text-red">Details</span>
        </template>

        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Order Header Card -->
            <div class="bg-surface border border-line rounded-2xl p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="px-3 py-1 bg-red/10 border border-red/20 rounded-full text-xs font-bold text-red uppercase tracking-widest">
                            {{ order.status }}
                        </div>
                        <span class="text-muted text-xs font-semibold">{{ new Date(order.created_at).toLocaleString() }}</span>
                    </div>
                    <h2 class="text-2xl font-heading font-extrabold mb-1">#{{ order.id.toString().slice(-12).toUpperCase() }}</h2>
                    <p class="text-muted font-semibold">Total: <span class="text-red text-lg ml-2">Ksh {{ order.total.toLocaleString() }}</span></p>
                </div>
                <Link :href="route('dashboard')" class="block text-center px-6 py-3 bg-surface-muted hover:bg-line rounded-xl font-bold transition text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </Link>
            </div>

            <!-- Timeline -->
            <section class="space-y-5">
                <h3 class="text-lg font-heading font-bold flex items-center gap-3">
                    <i class="fas fa-stream text-red text-sm"></i>
                    Status Timeline
                </h3>
                <div class="relative pl-12 space-y-8 before:absolute before:left-5 before:top-0 before:bottom-0 before:w-0.5 before:bg-line">
                    <div class="relative">
                        <div class="absolute -left-12 w-10 h-10 rounded-xl bg-red flex items-center justify-center z-10 shadow-lg shadow-red/20">
                            <i class="fas fa-check text-xs text-white"></i>
                        </div>
                        <div class="bg-surface border border-line rounded-2xl p-5">
                            <h4 class="font-bold mb-1">Order Placed</h4>
                            <p class="text-sm text-muted">Your order has been received and confirmed.</p>
                        </div>
                    </div>
                    <div class="relative opacity-50">
                        <div class="absolute -left-12 w-10 h-10 rounded-xl bg-surface border border-line flex items-center justify-center z-10">
                            <i class="fas fa-truck text-xs text-muted"></i>
                        </div>
                        <div class="bg-surface border border-line rounded-2xl p-5">
                            <h4 class="font-bold mb-1">Out for Delivery</h4>
                            <p class="text-sm text-muted">Your package is on its way.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Order Items -->
            <section class="space-y-5">
                <h3 class="text-lg font-heading font-bold flex items-center gap-3">
                    <i class="fas fa-box text-red text-sm"></i>
                    Items
                </h3>
                <div class="bg-surface border border-line rounded-2xl overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-surface-muted text-[10px] uppercase tracking-widest text-muted font-bold">
                                <th class="px-6 py-4">Product</th>
                                <th class="px-6 py-4">Qty</th>
                                <th class="px-6 py-4 text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            <tr v-for="item in order.items" :key="item.id" class="hover:bg-surface-muted transition">
                                <td class="px-6 py-4 font-bold">{{ item.name }}</td>
                                <td class="px-6 py-4 text-muted text-sm">{{ item.quantity }}</td>
                                <td class="px-6 py-4 text-right font-extrabold text-ink">Ksh {{ item.price.toLocaleString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Shipping -->
            <section class="space-y-5">
                <h3 class="text-lg font-heading font-bold flex items-center gap-3">
                    <i class="fas fa-map-marker-alt text-red text-sm"></i>
                    Delivery Details
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-surface border border-line rounded-2xl p-6 space-y-2">
                        <div class="text-xs font-bold uppercase tracking-widest text-muted">Recipient</div>
                        <div class="font-bold text-lg">{{ order.shipping_address.name }}</div>
                        <div class="text-sm text-muted">{{ order.shipping_address.email }}</div>
                        <div class="text-sm text-muted">{{ order.shipping_address.phone }}</div>
                    </div>
                    <div class="bg-surface border border-line rounded-2xl p-6 space-y-2">
                        <div class="text-xs font-bold uppercase tracking-widest text-muted">Address</div>
                        <div class="font-bold text-lg text-red">{{ order.shipping_address.city }}</div>
                        <div class="text-sm text-muted leading-relaxed">{{ order.shipping_address.address }}</div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
