<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';

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
                title: 'Transmission Received!',
                text: 'Your request is being processed by our technical specialists.',
                icon: 'success',
                confirmButtonColor: '#E31B23',
                background: '#1A1A1A',
                color: '#FFFFFF',
            });
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Request Quote - Laptech" />

    <div class="min-h-screen bg-charcoal text-white font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="py-24 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-20 items-start">
                    
                    <!-- Left Side: Contact Info & Aesthetic -->
                    <div class="space-y-12">
                        <div>
                            <h1 class="text-7xl font-heading font-black mb-8 italic tracking-tighter leading-none">
                                Request a <span class="text-red text-shadow-red">Quote.</span>
                            </h1>
                            <p class="text-xl text-white/50 leading-relaxed max-w-xl">
                                Partner with Laptech for high-performance hardware and expert IT infrastructure solutions. Our team typically responds within 24 operational hours.
                            </p>
                        </div>

                        <div class="grid gap-8">
                            <div class="flex gap-6 items-center p-8 bg-white/5 border border-white/5 rounded-[2rem] hover:border-red/30 transition duration-500 group">
                                <div class="w-14 h-14 rounded-2xl bg-red/10 flex items-center justify-center shrink-0 group-hover:bg-red transition duration-500">
                                    <i class="fas fa-map-marker-alt text-red group-hover:text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white/40 uppercase tracking-widest text-xs mb-1">Global Terminal</h4>
                                    <p class="font-bold text-lg">Tech Plaza, Nairobi, Kenya</p>
                                </div>
                            </div>

                            <div class="flex gap-6 items-center p-8 bg-white/5 border border-white/5 rounded-[2rem] hover:border-red/30 transition duration-500 group">
                                <div class="w-14 h-14 rounded-2xl bg-red/10 flex items-center justify-center shrink-0 group-hover:bg-red transition duration-500">
                                    <i class="fas fa-phone-alt text-red group-hover:text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white/40 uppercase tracking-widest text-xs mb-1">Direct Signal</h4>
                                    <p class="font-bold text-lg">+254 700 000 000</p>
                                </div>
                            </div>

                            <div class="flex gap-6 items-center p-8 bg-white/5 border border-white/5 rounded-[2rem] hover:border-red/30 transition duration-500 group">
                                <div class="w-14 h-14 rounded-2xl bg-red/10 flex items-center justify-center shrink-0 group-hover:bg-red transition duration-500">
                                    <i class="fas fa-envelope text-red group-hover:text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white/40 uppercase tracking-widest text-xs mb-1">Digital Frequency</h4>
                                    <p class="font-bold text-lg">specialists@laptech.co.ke</p>
                                </div>
                            </div>
                        </div>

                        <!-- Aesthetic Decoration -->
                        <div class="relative pt-12">
                             <div class="absolute -top-4 -left-4 w-32 h-32 bg-red/10 blur-3xl rounded-full"></div>
                             <div class="flex gap-4">
                                 <div v-for="i in 3" :key="i" class="w-12 h-1 bg-red/20 rounded-full"></div>
                                 <div class="w-24 h-1 bg-red rounded-full"></div>
                             </div>
                        </div>
                    </div>

                    <!-- Right Side: Contact Form -->
                    <div class="bg-charcoal-light border border-white/10 rounded-[3rem] p-12 md:p-16 shadow-3xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-5">
                            <i class="fas fa-paper-plane text-9xl -rotate-12"></i>
                        </div>

                        <form @submit.prevent="submitQuote" class="space-y-8 relative z-10">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30 ml-4">Select Service Type</label>
                                <select v-model="form.service_id" class="w-full bg-charcoal border-white/5 rounded-2xl p-5 focus:border-red focus:ring-red transition appearance-none" required>
                                    <option value="" disabled>Choose a service...</option>
                                    <option v-for="s in services" :key="s.id" :value="s.id">{{ s.name }}</option>
                                </select>
                            </div>

                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-white/30 ml-4">Full Name</label>
                                    <input v-model="form.name" type="text" placeholder="John Doe" class="w-full bg-charcoal border-white/5 rounded-2xl p-5 focus:border-red focus:ring-red transition" required />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-white/30 ml-4">Email Address</label>
                                    <input v-model="form.email" type="email" placeholder="john@example.com" class="w-full bg-charcoal border-white/5 rounded-2xl p-5 focus:border-red focus:ring-red transition" required />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30 ml-4">Phone Contact</label>
                                <input v-model="form.phone" type="tel" placeholder="+254..." class="w-full bg-charcoal border-white/5 rounded-2xl p-5 focus:border-red focus:ring-red transition" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30 ml-4">Project Requirements</label>
                                <textarea v-model="form.details" rows="5" placeholder="Tell us about your technical requirements..." class="w-full bg-charcoal border-white/5 rounded-2xl p-5 focus:border-red focus:ring-red transition" required></textarea>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="w-full bg-red hover:bg-red-light disabled:opacity-50 text-white font-black py-6 rounded-2xl transition shadow-2xl shadow-red/20 text-xl flex items-center justify-center gap-4 group"
                            >
                                {{ form.processing ? 'Transmitting...' : 'Initialize Request' }}
                                <i class="fas fa-arrow-right group-hover:translate-x-2 transition duration-500"></i>
                            </button>
                        </form>
                    </div>

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
    box-shadow: 0 40px 80px -15px rgba(0, 0, 0, 0.6);
}
.text-shadow-red {
    text-shadow: 0 0 20px rgba(227, 27, 35, 0.4);
}
</style>
