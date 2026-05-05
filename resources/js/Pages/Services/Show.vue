<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '../../Components/Navbar.vue';


const props = defineProps({
    service: Object,
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
                text: 'Our technical specialists will contact you within 24 hours.',
                icon: 'success',
                confirmButtonColor: '#008080',
            });
            form.reset('details', 'phone');
        },
    });
};

</script>

<template>
    <Head :title="service.name + ' - Laptech Services'" />

    <div class="min-h-screen bg-charcoal text-white font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="max-w-7xl mx-auto py-20 px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <!-- Service Info -->
                <div class="space-y-12">
                    <div>
                        <div class="w-20 h-20 bg-red rounded-3xl flex items-center justify-center mb-10 shadow-2xl shadow-red/30">
                            <i :class="service.icon" class="text-white text-4xl"></i>
                        </div>
                        <h1 class="text-6xl font-heading font-black mb-6 leading-tight">
                            {{ service.name }}
                        </h1>
                        <p class="text-xl text-white/50 leading-relaxed max-w-xl">
                            {{ service.description }} Our "Innovative Tech Hub" approach ensures you get the highest performance solutions tailored to your specific requirements.
                        </p>
                    </div>

                    <div class="grid gap-6">
                        <div v-for="i in 3" :key="i" class="flex gap-6 p-6 bg-white/5 border border-white/5 rounded-3xl items-start">
                            <div class="w-12 h-12 rounded-2xl bg-red/20 flex items-center justify-center shrink-0">
                                <i class="fas fa-check text-red"></i>
                            </div>
                            <div>
                                <h4 class="font-bold mb-2">High-Performance Standard</h4>
                                <p class="text-sm text-white/40">We use industry-leading metrics to ensure your infrastructure exceeds standard benchmarks.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quote Form -->
                <div class="bg-charcoal-light border border-white/10 rounded-[3rem] p-12 shadow-3xl">
                    <div class="mb-10">
                        <h2 class="text-3xl font-heading font-black mb-2">Request a <span class="text-red">Quote</span></h2>
                        <p class="text-white/40">Provide your details and we'll send a custom proposal.</p>
                    </div>

                    <form @submit.prevent="submitQuote" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30">Full Name</label>
                                <input v-model="form.name" type="text" placeholder="John Doe" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30">Email Address</label>
                                <input v-model="form.email" type="email" placeholder="john@example.com" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/30">Phone Number</label>
                            <input v-model="form.phone" type="tel" placeholder="+254..." class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/30">Project Details</label>
                            <textarea v-model="form.details" rows="4" placeholder="Tell us more about your requirements..." class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required></textarea>
                        </div>

                        <button type="submit" class="w-full bg-red hover:bg-red-light text-white font-black py-5 rounded-2xl transition shadow-xl shadow-red/20 text-lg flex items-center justify-center gap-3">
                            Submit Request <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </main>

        <Footer />
    </div>

</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
.shadow-3xl {
    box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.5);
}
</style>
