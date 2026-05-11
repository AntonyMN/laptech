<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    product: Object,
    categories: Array,
});

const form = useForm({
    name: props.product.name,
    slug: props.product.slug,
    description: props.product.description,
    price: props.product.price,
    stock: props.product.stock,
    category_id: props.product.category_id,
    image: props.product.image,
    is_featured: props.product.is_featured,
    specifications: [],
});

onMounted(() => {
    if (props.product.specifications) {
        // Convert associative array/object to array of {key, value}
        form.specifications = Object.entries(props.product.specifications).map(([key, value]) => ({
            key,
            value
        }));
    }
});

const addSpec = () => {
    form.specifications.push({ key: '', value: '' });
};

const removeSpec = (index) => {
    form.specifications.splice(index, 1);
};

const submit = () => {
    form.patch(route('admin.products.update', props.product.id));
};
</script>

<template>
    <AdminLayout :title="'Edit ' + product.name">
        <div class="max-w-4xl">
            <form @submit.prevent="submit" class="space-y-12">
                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-12">
                    <h3 class="text-xl font-bold mb-8">Basic Information</h3>
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

                    <div class="mt-8 space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Description</label>
                        <textarea v-model="form.description" rows="5" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required></textarea>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8 mt-8">
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

                    <div class="mt-8 space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Image URL</label>
                        <input v-model="form.image" type="text" placeholder="https://..." class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" />
                    </div>
                </div>

                <!-- Specifications -->
                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-12">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold">Specifications</h3>
                            <p class="text-white/40 text-sm">Add technical details as key-value pairs.</p>
                        </div>
                        <button type="button" @click="addSpec" class="px-4 py-2 bg-red/10 text-red rounded-xl font-bold hover:bg-red/20 transition flex items-center gap-2">
                            <i class="fas fa-plus text-xs"></i> Add Spec
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div v-for="(spec, index) in form.specifications" :key="index" class="flex gap-4 items-start">
                            <input v-model="spec.key" type="text" placeholder="Key (e.g. CPU)" class="flex-1 bg-charcoal border-white/5 rounded-xl p-3 focus:border-red transition" />
                            <input v-model="spec.value" type="text" placeholder="Value (e.g. Core i7)" class="flex-1 bg-charcoal border-white/5 rounded-xl p-3 focus:border-red transition" />
                            <button @click="removeSpec(index)" type="button" class="w-12 h-12 rounded-xl bg-white/5 text-white/30 hover:text-red transition flex items-center justify-center">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div v-if="form.specifications.length === 0" class="text-center py-8 text-white/10 italic">
                            No specifications added yet.
                        </div>
                    </div>
                </div>

                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-12">
                    <div class="flex items-center gap-4">
                        <input v-model="form.is_featured" type="checkbox" id="is_featured" class="w-6 h-6 bg-charcoal border-white/5 rounded text-red focus:ring-red" />
                        <label for="is_featured" class="text-sm font-bold text-white/50">Feature this product on homepage</label>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-8">
                    <Link :href="route('admin.products.index')" class="px-8 py-4 rounded-2xl font-bold text-white/40 hover:text-white transition">Cancel</Link>
                    <button type="submit" class="bg-red hover:bg-red-light text-white px-12 py-4 rounded-2xl font-black transition shadow-xl shadow-red/20" :disabled="form.processing">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
