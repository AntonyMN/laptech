<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const page = usePage();

const props = defineProps({
    posts: Object,
    categories: Array,
    currentCategory: String,
});

const seo = computed(() => {
    const baseUrl = page.props.appUrl || '';
    return {
        title: 'Blog — Tech Tips, Guides & News | Laptech Electronics',
        description: 'Buying guides, tips and tech news to help you choose the right laptop, desktop and accessories. Expert insights from Laptech Electronics, Nairobi.',
        url: `${baseUrl}/blog`,
        image: `${baseUrl}/favicon.png`,
    };
});

const filterByCategory = (slug) => {
    router.get(route('blog.index'), { category: slug }, { preserveScroll: true });
};
</script>

<template>
    <Head :title="seo.title">
        <meta name="description" :content="seo.description" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:url" :content="seo.url" />
        <meta property="og:image" :content="seo.image" />
        <meta property="og:type" content="website" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="seo.title" />
        <meta name="twitter:description" :content="seo.description" />
        <meta name="twitter:image" :content="seo.image" />
    </Head>

    <div class="min-h-screen bg-page text-ink font-sans">
        <Navbar />

        <!-- Hero -->
        <header class="bg-surface border-b border-line py-12 px-6">
            <div class="max-w-7xl mx-auto text-center">
                <h1 class="text-3xl md:text-4xl font-heading font-extrabold mb-3">Laptech <span class="text-red">Blog</span></h1>
                <p class="text-muted max-w-2xl mx-auto">Buying guides, tips and the latest tech news.</p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto pb-20 px-6">
            <!-- Categories -->
            <div class="flex flex-wrap justify-center gap-3 py-10">
                <button
                    @click="filterByCategory('')"
                    :class="!currentCategory ? 'bg-red text-white' : 'bg-surface border border-line text-muted hover:text-ink'"
                    class="px-6 py-2.5 rounded-full font-bold transition text-sm"
                >
                    All Articles
                </button>
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="filterByCategory(cat.slug)"
                    :class="currentCategory === cat.slug ? 'bg-red text-white' : 'bg-surface border border-line text-muted hover:text-ink'"
                    class="px-6 py-2.5 rounded-full font-bold transition text-sm"
                >
                    {{ cat.name }}
                </button>
            </div>

            <!-- Posts Grid -->
            <div v-if="posts.data.length > 0" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <Link v-for="post in posts.data" :key="post.id" :href="route('blog.show', post.slug)" class="group block bg-surface border border-line rounded-2xl overflow-hidden hover:border-red/40 hover:shadow-lg transition">
                    <div class="h-52 bg-surface-muted relative overflow-hidden">
                        <img :src="post.featured_image || 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=800'" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-surface/90 backdrop-blur border border-line rounded-full text-[10px] font-bold text-red uppercase tracking-widest">
                                {{ post.category?.name }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h2 class="text-lg font-heading font-bold mb-2 group-hover:text-red transition leading-snug line-clamp-2">{{ post.title }}</h2>
                        <p class="text-muted text-sm mb-5 line-clamp-3 leading-relaxed">{{ post.excerpt }}</p>
                        <div class="flex items-center justify-between pt-4 border-t border-line">
                            <span class="text-xs text-muted">
                                {{ new Date(post.published_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                            </span>
                            <span class="text-red font-bold text-xs flex items-center gap-2 group-hover:gap-3 transition-all">
                                Read <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Empty State -->
            <div v-else class="py-24 text-center bg-surface border border-line rounded-2xl">
                <i class="fas fa-newspaper text-5xl text-line mb-5"></i>
                <h2 class="text-xl font-bold mb-2">No articles found</h2>
                <p class="text-muted">We're drafting new content. Check back soon.</p>
            </div>

            <!-- Pagination -->
            <div v-if="posts.links.length > 3" class="mt-14 flex justify-center gap-2">
                <template v-for="(link, k) in posts.links" :key="k">
                    <span v-if="link.url === null" class="px-4 py-2 text-muted text-sm font-bold" v-html="link.label" />
                    <Link
                        v-else
                        :href="link.url"
                        class="px-5 py-2 rounded-lg text-sm font-bold transition border"
                        :class="link.active ? 'bg-red border-red text-white' : 'bg-surface border-line text-muted hover:text-ink'"
                        v-html="link.label"
                    />
                </template>
            </div>
        </main>

        <Footer />
    </div>
</template>
