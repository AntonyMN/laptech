<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    post: Object,
    relatedPosts: Array,
});
</script>

<template>
    <Head :title="post.title + ' - Laptech'" />

    <div class="min-h-screen bg-charcoal text-white font-sans selection:bg-red selection:text-white">
        <Navbar />

        <article>
            <!-- Hero -->
            <header class="relative h-[70vh] flex items-end pb-20 px-6 overflow-hidden">
                <img :src="post.featured_image || 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=1200'" class="absolute inset-0 w-full h-full object-cover opacity-40" />
                <div class="absolute inset-0 bg-gradient-to-t from-charcoal via-charcoal/60 to-transparent"></div>
                
                <div class="max-w-4xl mx-auto relative z-10 w-full">
                    <Link :href="route('blog.index', { category: post.category?.slug })" class="inline-block px-4 py-1 bg-red/20 border border-red/30 rounded-full text-xs font-black text-red-bright uppercase tracking-widest mb-8">
                        {{ post.category?.name }}
                    </Link>
                    <h1 class="text-5xl md:text-7xl font-black italic leading-tight tracking-tighter mb-8">{{ post.title }}</h1>
                    <div class="flex items-center gap-6 text-white/40 font-bold uppercase tracking-widest text-xs">
                        <span>{{ new Date(post.published_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-red"></span>
                        <span>Laptech Intelligence</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="max-w-4xl mx-auto py-24 px-6">
                <div class="prose prose-invert prose-red max-w-none 
                    prose-h1:font-black prose-h1:italic prose-h1:tracking-tighter
                    prose-h2:text-3xl prose-h2:font-black prose-h2:italic prose-h2:mt-16 prose-h2:mb-8
                    prose-p:text-xl prose-p:leading-relaxed prose-p:text-white/70 prose-p:mb-10
                    prose-blockquote:border-red prose-blockquote:bg-white/5 prose-blockquote:p-10 prose-blockquote:rounded-[2rem] prose-blockquote:not-italic prose-blockquote:font-bold
                    prose-img:rounded-[3rem] prose-img:shadow-2xl prose-img:my-16"
                    v-html="post.content">
                </div>
            </div>
        </article>

        <!-- Related -->
        <section v-if="relatedPosts.length > 0" class="py-24 px-6 bg-charcoal-light">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-4xl font-black italic mb-16">Related <span class="text-red">Intelligence.</span></h2>
                <div class="grid md:grid-cols-3 gap-12">
                    <Link v-for="rp in relatedPosts" :key="rp.id" :href="route('blog.show', rp.slug)" class="group block">
                        <div class="h-48 bg-charcoal rounded-[2rem] overflow-hidden mb-6 border border-white/5 group-hover:border-red/30 transition">
                            <img v-if="rp.featured_image" :src="rp.featured_image" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition duration-700" />
                        </div>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-red transition leading-tight">{{ rp.title }}</h3>
                        <p class="text-white/40 text-sm italic">{{ rp.category?.name }}</p>
                    </Link>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-32 px-6">
            <div class="max-w-5xl mx-auto bg-red rounded-[4rem] p-20 text-center relative overflow-hidden shadow-3xl">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <h2 class="text-4xl md:text-5xl font-black italic mb-8 text-white">Power your next big vision.</h2>
                <p class="text-xl text-white/80 mb-12 max-w-xl mx-auto font-bold">Our technical architects are ready to transform your infrastructure.</p>
                <Link :href="route('quotes.create')" class="bg-white text-charcoal hover:bg-charcoal hover:text-white px-12 py-5 rounded-2xl font-black text-xl transition shadow-2xl">
                    Request Architecture Quote
                </Link>
            </div>
        </section>

        <Footer />
    </div>
</template>

<style>
/* Custom prose styles for dark mode and premium look */
.prose blockquote p::before,
.prose blockquote p::after {
    content: none !important;
}
.shadow-3xl {
    box-shadow: 0 35px 60px -15px rgba(227, 27, 35, 0.3);
}
</style>
