<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';

const page = usePage();

const seo = computed(() => {
    const baseUrl = page.props.appUrl || '';
    return {
        title: 'Request a Quote — Laptech Electronics',
        description: 'Get a custom quote for hardware and IT services from Laptech Electronics, Nairobi. Our team responds within 24 hours.',
        url: `${baseUrl}/quotes/create`,
        image: `${baseUrl}/favicon.png`,
    };
});

defineProps({
    services: Array,
});

const form = useForm({
    service_id: '',
    name: '',
    email: '',
    phone: '',
    details: '',
});

const submitQuote = () => {
    form.post(route('quotes.store'), {
        onSuccess: () => {
            window.swal.fire({
                title: 'Request Received!',
                text: 'Our team will get back to you within 24 hours.',
                icon: 'success',
                confirmButtonColor: '#F05423',
            });
            form.reset();
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

        <main class="py-14 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-start">

                    <!-- Left: Contact Info -->
                    <div class="space-y-8">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-heading font-extrabold mb-4 leading-tight">
                                Request a <span class="text-red">Quote</span>
                            </h1>
                            <p class="text-lg text-muted leading-relaxed max-w-xl">
                                Tell us what you need — hardware, repairs or a custom setup — and our team will get back to you within 24 hours.
                            </p>
                        </div>

                        <div class="grid gap-4">
                            <div class="flex gap-5 items-center p-5 bg-surface border border-line rounded-2xl">
                                <div class="w-12 h-12 rounded-xl bg-red/10 flex items-center justify-center shrink-0">
                                    <i class="fas fa-map-marker-alt text-red text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-muted uppercase tracking-widest text-xs mb-0.5">Location</h4>
                                    <p class="font-bold text-ink">Nairobi, Kenya</p>
                                </div>
                            </div>

                            <a href="tel:+254722964566" class="flex gap-5 items-center p-5 bg-surface border border-line rounded-2xl hover:border-red/40 transition">
                                <div class="w-12 h-12 rounded-xl bg-red/10 flex items-center justify-center shrink-0">
                                    <i class="fas fa-phone-alt text-red text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-muted uppercase tracking-widest text-xs mb-0.5">Phone</h4>
                                    <p class="font-bold text-ink">+254 722 964566</p>
                                </div>
                            </a>

                            <a href="mailto:info@laptech.co.ke" class="flex gap-5 items-center p-5 bg-surface border border-line rounded-2xl hover:border-red/40 transition">
                                <div class="w-12 h-12 rounded-xl bg-red/10 flex items-center justify-center shrink-0">
                                    <i class="fas fa-envelope text-red text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-muted uppercase tracking-widest text-xs mb-0.5">Email</h4>
                                    <p class="font-bold text-ink">info@laptech.co.ke</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Right: Form -->
                    <div class="bg-surface border border-line rounded-3xl p-8 md:p-10 shadow-xl">
                        <form @submit.prevent="submitQuote" class="space-y-6">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Service Type</label>
                                <select v-model="form.service_id" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required>
                                    <option value="" disabled>Choose a service…</option>
                                    <option v-for="s in services" :key="s.id" :value="s.id">{{ s.name }}</option>
                                </select>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
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
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Phone</label>
                                <input v-model="form.phone" type="tel" placeholder="+254…" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" />
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Details</label>
                                <textarea v-model="form.details" rows="5" placeholder="Tell us about your requirements…" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required></textarea>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full bg-red hover:bg-red-dark disabled:opacity-50 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-red/20 flex items-center justify-center gap-3 group"
                            >
                                {{ form.processing ? 'Sending…' : 'Submit Request' }}
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>
