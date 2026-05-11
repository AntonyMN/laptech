<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    services: Array,
});

const deleteService = (id) => {
    if (confirm('Are you sure?')) {
        router.delete(route('admin.services.delete', id));
    }
};
</script>

<template>
    <AdminLayout title="Service Management">
        <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
            <div class="p-10 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-bold">Offerings List</h3>
                <Link :href="route('admin.services.create')" class="bg-red hover:bg-red-light text-white px-6 py-3 rounded-xl font-bold transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add Service
                </Link>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 text-xs uppercase tracking-widest text-white/40 font-bold">
                            <th class="px-10 py-6">Service</th>
                            <th class="px-10 py-6">Category</th>
                            <th class="px-10 py-6">Status</th>
                            <th class="px-10 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr v-for="service in services" :key="service.id" class="hover:bg-white/5 transition group">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-red/20 rounded-lg flex items-center justify-center text-red">
                                        <i :class="service.icon"></i>
                                    </div>
                                    <span class="font-bold">{{ service.name }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6 text-white/50">{{ service.category?.name }}</td>
                            <td class="px-10 py-6">
                                <span v-if="service.is_featured" class="px-3 py-1 bg-red/10 text-red rounded-full text-[10px] font-bold uppercase tracking-tighter">Featured</span>
                                <span v-else class="px-3 py-1 bg-white/5 text-white/40 rounded-full text-[10px] font-bold uppercase tracking-tighter">Standard</span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="route('admin.services.edit', service.id)" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-red transition flex items-center justify-center">
                                        <i class="fas fa-edit text-sm"></i>
                                    </Link>
                                    <button @click="deleteService(service.id)" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-red-500 transition flex items-center justify-center">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
