<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import BulkProductModal from '@/Components/BulkProductModal.vue';
import ProductFormModal from '@/Components/ProductFormModal.vue';

import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    products: Object, // Laravel paginator: { data, links, from, to, total, last_page, ... }
    categories: Array,
    filters: Object,
});

const showBulkModal = ref(false);
const showForm = ref(false);
const editing = ref(null);

const openCreate = () => { editing.value = null; showForm.value = true; };
const openEdit = (product) => { editing.value = product; showForm.value = true; };

const search = ref(props.filters?.search ?? '');

// Debounced server-side search (preserves scroll + input focus)
let searchTimer = null;
watch(search, (value) => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get(
            route('admin.products.index'),
            { search: value || undefined },
            { preserveState: true, preserveScroll: true, replace: true },
        );
    }, 300);
});

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('admin.products.delete', id));
    }
};

const getStatusClasses = (status) => {
    switch (status) {
        case 'Brand new':
            return 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
        case 'Ex-UK':
            return 'bg-purple-500/10 text-purple-400 border border-purple-500/20';
        case 'Certified Refurbished':
            return 'bg-blue-500/10 text-blue-400 border border-blue-500/20';
        default:
            return 'bg-white/5 text-white/50 border border-white/10';
    }
};
</script>

<template>
    <AdminLayout title="Product Management">
        <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
            <div class="p-10 border-b border-white/5 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <h3 class="text-xl font-bold">Inventory List</h3>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search products or category..."
                            class="bg-charcoal border border-white/10 rounded-xl pl-11 pr-4 py-3 text-sm w-full md:w-72 text-white placeholder-white/30 focus:outline-none focus:border-red transition"
                        />
                    </div>
                    <button @click="showBulkModal = true" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white px-5 py-3 rounded-xl font-bold transition flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-file-excel text-emerald-400"></i> Bulk Update
                    </button>
                    <button @click="openCreate" class="bg-red hover:bg-red-light text-white px-6 py-3 rounded-xl font-bold transition flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </div>
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
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-white/5 transition group">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-charcoal rounded-lg overflow-hidden shrink-0">
                                        <img
                                            :src="product.image || 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&q=80&w=100'"
                                            loading="lazy"
                                            decoding="async"
                                            width="48"
                                            height="48"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <span class="font-bold">{{ product.name }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6 text-white/50">{{ product.category?.name }}</td>
                            <td class="px-10 py-6 font-bold text-red">Ksh {{ product.price.toLocaleString() }}</td>
                            <td class="px-10 py-6">{{ product.stock }}</td>
                            <td class="px-10 py-6">
                                <span :class="getStatusClasses(product.status)" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter">
                                    {{ product.status || 'Brand new' }}
                                </span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEdit(product)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red transition flex items-center justify-center">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button @click="deleteProduct(product.id)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500 transition flex items-center justify-center">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="products.data.length === 0">
                            <td colspan="6" class="px-10 py-16 text-center text-white/40">
                                No products found<span v-if="search"> for “{{ search }}”</span>.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="products.last_page > 1" class="p-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-sm text-white/40">Showing {{ products.from }}–{{ products.to }} of {{ products.total }}</span>
                <div class="flex items-center gap-1 flex-wrap justify-center">
                    <template v-for="(link, i) in products.links" :key="i">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            preserve-scroll
                            preserve-state
                            :class="link.active ? 'bg-red text-white' : 'bg-white/5 text-white/60 hover:bg-white/10'"
                            class="min-w-9 h-9 px-3 rounded-lg text-sm font-bold flex items-center justify-center transition"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="min-w-9 h-9 px-3 rounded-lg text-sm text-white/20 flex items-center justify-center"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>

        <BulkProductModal :show="showBulkModal" @close="showBulkModal = false" />
        <ProductFormModal :show="showForm" :product="editing" :categories="categories" @close="showForm = false" />
    </AdminLayout>
</template>
