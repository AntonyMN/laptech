<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';



const props = defineProps({
    stats: Object,
    recent_orders: Array,
});
</script>

<template>
    <AdminLayout title="Dashboard">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="bg-charcoal-light border border-white/5 p-8 rounded-[2rem] hover:border-red/50 transition">
                <div class="w-12 h-12 bg-red/20 text-red rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="text-4xl font-black mb-2">{{ stats.total_orders }}</div>
                <div class="text-white/40 text-sm font-bold uppercase tracking-widest">Total Orders</div>
            </div>
            <div class="bg-charcoal-light border border-white/5 p-8 rounded-[2rem] hover:border-red/50 transition">
                <div class="w-12 h-12 bg-red/20 text-red rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="text-4xl font-black mb-2">{{ stats.total_quotes }}</div>
                <div class="text-white/40 text-sm font-bold uppercase tracking-widest">Quote Requests</div>
            </div>
            <div class="bg-charcoal-light border border-white/5 p-8 rounded-[2rem] hover:border-red/50 transition">
                <div class="w-12 h-12 bg-red/20 text-red rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-box"></i>
                </div>
                <div class="text-4xl font-black mb-2">{{ stats.total_products }}</div>
                <div class="text-white/40 text-sm font-bold uppercase tracking-widest">Active Products</div>
            </div>
            <div class="bg-charcoal-light border border-white/5 p-8 rounded-[2rem] hover:border-red/50 transition">
                <div class="w-12 h-12 bg-red/20 text-red rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="text-3xl font-black mb-2">Ksh {{ stats.revenue.toLocaleString() }}</div>
                <div class="text-white/40 text-sm font-bold uppercase tracking-widest">Total Revenue</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid lg:grid-cols-2 gap-12">
            <section class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-heading font-black">Recent <span class="text-red">Orders</span></h3>
                    <Link :href="route('admin.orders.index')" class="text-red text-sm font-bold hover:underline">View All</Link>
                </div>
                <div class="space-y-4">
                    <div v-for="order in recent_orders" :key="order.id" class="flex items-center justify-between p-6 bg-white/5 rounded-2xl hover:bg-white/10 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-red/20 rounded-lg flex items-center justify-center text-red">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <div class="font-bold">#{{ order.id.toString().slice(-6) }}</div>
                                <div class="text-xs text-white/40">{{ order.shipping_address.name }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-black text-red">Ksh {{ order.total.toLocaleString() }}</div>
                            <div class="text-[10px] uppercase font-bold tracking-widest px-2 py-1 bg-white/10 rounded-full inline-block mt-1">
                                {{ order.status }}
                            </div>
                        </div>
                    </div>
                    <div v-if="recent_orders.length === 0" class="text-center py-12 text-white/20 font-bold">No orders yet</div>
                </div>
            </section>

            <section class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-heading font-black">Platform <span class="text-red">Insights</span></h3>
                    <i class="fas fa-chart-line text-red"></i>
                </div>
                <div class="h-64 flex flex-col items-center justify-center text-white/20 border-2 border-dashed border-white/5 rounded-3xl">
                    <i class="fas fa-microchip text-4xl mb-4 opacity-10"></i>
                    <p class="font-bold italic">Analytics data syncing...</p>
                </div>
            </section>
        </div>
    </AdminLayout>
</template>
