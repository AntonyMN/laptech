<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array,
});

const form = useForm({
    name: '',
    slug: '',
    description: '',
    features: [
        { title: 'High-Performance Standard', description: 'We use industry-leading metrics to ensure your infrastructure exceeds standard benchmarks.' },
        { title: 'Advanced Scalability', description: 'Our solutions grow with your business, ensuring long-term technological relevance.' },
        { title: 'Elite Support', description: 'Dedicated technical experts available to ensure mission-critical uptime.' },
    ],
    icon: 'fas fa-tools',
    is_featured: false,
    service_category_id: '',
});

const submit = () => {
    form.post(route('admin.services.store'));
};
</script>

<template>
    <AdminLayout title="Add New Service">
        <div class="max-w-4xl">
            <form @submit.prevent="submit" class="space-y-12">
                <!-- Basic Info -->
                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-12">
                    <h3 class="text-xl font-bold mb-8">Basic Information</h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/30">Service Name</label>
                            <input v-model="form.name" type="text" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/30">Slug</label>
                            <input v-model="form.slug" type="text" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/30">Category</label>
                            <select v-model="form.service_category_id" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required>
                                <option value="">Select Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/30">Icon Class (FontAwesome)</label>
                            <input v-model="form.icon" type="text" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                        </div>
                    </div>
                    <div class="mt-8 space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/30">Main Description</label>
                        <textarea v-model="form.description" rows="4" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required></textarea>
                    </div>
                </div>

                <!-- Dynamic Features -->
                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-12">
                    <h3 class="text-xl font-bold mb-2">Key Aspects</h3>
                    <p class="text-white/40 text-sm mb-8">Define exactly 3 key highlights for this service.</p>
                    
                    <div class="space-y-8">
                        <div v-for="(feature, index) in form.features" :key="index" class="p-8 bg-white/5 rounded-3xl space-y-4 border border-white/5">
                            <div class="flex items-center gap-4 mb-2">
                                <span class="w-8 h-8 rounded-full bg-red/20 text-red flex items-center justify-center font-black text-xs">{{ index + 1 }}</span>
                                <input v-model="feature.title" type="text" placeholder="Feature Title" class="flex-1 bg-transparent border-none focus:ring-0 font-bold text-lg p-0" />
                            </div>
                            <textarea v-model="feature.description" rows="2" placeholder="Brief description of this aspect..." class="w-full bg-charcoal/50 border-white/5 rounded-xl p-4 focus:border-red focus:ring-red transition text-sm"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] p-12">
                    <div class="flex items-center gap-4">
                        <input v-model="form.is_featured" type="checkbox" id="is_featured" class="w-6 h-6 bg-charcoal border-white/5 rounded text-red focus:ring-red" />
                        <label for="is_featured" class="text-sm font-bold text-white/50">Feature this service on homepage</label>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <Link :href="route('admin.services.index')" class="px-8 py-4 rounded-2xl font-bold text-white/40 hover:text-white transition">Cancel</Link>
                    <button type="submit" class="bg-red hover:bg-red-light text-white px-12 py-4 rounded-2xl font-black transition shadow-xl shadow-red/20" :disabled="form.processing">
                        Create Service
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
