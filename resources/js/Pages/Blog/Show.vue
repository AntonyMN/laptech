<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const page = usePage();

const props = defineProps({
    post: Object,
    relatedPosts: Array,
});

const seo = computed(() => {
    let baseUrl = page.props.appUrl || 'https://laptech.co.ke';
    if (baseUrl.includes('localhost') && typeof window !== 'undefined') {
        baseUrl = window.location.origin;
    } else if (baseUrl.includes('localhost')) {
        baseUrl = 'https://laptech.co.ke';
    }
    if (baseUrl.endsWith('/')) {
        baseUrl = baseUrl.slice(0, -1);
    }

    const imagePath = props.post.featured_image
        ? (props.post.featured_image.startsWith('/') ? props.post.featured_image : `/${props.post.featured_image}`)
        : '/favicon.png';

    const postImage = imagePath.startsWith('http') ? imagePath : `${baseUrl}${imagePath}`;

    return {
        title: `${props.post.title} — Laptech Blog`,
        description: props.post.excerpt?.substring(0, 160) || props.post.title,
        url: `${baseUrl}/blog/${props.post.slug}`,
        image: postImage,
    };
});
</script>

<template>
    <Head :title="seo.title">
        <meta name="description" :content="seo.description" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:url" :content="seo.url" />
        <meta property="og:image" :content="seo.image" />
        <meta property="og:type" content="article" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="seo.title" />
        <meta name="twitter:description" :content="seo.description" />
        <meta name="twitter:image" :content="seo.image" />
    </Head>

    <div class="min-h-screen bg-page text-ink font-sans selection:bg-red selection:text-white">
        <Navbar />

        <article>
            <!-- Hero -->
            <header class="bg-surface border-b border-line">
                <div class="max-w-4xl mx-auto px-6 py-12">
                    <nav class="text-xs text-muted mb-6">
                        <Link :href="route('welcome')" class="hover:text-red">Home</Link>
                        <span class="mx-2">/</span>
                        <Link :href="route('blog.index')" class="hover:text-red">Blog</Link>
                    </nav>
                    <Link :href="route('blog.index', { category: post.category?.slug })" class="inline-block px-3 py-1 bg-red/10 rounded-full text-xs font-bold text-red uppercase tracking-widest mb-5">
                        {{ post.category?.name }}
                    </Link>
                    <h1 class="text-3xl md:text-5xl font-heading font-extrabold leading-tight mb-5">{{ post.title }}</h1>
                    <div class="flex items-center gap-4 text-muted text-sm">
                        <span>{{ new Date(post.published_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-red"></span>
                        <span>Laptech Electronics</span>
                    </div>
                </div>
                <div v-if="post.featured_image" class="max-w-4xl mx-auto px-6 pb-12">
                    <img :src="post.featured_image" class="w-full rounded-2xl border border-line" />
                </div>
            </header>

            <!-- Content -->
            <div class="max-w-3xl mx-auto py-14 px-6">
                <div class="prose prose-lg max-w-none
                    prose-headings:font-heading prose-headings:font-bold prose-headings:text-ink
                    prose-p:text-muted prose-p:leading-relaxed
                    prose-a:text-red prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-ink
                    prose-blockquote:border-red prose-blockquote:bg-surface prose-blockquote:not-italic
                    prose-img:rounded-2xl"
                    v-html="post.content">
                </div>
            </div>
        </article>

        <!-- Related -->
        <section v-if="relatedPosts.length > 0" class="py-16 px-6 bg-surface border-t border-line">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-heading font-extrabold mb-8">Related Articles</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <Link v-for="rp in relatedPosts" :key="rp.id" :href="route('blog.show', rp.slug)" class="group block">
                        <div class="h-44 bg-surface-muted rounded-2xl overflow-hidden mb-4 border border-line group-hover:border-red/40 transition">
                            <img v-if="rp.featured_image" :src="rp.featured_image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                        </div>
                        <h3 class="font-heading font-bold mb-1 group-hover:text-red transition leading-snug line-clamp-2">{{ rp.title }}</h3>
                        <p class="text-muted text-sm">{{ rp.category?.name }}</p>
                    </Link>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="px-6 py-16">
            <div class="max-w-5xl mx-auto bg-gradient-to-br from-red to-red-dark rounded-3xl p-10 md:p-14 text-center text-white shadow-xl">
                <h2 class="text-2xl md:text-3xl font-heading font-extrabold mb-4">Ready to shop?</h2>
                <p class="text-lg text-white/80 mb-8 max-w-xl mx-auto">Browse our latest laptops, desktops and accessories.</p>
                <Link :href="route('products.index')" class="inline-block bg-white text-red hover:bg-charcoal hover:text-white px-8 py-3.5 rounded-full font-bold transition">
                    Shop Now
                </Link>
            </div>
        </section>

        <Footer />
    </div>
</template>
