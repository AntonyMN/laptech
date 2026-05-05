<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    orders: Array,
    quotes: Array,
});
</script>

<template>
    <Head title="System Dashboard - Laptech" />

    <AuthenticatedLayout>
        <template #header>
            System <span class="text-red">Intelligence</span>
        </template>

        <div class="space-y-12">
            <!-- Welcome Section -->
            <section class="bg-red/10 border border-red/20 rounded-[2.5rem] p-10 flex items-center justify-between overflow-hidden relative group">
                <div class="relative z-10">
                    <h3 class="text-3xl font-heading font-black mb-2">Welcome Back, <span class="text-red">{{ $page.props.auth.user.name }}</span></h3>
                    <p class="text-white/50 max-w-md">Your tech ecosystem is running smoothly. Monitor your hardware orders and service inquiries below.</p>
                </div>
                <div class="absolute -right-10 top-0 h-full w-1/3 bg-gradient-to-l from-red/20 to-transparent pointer-events-none group-hover:w-1/2 transition-all duration-700"></div>
                <i class="fas fa-microchip text-8xl text-red/10 absolute right-10 top-1/2 -translate-y-1/2 rotate-12 group-hover:scale-110 transition duration-700"></i>
            </section>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Orders Section -->
                <section>
                    <div class="flex items-center justify-between mb-8">
                        <h4 class="text-2xl font-heading font-black flex items-center gap-4">
                            <i class="fas fa-shopping-bag text-red"></i>
                            Hardware <span class="text-red">Orders</span>
                        </h4>
                        <span class="text-xs font-bold uppercase tracking-widest text-white/20">{{ orders.length }} Total</span>
                    </div>

                    <div class="space-y-4">
                        <div v-for="order in orders" :key="order.id" class="bg-charcoal-light border border-white/5 rounded-3xl p-6 hover:border-red/30 transition group">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="text-xs text-white/30 uppercase font-black tracking-tighter mb-1">Order ID</div>
                                    <div class="font-bold">#{{ order.id.toString().slice(-8).toUpperCase() }}</div>
                                </div>
                                <div class="text-right">
                                    <div :class="{
                                        'bg-orange-500/10 text-orange-500': order.status === 'pending',
                                        'bg-green-500/10 text-green-500': order.status === 'delivered',
                                        'bg-blue-500/10 text-blue-500': order.status === 'shipped'
                                    }" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                        {{ order.status }}
                                    </div>
                                    <div class="text-[10px] text-white/20 mt-1 font-bold">{{ new Date(order.created_at).toLocaleDateString() }}</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                <div class="text-red font-black">Ksh {{ order.total.toLocaleString() }}</div>
                                <Link :href="route('orders.show', order.id)" class="text-xs font-bold text-white/40 group-hover:text-white transition">Full Traceability <i class="fas fa-chevron-right ml-1"></i></Link>
                            </div>

                        </div>

                        <div v-if="orders.length === 0" class="bg-white/5 border border-dashed border-white/10 rounded-3xl p-12 text-center text-white/20">
                            <i class="fas fa-box-open text-4xl mb-4"></i>
                            <p class="font-bold">No hardware orders initiated yet.</p>
                            <Link :href="route('welcome')" class="text-red text-xs mt-4 inline-block hover:underline">Browse Catalog</Link>
                        </div>
                    </div>
                </section>

                <!-- Quotes Section -->
                <section>
                    <div class="flex items-center justify-between mb-8">
                        <h4 class="text-2xl font-heading font-black flex items-center gap-4">
                            <i class="fas fa-file-invoice-dollar text-red"></i>
                            Service <span class="text-red">Inquiries</span>
                        </h4>
                        <span class="text-xs font-bold uppercase tracking-widest text-white/20">{{ quotes.length }} Active</span>
                    </div>

                    <div class="space-y-4">
                        <div v-for="quote in quotes" :key="quote.id" class="bg-charcoal-light border border-white/5 rounded-3xl p-6 hover:border-red/30 transition group">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="text-xs text-white/30 uppercase font-black tracking-tighter mb-1">{{ quote.service?.name }}</div>
                                    <div class="font-bold truncate max-w-xs">{{ quote.details }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="px-3 py-1 bg-red/10 text-red rounded-full text-[10px] font-black uppercase tracking-widest">
                                        {{ quote.status }}
                                    </div>
                                    <div class="text-[10px] text-white/20 mt-1 font-bold">{{ new Date(quote.created_at).toLocaleDateString() }}</div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-white/5">
                                <button class="text-xs font-bold text-white/40 group-hover:text-white transition flex items-center gap-2">
                                    <i class="fas fa-comment-alt text-red text-[10px]"></i> View Communication Thread
                                </button>
                            </div>
                        </div>

                        <div v-if="quotes.length === 0" class="bg-white/5 border border-dashed border-white/10 rounded-3xl p-12 text-center text-white/20">
                            <i class="fas fa-file-contract text-4xl mb-4"></i>
                            <p class="font-bold">No service inquiries found.</p>
                            <Link :href="route('welcome')" class="text-red text-xs mt-4 inline-block hover:underline">Request Technician</Link>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
