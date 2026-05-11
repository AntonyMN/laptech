<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: Array,
});

const editingCategory = ref(null);

const form = useForm({
    name: '',
    slug: '',
});

const edit = (category) => {
    editingCategory.value = category;
    form.name = category.name;
    form.slug = category.slug;
};

const cancelEdit = () => {
    editingCategory.value = null;
    form.reset();
};

const submit = () => {
    if (editingCategory.value) {
        form.patch(route('admin.service-categories.update', editingCategory.value.id), {
            onSuccess: () => cancelEdit(),
        });
    } else {
        form.post(route('admin.service-categories.store'), {
            onSuccess: () => form.reset(),
        });
    }
};

const deleteCategory = (id) => {
    if (confirm('Are you sure?')) {
        form.delete(route('admin.service-categories.delete', id));
    }
};
</script>

<template>
    <AdminLayout title="Service Categories">
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Form -->
            <div class="lg:col-span-1">
                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-10 sticky top-12">
                    <h3 class="text-xl font-bold mb-8">{{ editingCategory ? 'Edit' : 'Add' }} Category</h3>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/30">Name</label>
                            <input v-model="form.name" type="text" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/30">Slug</label>
                            <input v-model="form.slug" type="text" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="flex-1 bg-red hover:bg-red-light text-white py-4 rounded-2xl font-black transition shadow-xl shadow-red/20" :disabled="form.processing">
                                {{ editingCategory ? 'Update' : 'Create' }}
                            </button>
                            <button v-if="editingCategory" @click="cancelEdit" type="button" class="px-6 py-4 rounded-2xl bg-white/5 hover:bg-white/10 transition font-bold">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List -->
            <div class="lg:col-span-2">
                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5 text-xs uppercase tracking-widest text-white/40 font-bold">
                                <th class="px-10 py-6">Category</th>
                                <th class="px-10 py-6">Services</th>
                                <th class="px-10 py-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-for="category in categories" :key="category.id" class="hover:bg-white/5 transition group">
                                <td class="px-10 py-6">
                                    <div class="font-bold">{{ category.name }}</div>
                                    <div class="text-xs text-white/30">{{ category.slug }}</div>
                                </td>
                                <td class="px-10 py-6">
                                    <span class="px-3 py-1 bg-white/5 rounded-full text-xs font-bold">{{ category.services_count }} services</span>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="edit(category)" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-red transition flex items-center justify-center">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button @click="deleteCategory(category.id)" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-red-500 transition flex items-center justify-center">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
