<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';

const page = usePage();

const props = defineProps({
    service: Object,
});

const seo = computed(() => {
    const baseUrl = page.props.appUrl || '';
    return {
        title: `${props.service.name} — Laptech IT Services`,
        description: props.service.description?.substring(0, 160) || `Professional ${props.service.name} services by certified Laptech technicians in Nairobi, Kenya.`,
        url: `${baseUrl}/services/${props.service.slug}`,
        image: `${baseUrl}/favicon.png`,
    };
});

const form = useForm({
    service_id: props.service.id,
    name: '',
    email: '',
    phone: '',
    details: '',
});

const submitQuote = () => {
    form.post(route('quotes.store'), {
        onSuccess: () => {
            window.swal.fire({
                title: 'Quote Requested!',
                text: 'Our specialists will contact you within 24 hours.',
                icon: 'success',
                confirmButtonColor: '#F05423',
            });
            form.reset('details', 'phone');
        },
    });
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

    <div class="min-h-screen bg-page text-ink font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="max-w-7xl mx-auto py-14 px-6">
            <nav class="text-xs text-muted mb-8">
                <Link :href="route('welcome')" class="hover:text-red">Home</Link>
                <span class="mx-2">/</span>
                <Link :href="route('services.index')" class="hover:text-red">Services</Link>
                <span class="mx-2">/</span>
                <span class="text-ink font-semibold">{{ service.name }}</span>
            </nav>

            <div class="grid lg:grid-cols-2 gap-12 items-start">
                <!-- Service Info -->
                <div class="space-y-10">
                    <div>
                        <div class="w-16 h-16 bg-red rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-red/30">
                            <i :class="service.icon" class="text-white text-3xl"></i>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-heading font-extrabold mb-4 leading-tight">
                            {{ service.name }}
                        </h1>
                        <p class="text-lg text-muted leading-relaxed max-w-xl">
                            {{ service.description }}
                        </p>
                    </div>

                    <div v-if="service.features" class="grid gap-4">
                        <div v-for="(feature, index) in service.features" :key="index" class="flex gap-5 p-5 bg-surface border border-line rounded-2xl items-start">
                            <div class="w-11 h-11 rounded-xl bg-red/10 flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-red"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-1 text-ink">{{ feature.title }}</h4>
                                <p class="text-sm text-muted">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quote Form -->
                <div class="bg-surface border border-line rounded-3xl p-8 md:p-10 shadow-xl lg:sticky lg:top-40">
                    <div class="mb-8">
                        <h2 class="text-2xl font-heading font-extrabold mb-2">Request a <span class="text-red">Quote</span></h2>
                        <p class="text-muted text-sm">Provide your details and we'll send a custom proposal.</p>
                    </div>

                    <form @submit.prevent="submitQuote" class="space-y-5">
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Full Name</label>
                                <input v-model="form.name" type="text" placeholder="John Doe" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Email Address</label>
                                <input v-model="form.email" type="email" placeholder="john@example.com" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-widest text-muted">Phone Number</label>
                            <input v-model="form.phone" type="tel" placeholder="+254…" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" />
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-widest text-muted">Project Details</label>
                            <textarea v-model="form.details" rows="4" placeholder="Tell us more about your requirements…" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required></textarea>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full bg-red hover:bg-red-dark text-white font-bold py-4 rounded-xl transition shadow-lg shadow-red/20 flex items-center justify-center gap-3 disabled:opacity-60">
                            Submit Request <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
