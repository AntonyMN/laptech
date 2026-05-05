<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
});
</script>

<template>
    <Head title="Order Traceability - Laptech" />

    <AuthenticatedLayout>
        <template #header>
            Order <span class="text-red">Traceability</span>
        </template>

        <div class="max-w-4xl mx-auto space-y-12">
            <!-- Order Header Card -->
            <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="px-4 py-1 bg-red/10 border border-red/20 rounded-full text-xs font-black text-red uppercase tracking-widest">
                            {{ order.status }}
                        </div>
                        <span class="text-white/20 text-xs font-bold">{{ new Date(order.created_at).toLocaleString() }}</span>
                    </div>
                    <h2 class="text-4xl font-heading font-black mb-2">#{{ order.id.toString().slice(-12).toUpperCase() }}</h2>
                    <p class="text-white/40 font-bold">Total Investment: <span class="text-red text-xl ml-2">Ksh {{ order.total.toLocaleString() }}</span></p>
                </div>
                <div class="relative z-10 w-full md:w-auto">
                    <Link :href="route('dashboard')" class="block text-center px-8 py-4 bg-white/5 hover:bg-white/10 rounded-2xl font-bold transition text-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Return to Intelligence
                    </Link>
                </div>
                <i class="fas fa-box-open absolute -right-4 -bottom-4 text-white/5 text-9xl"></i>
            </div>

            <!-- Traceability Timeline -->
            <section class="space-y-8">
                <h3 class="text-xl font-heading font-black flex items-center gap-4">
                    <i class="fas fa-stream text-red text-sm"></i>
                    Status <span class="text-red">Timeline</span>
                </h3>
                <div class="relative pl-12 space-y-12 before:absolute before:left-5 before:top-0 before:bottom-0 before:w-0.5 before:bg-white/5">
                    <div class="relative">
                        <div class="absolute -left-12 w-10 h-10 rounded-xl bg-red flex items-center justify-center z-10 shadow-lg shadow-red/20">
                            <i class="fas fa-check text-xs text-white"></i>
                        </div>
                        <div class="bg-white/5 border border-white/5 rounded-3xl p-6">
                            <h4 class="font-bold mb-1">Order Initiated</h4>
                            <p class="text-sm text-white/40">Request received and validated by system.</p>
                        </div>
                    </div>
                    <div class="relative opacity-30">
                        <div class="absolute -left-12 w-10 h-10 rounded-xl bg-charcoal border border-white/10 flex items-center justify-center z-10">
                            <i class="fas fa-truck text-xs text-white/40"></i>
                        </div>
                        <div class="bg-white/5 border border-white/5 rounded-3xl p-6">
                            <h4 class="font-bold mb-1">Logistics Transition</h4>
                            <p class="text-sm text-white/40">Hardware package moving to dispatch terminal.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Order Components -->
            <section class="space-y-8">
                <h3 class="text-xl font-heading font-black flex items-center gap-4">
                    <i class="fas fa-microchip text-red text-sm"></i>
                    Package <span class="text-red">Components</span>
                </h3>
                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5 text-[10px] uppercase tracking-widest text-white/40 font-black">
                                <th class="px-10 py-6">Component Name</th>
                                <th class="px-10 py-6">Qty</th>
                                <th class="px-10 py-6 text-right">Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-for="item in order.items" :key="item.id" class="hover:bg-white/5 transition group">
                                <td class="px-10 py-6 font-bold">{{ item.name }}</td>
                                <td class="px-10 py-6 text-white/40 font-mono text-sm">{{ item.quantity }}</td>
                                <td class="px-10 py-6 text-right font-black text-red">Ksh {{ item.price.toLocaleString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Shipping Destination -->
            <section class="space-y-8">
                <h3 class="text-xl font-heading font-black flex items-center gap-4">
                    <i class="fas fa-map-marker-alt text-red text-sm"></i>
                    Logistics <span class="text-red">Destination</span>
                </h3>
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white/5 border border-white/5 rounded-3xl p-8 space-y-4">
                        <div class="text-xs font-black uppercase tracking-widest text-white/20">Recipient</div>
                        <div class="font-bold text-lg">{{ order.shipping_address.name }}</div>
                        <div class="text-sm text-white/40">{{ order.shipping_address.email }}</div>
                        <div class="text-sm text-white/40">{{ order.shipping_address.phone }}</div>
                    </div>
                    <div class="bg-white/5 border border-white/5 rounded-3xl p-8 space-y-4">
                        <div class="text-xs font-black uppercase tracking-widest text-white/20">Terminal Location</div>
                        <div class="font-bold text-lg text-red">{{ order.shipping_address.city }}</div>
                        <div class="text-sm text-white/40 leading-relaxed">{{ order.shipping_address.address }}</div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
