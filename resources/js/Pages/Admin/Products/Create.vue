<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array,
});

const form = useForm({
    name: '',
    slug: '',
    description: '',
    price: '',
    stock: '',
    category_id: '',
    image: '',
    is_featured: false,
});

const submit = () => {
    form.post(route('admin.products.store'));
};
</script>

<template>
    <AdminLayout title="Add New Product">
        <div class="max-w-4xl bg-charcoal-light border border-white/5 rounded-[2.5rem] p-12">
            <form @submit.prevent="submit" class="space-y-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Product Name</label>
                        <input v-model="form.name" type="text" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Slug</label>
                        <input v-model="form.slug" type="text" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-white/30">Description</label>
                    <textarea v-model="form.description" rows="5" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required></textarea>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Price (Ksh)</label>
                        <input v-model="form.price" type="number" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Stock Level</label>
                        <input v-model="form.stock" type="number" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Category</label>
                        <select v-model="form.category_id" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required>
                            <option value="">Select Category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-white/30">Image URL</label>
                    <input v-model="form.image" type="text" placeholder="https://..." class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" />
                </div>

                <div class="flex items-center gap-4">
                    <input v-model="form.is_featured" type="checkbox" id="is_featured" class="w-6 h-6 bg-charcoal border-white/5 rounded text-red focus:ring-red" />
                    <label for="is_featured" class="text-sm font-bold text-white/50">Feature this product on homepage</label>
                </div>

                <div class="flex justify-end gap-4 pt-8">
                    <Link :href="route('admin.products.index')" class="px-8 py-4 rounded-2xl font-bold text-white/40 hover:text-white transition">Cancel</Link>
                    <button type="submit" class="bg-red hover:bg-red-light text-white px-12 py-4 rounded-2xl font-black transition shadow-xl shadow-red/20" :disabled="form.processing">
                        Create Product
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
