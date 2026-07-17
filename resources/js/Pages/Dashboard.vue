<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    orders: Array,
    quotes: Array,
});
</script>

<template>
    <Head title="Dashboard — Laptech">
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <AuthenticatedLayout>
        <template #header>
            My <span class="text-red">Dashboard</span>
        </template>

        <div class="space-y-10">
            <!-- Welcome Section -->
            <section class="bg-surface border border-line rounded-2xl p-8 flex items-center justify-between overflow-hidden relative">
                <div class="relative z-10">
                    <h3 class="text-2xl font-heading font-extrabold mb-2">Welcome back, <span class="text-red">{{ $page.props.auth.user.name }}</span></h3>
                    <p class="text-muted max-w-md">Track your orders and service inquiries below.</p>
                </div>
                <i class="fas fa-laptop text-7xl text-red/10 absolute right-8 top-1/2 -translate-y-1/2 rotate-6"></i>
            </section>

            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Orders Section -->
                <section>
                    <div class="flex items-center justify-between mb-5">
                        <h4 class="text-lg font-heading font-bold flex items-center gap-3">
                            <i class="fas fa-shopping-bag text-red"></i>
                            My Orders
                        </h4>
                        <span class="text-xs font-bold uppercase tracking-widest text-muted">{{ orders.length }} Total</span>
                    </div>

                    <div class="space-y-4">
                        <div v-for="order in orders" :key="order.id" class="bg-surface border border-line rounded-2xl p-5 hover:border-red/30 transition group">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="text-xs text-muted uppercase font-bold mb-1">Order ID</div>
                                    <div class="font-bold">#{{ order.id.toString().slice(-8).toUpperCase() }}</div>
                                </div>
                                <div class="text-right">
                                    <div :class="{
                                        'bg-orange-100 text-orange-700': order.status === 'pending',
                                        'bg-green-100 text-green-700': order.status === 'delivered',
                                        'bg-blue-100 text-blue-700': order.status === 'shipped'
                                    }" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                                        {{ order.status }}
                                    </div>
                                    <div class="text-[10px] text-muted mt-1 font-semibold">{{ new Date(order.created_at).toLocaleDateString() }}</div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-line">
                                <div class="text-red font-extrabold">Ksh {{ order.total.toLocaleString() }}</div>
                                <Link :href="route('orders.show', order.id)" class="text-xs font-bold text-muted group-hover:text-ink transition">View details <i class="fas fa-chevron-right ml-1"></i></Link>
                            </div>
                        </div>

                        <div v-if="orders.length === 0" class="bg-surface border border-dashed border-line rounded-2xl p-10 text-center text-muted">
                            <i class="fas fa-box-open text-4xl mb-4"></i>
                            <p class="font-bold">No orders yet.</p>
                            <Link :href="route('products.index')" class="text-red text-xs mt-3 inline-block hover:underline">Browse Products</Link>
                        </div>
                    </div>
                </section>

                <!-- Quotes Section -->
                <section>
                    <div class="flex items-center justify-between mb-5">
                        <h4 class="text-lg font-heading font-bold flex items-center gap-3">
                            <i class="fas fa-file-invoice-dollar text-red"></i>
                            Service Inquiries
                        </h4>
                        <span class="text-xs font-bold uppercase tracking-widest text-muted">{{ quotes.length }} Active</span>
                    </div>

                    <div class="space-y-4">
                        <div v-for="quote in quotes" :key="quote.id" class="bg-surface border border-line rounded-2xl p-5 hover:border-red/30 transition group">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-muted uppercase font-bold mb-1">{{ quote.service?.name }}</div>
                                    <div class="font-bold truncate">{{ quote.details }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="px-3 py-1 bg-red/10 text-red rounded-full text-[10px] font-bold uppercase tracking-widest">
                                        {{ quote.status }}
                                    </div>
                                    <div class="text-[10px] text-muted mt-1 font-semibold">{{ new Date(quote.created_at).toLocaleDateString() }}</div>
                                </div>
                            </div>
                        </div>

                        <div v-if="quotes.length === 0" class="bg-surface border border-dashed border-line rounded-2xl p-10 text-center text-muted">
                            <i class="fas fa-file-contract text-4xl mb-4"></i>
                            <p class="font-bold">No service inquiries yet.</p>
                            <Link :href="route('quotes.create')" class="text-red text-xs mt-3 inline-block hover:underline">Request a Quote</Link>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
