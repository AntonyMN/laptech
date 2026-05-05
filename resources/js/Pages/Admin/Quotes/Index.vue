<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';


const props = defineProps({
    quotes: Array,
});

const updateStatus = (id, status) => {
    router.patch(route('admin.quotes.update-status', id), { status });
};

</script>

<template>
    <AdminLayout title="Quote Requests">
        <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
            <div class="p-10 border-b border-white/5">
                <h3 class="text-xl font-bold">Service Inquiries</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 text-xs uppercase tracking-widest text-white/40 font-bold">
                            <th class="px-10 py-6">Service</th>
                            <th class="px-10 py-6">Customer</th>
                            <th class="px-10 py-6">Details</th>
                            <th class="px-10 py-6">Status</th>
                            <th class="px-10 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr v-for="quote in quotes" :key="quote.id" class="hover:bg-white/5 transition group">
                            <td class="px-10 py-6 font-bold text-red">{{ quote.service?.name }}</td>
                            <td class="px-10 py-6">
                                <div class="font-bold">{{ quote.name }}</div>
                                <div class="text-xs text-white/40">{{ quote.email }}</div>
                            </td>
                            <td class="px-10 py-6 text-sm text-white/50 max-w-xs truncate">{{ quote.details }}</td>
                            <td class="px-10 py-6">
                                <select 
                                    @change="updateStatus(quote.id, $event.target.value)"
                                    class="px-3 py-1 bg-red/10 text-red rounded-full text-[10px] font-bold uppercase tracking-tighter border-none focus:ring-0 cursor-pointer"
                                    :value="quote.status"
                                >
                                    <option value="pending">Pending</option>
                                    <option value="responded">Responded</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </td>

                            <td class="px-10 py-6 text-right">
                                <button class="bg-white/5 hover:bg-red text-white px-4 py-2 rounded-lg font-bold transition text-xs">
                                    Respond
                                </button>
                            </td>
                        </tr>
                        <tr v-if="quotes.length === 0">
                            <td colspan="5" class="px-10 py-20 text-center text-white/20 font-bold">No quote requests yet</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
