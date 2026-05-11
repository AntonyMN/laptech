<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    posts: Object,
    categories: Array,
    currentCategory: String,
});

const filterByCategory = (slug) => {
    router.get(route('blog.index'), { category: slug }, { preserveScroll: true });
};
</script>

<template>
    <Head title="Technical Insights & News - Laptech" />

    <div class="min-h-screen bg-charcoal text-white font-sans">
        <Navbar />

        <!-- Hero -->
        <header class="py-24 px-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(227,27,35,0.1),transparent_70%)]"></div>
            <div class="max-w-7xl mx-auto text-center relative z-10">
                <h1 class="text-6xl md:text-7xl font-black italic tracking-tighter mb-6">Technical <span class="text-red">Intelligence.</span></h1>
                <p class="text-white/40 text-xl max-w-2xl mx-auto font-bold">Deep dives into infrastructure, security, and the future of tech.</p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto pb-32 px-6">
            <!-- Categories -->
            <div class="flex flex-wrap justify-center gap-4 mb-20">
                <button 
                    @click="filterByCategory('')"
                    :class="!currentCategory ? 'bg-red text-white shadow-xl shadow-red/20' : 'bg-white/5 text-white/40 hover:bg-white/10'"
                    class="px-8 py-3 rounded-xl font-bold transition text-sm uppercase tracking-widest"
                >
                    All Articles
                </button>
                <button 
                    v-for="cat in categories" 
                    :key="cat.id"
                    @click="filterByCategory(cat.slug)"
                    :class="currentCategory === cat.slug ? 'bg-red text-white shadow-xl shadow-red/20' : 'bg-white/5 text-white/40 hover:bg-white/10'"
                    class="px-8 py-3 rounded-xl font-bold transition text-sm uppercase tracking-widest"
                >
                    {{ cat.name }}
                </button>
            </div>

            <!-- Posts Grid -->
            <div v-if="posts.data.length > 0" class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
                <div v-for="post in posts.data" :key="post.id" class="group">
                    <Link :href="route('blog.show', post.slug)" class="block bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden hover:border-red/50 transition duration-500 shadow-2xl">
                        <div class="h-64 bg-charcoal relative overflow-hidden">
                            <img v-if="post.featured_image" :src="post.featured_image" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-80" />
                            <div v-else class="w-full h-full flex items-center justify-center text-white/5">
                                <i class="fas fa-feather text-6xl"></i>
                            </div>
                            <div class="absolute top-6 left-6">
                                <span class="px-3 py-1 bg-red/20 backdrop-blur-md border border-red/30 rounded-full text-[10px] font-black text-red uppercase tracking-widest">
                                    {{ post.category?.name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-10">
                            <h2 class="text-2xl font-bold mb-4 group-hover:text-red transition leading-tight">{{ post.title }}</h2>
                            <p class="text-white/40 text-sm mb-8 line-clamp-3 leading-relaxed italic">{{ post.excerpt }}</p>
                            <div class="flex items-center justify-between pt-8 border-t border-white/5">
                                <span class="text-[10px] font-black uppercase tracking-widest text-white/20">
                                    {{ new Date(post.published_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}
                                </span>
                                <span class="text-red font-black text-xs uppercase tracking-widest flex items-center gap-2 group-hover:gap-4 transition-all">
                                    Read Article <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="py-32 text-center bg-charcoal-light border border-white/5 rounded-[3rem]">
                <i class="fas fa-search text-6xl text-white/5 mb-6"></i>
                <h2 class="text-2xl font-bold mb-2">No articles found</h2>
                <p class="text-white/40">We're currently drafting new intelligence. Check back soon.</p>
            </div>

            <!-- Pagination -->
            <div v-if="posts.links.length > 3" class="mt-20 flex justify-center gap-2">
                <template v-for="(link, k) in posts.links" :key="k">
                    <div v-if="link.url === null" class="px-4 py-2 text-white/20 text-sm font-bold uppercase tracking-widest" v-html="link.label" />
                    <Link 
                        v-else 
                        :href="link.url" 
                        class="px-6 py-2 rounded-xl text-sm font-bold transition uppercase tracking-widest" 
                        :class="link.active ? 'bg-red text-white' : 'bg-white/5 text-white/40 hover:bg-white/10'" 
                        v-html="link.label" 
                    />
                </template>
            </div>
        </main>

        <Footer />
    </div>
</template>
