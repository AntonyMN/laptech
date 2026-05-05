<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';
import CartSidebar from '../../Components/CartSidebar.vue';


const props = defineProps({
    services: Array,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');

const applyFilters = () => {
    router.get(route('services.index'), {
        search: search.value,
        category: category.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    search.value = '';
    category.value = '';
    applyFilters();
};
</script>

<template>
    <Head title="Expert Services - Laptech" />

    <div class="min-h-screen bg-charcoal text-white font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="max-w-7xl mx-auto py-12 px-6">
            <!-- Search Section -->
            <div class="mb-16 text-center max-w-2xl mx-auto">
                <h1 class="text-5xl font-heading font-black mb-6 italic tracking-tighter">Service <span class="text-red">Matrix.</span></h1>
                <p class="text-white/40 font-bold mb-10">Advanced technical capabilities delivered with precision.</p>
                <div class="relative group">
                    <input 
                        v-model="search" 
                        @keyup.enter="applyFilters"
                        type="text" 
                        placeholder="Search for solutions (e.g. Server, Cloud, Repair)..." 
                        class="w-full bg-white/5 border-white/10 rounded-2xl px-14 py-5 focus:border-red focus:ring-red transition text-lg"
                    />
                    <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-white/20 group-focus-within:text-red transition"></i>
                    <button @click="applyFilters" class="absolute right-3 top-1/2 -translate-y-1/2 bg-red hover:bg-red-light px-6 py-2 rounded-xl font-bold transition shadow-lg shadow-red/20">
                        Execute
                    </button>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Filters Sidebar -->
                <aside class="w-full lg:w-72 shrink-0 space-y-10">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-white/20 mb-6">Service Domain</h3>
                        <div class="space-y-2">
                            <button 
                                @click="category = ''; applyFilters()"
                                :class="category === '' ? 'bg-red text-white' : 'hover:bg-white/5 text-white/40 hover:text-white'"
                                class="w-full text-left px-4 py-3 rounded-xl transition font-bold text-sm"
                            >
                                All Solutions
                            </button>
                            <button 
                                v-for="cat in categories" 
                                :key="cat.id"
                                @click="category = cat.id; applyFilters()"
                                :class="category === cat.id ? 'bg-red text-white' : 'hover:bg-white/5 text-white/40 hover:text-white'"
                                class="w-full text-left px-4 py-3 rounded-xl transition font-bold text-sm"
                            >
                                {{ cat.name }}
                            </button>
                        </div>
                    </div>

                    <div class="p-8 bg-red/10 border border-red/20 rounded-[2rem]">
                        <h4 class="font-bold mb-4">Bespoke Project?</h4>
                        <p class="text-xs text-white/50 leading-relaxed mb-6">If you don't see the specific service you need, our technicians can build a custom solution for you.</p>
                        <button class="w-full py-3 bg-red text-white rounded-xl text-xs font-black uppercase tracking-widest transition hover:bg-red-light">
                            Start Consultation
                        </button>
                    </div>

                    <button @click="clearFilters" class="w-full py-4 border border-white/5 rounded-2xl text-xs font-bold text-white/20 hover:text-red-400 transition uppercase tracking-widest">
                        Reset Filters
                    </button>
                </aside>

                <!-- Results Grid -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="text-3xl font-heading font-black">Expert <span class="text-red">Solutions</span></h2>
                        <div class="text-white/20 text-sm font-bold">{{ services.length }} capabilities found</div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-8">
                        <Link v-for="service in services" :key="service.id" :href="route('services.show', service.slug)" class="group p-10 bg-charcoal-light border border-white/5 rounded-[2.5rem] hover:bg-red hover:border-red transition-all duration-500 shadow-2xl relative overflow-hidden">
                            <!-- Background Decor -->
                            <i :class="service.icon" class="absolute -right-4 -bottom-4 text-white/5 text-9xl group-hover:text-white/10 transition duration-700"></i>
                            
                            <div class="relative z-10">
                                <div class="w-16 h-16 bg-red group-hover:bg-white rounded-2xl flex items-center justify-center mb-8 transition shadow-lg shadow-red/20">
                                    <i :class="service.icon" class="text-white group-hover:text-red text-3xl"></i>
                                </div>
                                <div class="text-xs font-black text-red group-hover:text-white/60 uppercase tracking-widest mb-2">
                                    {{ service.category?.name }}
                                </div>
                                <h3 class="text-2xl font-heading font-bold mb-4 group-hover:text-white transition">{{ service.name }}</h3>
                                <p class="text-white/50 group-hover:text-white/80 transition leading-relaxed mb-8">
                                    {{ service.description }}
                                </p>
                                <div class="flex items-center gap-2 text-red group-hover:text-white font-bold">
                                    Request Technical Quote <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </Link>

                        <div v-if="services.length === 0" class="col-span-full py-32 text-center">
                            <i class="fas fa-microscope text-6xl text-white/5 mb-6"></i>
                            <p class="text-white/20 font-bold text-xl uppercase tracking-widest italic">No service capabilities matching search</p>
                            <button @click="clearFilters" class="mt-6 text-red font-black hover:underline">Clear all filters</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <Footer />
        <CartSidebar />
    </div>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
