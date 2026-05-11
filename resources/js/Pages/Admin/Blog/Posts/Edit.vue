<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import QuillEditor from '@/Components/QuillEditor.vue';

const props = defineProps({
    post: Object,
    categories: Array,
});

const form = useForm({
    blog_category_id: props.post.blog_category_id,
    title: props.post.title,
    slug: props.post.slug,
    excerpt: props.post.excerpt,
    content: props.post.content,
    image_file: null,
    is_published: props.post.is_published,
    _method: 'PATCH',
});

const generateSlug = () => {
    form.slug = form.title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
};

const submit = () => {
    form.post(route('admin.blog-posts.update', props.post.id));
};
</script>

<template>
    <Head title="Edit Blog Post - Admin" />

    <AdminLayout>
        <div class="flex items-center gap-6 mb-12">
            <Link :href="route('admin.blog-posts.index')" class="w-12 h-12 bg-white/5 hover:bg-white/10 rounded-2xl flex items-center justify-center transition">
                <i class="fas fa-arrow-left"></i>
            </Link>
            <h1 class="text-4xl font-black italic tracking-tighter">Edit <span class="text-red">Article.</span></h1>
        </div>

        <form @submit.prevent="submit" class="grid lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-charcoal-light border border-white/5 rounded-[3rem] p-10 space-y-8">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Title</label>
                        <input v-model="form.title" @input="generateSlug" type="text" placeholder="Title" class="w-full bg-charcoal border-white/5 rounded-2xl p-5 text-xl font-bold focus:border-red focus:ring-red transition" required />
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Slug</label>
                        <input v-model="form.slug" type="text" placeholder="slug" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Excerpt</label>
                        <textarea v-model="form.excerpt" rows="3" placeholder="summary" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Content</label>
                        <QuillEditor v-model="form.content" />
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-charcoal-light border border-white/5 rounded-[3rem] p-10 space-y-8">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Category</label>
                        <select v-model="form.blog_category_id" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Featured Image</label>
                        <div v-if="post.featured_image" class="mb-4 rounded-2xl overflow-hidden h-32 relative group">
                            <img :src="post.featured_image" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-red/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                <span class="text-white font-bold text-xs">Change Image</span>
                            </div>
                        </div>
                        <div class="relative h-24 bg-charcoal border border-dashed border-white/10 rounded-2xl flex items-center justify-center overflow-hidden">
                            <input @input="form.image_file = $event.target.files[0]" type="file" class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt text-xl text-white/10 mb-1"></i>
                                <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest">Replace image</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-sm font-bold">Published</span>
                        <button type="button" @click="form.is_published = !form.is_published" :class="form.is_published ? 'bg-red' : 'bg-white/10'" class="w-12 h-6 rounded-full relative transition duration-300">
                            <div :class="form.is_published ? 'translate-x-6' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"></div>
                        </button>
                    </div>

                    <button type="submit" class="w-full bg-red hover:bg-red-light text-white font-black py-5 rounded-2xl transition shadow-xl shadow-red/20 uppercase tracking-widest text-sm" :disabled="form.processing">
                        {{ form.processing ? 'Updating...' : 'Update Article' }}
                    </button>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
