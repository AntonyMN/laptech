<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';


const props = defineProps({
    orders: Array,
});

const updateStatus = (id, status) => {
    router.patch(route('admin.orders.update-status', id), { status });
};

</script>

<template>
    <AdminLayout title="Order Management">
        <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
            <div class="p-10 border-b border-white/5">
                <h3 class="text-xl font-bold">All Orders</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 text-xs uppercase tracking-widest text-white/40 font-bold">
                            <th class="px-10 py-6">Order ID</th>
                            <th class="px-10 py-6">Customer</th>
                            <th class="px-10 py-6">Total</th>
                            <th class="px-10 py-6">Status</th>
                            <th class="px-10 py-6">Date</th>
                            <th class="px-10 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr v-for="order in orders" :key="order.id" class="hover:bg-white/5 transition group">
                            <td class="px-10 py-6 font-bold">#{{ order.id.toString().slice(-8).toUpperCase() }}</td>
                            <td class="px-10 py-6">
                                <div class="font-bold">{{ order.shipping_address.name }}</div>
                                <div class="text-xs text-white/40">{{ order.shipping_address.email }}</div>
                            </td>
                            <td class="px-10 py-6 font-black text-red">Ksh {{ order.total.toLocaleString() }}</td>
                            <td class="px-10 py-6">
                                <select 
                                    @change="updateStatus(order.id, $event.target.value)"
                                    :class="{
                                        'bg-orange-500/10 text-orange-500': order.status === 'pending',
                                        'bg-green-500/10 text-green-500': order.status === 'delivered',
                                        'bg-blue-500/10 text-blue-500': order.status === 'shipped',
                                        'bg-red-500/10 text-red-500': order.status === 'cancelled'
                                    }" 
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter border-none focus:ring-0 cursor-pointer"
                                    :value="order.status"
                                >
                                    <option value="pending">Pending</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </td>

                            <td class="px-10 py-6 text-white/40 text-sm">{{ new Date(order.created_at).toLocaleDateString() }}</td>
                            <td class="px-10 py-6 text-right">
                                <button class="bg-white/5 hover:bg-red text-white px-4 py-2 rounded-lg font-bold transition text-xs">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        <tr v-if="orders.length === 0">
                            <td colspan="6" class="px-10 py-20 text-center text-white/20 font-bold">No orders found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
