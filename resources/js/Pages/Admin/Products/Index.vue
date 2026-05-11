<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    products: Array,
});

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('admin.products.delete', id));
    }
};
</script>

<template>
    <AdminLayout title="Product Management">
        <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
            <div class="p-10 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-bold">Inventory List</h3>
                <Link :href="route('admin.products.create')" class="bg-red hover:bg-red-light text-white px-6 py-3 rounded-xl font-bold transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add Product
                </Link>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 text-xs uppercase tracking-widest text-white/40 font-bold">
                            <th class="px-10 py-6">Product</th>
                            <th class="px-10 py-6">Category</th>
                            <th class="px-10 py-6">Price</th>
                            <th class="px-10 py-6">Stock</th>
                            <th class="px-10 py-6">Status</th>
                            <th class="px-10 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr v-for="product in products" :key="product.id" class="hover:bg-white/5 transition group">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-charcoal rounded-lg overflow-hidden shrink-0">
                                        <img :src="product.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=100'" class="w-full h-full object-cover" />
                                    </div>
                                    <span class="font-bold">{{ product.name }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6 text-white/50">{{ product.category?.name }}</td>
                            <td class="px-10 py-6 font-bold text-red">Ksh {{ product.price.toLocaleString() }}</td>
                            <td class="px-10 py-6">{{ product.stock }}</td>
                            <td class="px-10 py-6">
                                <span class="px-3 py-1 bg-green-500/10 text-green-500 rounded-full text-[10px] font-bold uppercase tracking-tighter">Active</span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="route('admin.products.edit', product.id)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red transition flex items-center justify-center">
                                        <i class="fas fa-edit text-xs"></i>
                                    </Link>
                                    <button @click="deleteProduct(product.id)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500 transition flex items-center justify-center">
                                        <i class="fas fa-trash-alt text-xs"></i>
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
