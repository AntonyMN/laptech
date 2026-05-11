<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    categories: Array,
});

const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    name: '',
    slug: '',
    description: '',
});

const edit = (category) => {
    isEditing.value = true;
    editingId.value = category.id;
    form.name = category.name;
    form.slug = category.slug;
    form.description = category.description;
};

const reset = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
};

const submit = () => {
    if (isEditing.value) {
        form.patch(route('admin.blog-categories.update', editingId.value), {
            onSuccess: () => reset(),
        });
    } else {
        form.post(route('admin.blog-categories.store'), {
            onSuccess: () => reset(),
        });
    }
};

const deleteCategory = (id) => {
    if (confirm('Are you sure? This will delete all posts in this category.')) {
        form.delete(route('admin.blog-categories.delete', id));
    }
};

const generateSlug = () => {
    form.slug = form.name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
};
</script>

<template>
    <Head title="Blog Categories - Admin" />

    <AdminLayout>
        <div class="flex items-center justify-between mb-12">
            <h1 class="text-4xl font-black italic tracking-tighter">Blog <span class="text-red">Categories.</span></h1>
        </div>

        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Form -->
            <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-10 h-fit sticky top-10">
                <h2 class="text-xl font-bold mb-8 italic">{{ isEditing ? 'Edit' : 'Add' }} Category</h2>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Name</label>
                        <input v-model="form.name" @input="generateSlug" type="text" placeholder="e.g. Infrastructure" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Slug</label>
                        <input v-model="form.slug" type="text" placeholder="infrastructure" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Description</label>
                        <textarea v-model="form.description" rows="4" placeholder="Brief description..." class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition"></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-red hover:bg-red-light text-white font-black py-4 rounded-2xl transition shadow-xl shadow-red/20 uppercase tracking-widest text-xs">
                            {{ isEditing ? 'Update' : 'Create' }}
                        </button>
                        <button v-if="isEditing" @click="reset" type="button" class="px-6 bg-white/5 hover:bg-white/10 rounded-2xl transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="lg:col-span-2 space-y-4">
                <div v-for="category in categories" :key="category.id" class="bg-charcoal-light border border-white/5 rounded-[2rem] p-8 flex items-center justify-between group hover:border-red/30 transition">
                    <div>
                        <h3 class="text-xl font-bold mb-1">{{ category.name }}</h3>
                        <p class="text-white/20 text-xs font-bold uppercase tracking-widest">{{ category.slug }} • {{ category.posts_count }} posts</p>
                    </div>
                    <div class="flex gap-2">
                        <button @click="edit(category)" class="w-10 h-10 bg-white/5 hover:bg-red rounded-xl flex items-center justify-center transition">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <button @click="deleteCategory(category.id)" class="w-10 h-10 bg-white/5 hover:bg-red rounded-xl flex items-center justify-center transition">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>

                <div v-if="categories.length === 0" class="py-20 text-center bg-white/5 rounded-[2.5rem] border border-dashed border-white/10">
                    <p class="text-white/20 font-bold uppercase tracking-widest italic text-sm">No categories established yet</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
