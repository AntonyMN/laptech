<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    posts: Array,
});

const form = useForm({});

const deletePost = (id) => {
    if (confirm('Are you sure you want to delete this post?')) {
        form.delete(route('admin.blog-posts.delete', id));
    }
};
</script>

<template>
    <Head title="Blog Posts - Admin" />

    <AdminLayout>
        <div class="flex items-center justify-between mb-12">
            <h1 class="text-4xl font-black italic tracking-tighter">Editorial <span class="text-red">Archive.</span></h1>
            <Link :href="route('admin.blog-posts.create')" class="bg-red hover:bg-red-light text-white px-8 py-4 rounded-2xl font-black transition shadow-xl shadow-red/20 uppercase tracking-widest text-xs flex items-center gap-3">
                <i class="fas fa-plus"></i> New Article
            </Link>
        </div>

        <div class="grid gap-6">
            <div v-for="post in posts" :key="post.id" class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-6 flex flex-col md:flex-row gap-8 items-center group hover:border-red/30 transition">
                <div class="w-full md:w-48 h-32 rounded-2xl overflow-hidden shrink-0 bg-charcoal relative">
                    <img v-if="post.featured_image" :src="post.featured_image" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" />
                    <div v-else class="w-full h-full flex items-center justify-center text-white/10">
                        <i class="fas fa-image text-4xl"></i>
                    </div>
                    <div v-if="!post.is_published" class="absolute inset-0 bg-charcoal/60 backdrop-blur-sm flex items-center justify-center">
                        <span class="text-[10px] font-black uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full text-white">Draft</span>
                    </div>
                </div>

                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-[10px] font-black uppercase tracking-widest text-red bg-red/10 px-3 py-1 rounded-full">
                            {{ post.category?.name }}
                        </span>
                        <span class="text-[10px] font-bold text-white/20 uppercase tracking-widest">
                            {{ post.published_at ? new Date(post.published_at).toLocaleDateString() : 'Unpublished' }}
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">{{ post.title }}</h3>
                    <p class="text-white/40 text-sm line-clamp-1 italic">{{ post.excerpt }}</p>
                </div>

                <div class="flex gap-2 shrink-0">
                    <Link :href="route('admin.blog-posts.edit', post.id)" class="w-12 h-12 bg-white/5 hover:bg-red rounded-2xl flex items-center justify-center transition">
                        <i class="fas fa-edit"></i>
                    </Link>
                    <button @click="deletePost(post.id)" class="w-12 h-12 bg-white/5 hover:bg-red rounded-2xl flex items-center justify-center transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div v-if="posts.length === 0" class="py-32 text-center bg-white/5 rounded-[3rem] border border-dashed border-white/10">
                <i class="fas fa-feather text-6xl text-white/5 mb-6"></i>
                <p class="text-white/20 font-bold uppercase tracking-widest italic text-xl">The editorial archive is empty</p>
            </div>
        </div>
    </AdminLayout>
</template>
