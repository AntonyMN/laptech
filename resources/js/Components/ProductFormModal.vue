<script setup>
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import QuillEditor from '@/Components/QuillEditor.vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    product: { type: Object, default: null },
    categories: { type: Array, default: () => [] },
});
const emit = defineEmits(['close']);

const isEdit = computed(() => !!props.product);
let slugTouched = false;

const form = useForm({
    name: '',
    slug: '',
    description: '',
    price: '',
    stock: '',
    category_id: '',
    image: '',
    image_file: null,
    is_featured: false,
    status: 'Brand new',
    specifications: [],
});

const slugify = (s) => (s || '')
    .toLowerCase().trim()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');

// Populate whenever the modal is opened.
watch(() => props.show, (open) => {
    if (!open) return;
    form.clearErrors();
    const p = props.product;
    if (p) {
        form.name = p.name;
        form.slug = p.slug;
        form.description = p.description || '';
        form.price = p.price;
        form.stock = p.stock;
        form.category_id = p.category_id;
        form.image = p.image || '';
        form.image_file = null;
        form.is_featured = !!p.is_featured;
        form.status = p.status || 'Brand new';
        form.specifications = p.specifications
            ? Object.entries(p.specifications).map(([key, value]) => ({ key, value }))
            : [];
        slugTouched = true;
    } else {
        form.reset();
        form.specifications = [];
        slugTouched = false;
    }
});

// Auto-fill slug from name until the user edits it (create mode only).
watch(() => form.name, (name) => {
    if (!slugTouched) form.slug = slugify(name);
});

const imagePreview = computed(() => {
    if (form.image_file) return URL.createObjectURL(form.image_file);
    return form.image || '';
});

const onImageFile = (e) => {
    form.image_file = e.target.files?.[0] ?? null;
};

const addSpec = () => form.specifications.push({ key: '', value: '' });
const removeSpec = (i) => form.specifications.splice(i, 1);

const close = () => emit('close');

const submit = () => {
    const options = {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            window.toastr?.success(isEdit.value ? 'Product updated.' : 'Product created.');
            close();
        },
    };

    if (isEdit.value) {
        form
            .transform((data) => ({ ...data, _method: 'PATCH' }))
            .post(route('admin.products.update', props.product.id), options);
    } else {
        form.post(route('admin.products.store'), options);
    }
};
</script>

<template>
    <Teleport to="body">
        <div v-if="show" class="fixed inset-0 z-[70] flex items-start justify-center p-4 sm:p-6 overflow-y-auto">
                <div class="fixed inset-0 bg-black/75" @click="close"></div>

                <form
                    @submit.prevent="submit"
                    class="relative w-full max-w-3xl my-4 bg-charcoal-light border border-white/10 rounded-2xl shadow-2xl text-white flex flex-col max-h-[92vh]"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-5 border-b border-white/10 shrink-0">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-red/15 flex items-center justify-center">
                                <i class="fas" :class="isEdit ? 'fa-pen' : 'fa-plus'" :style="{ color: '#F05423' }"></i>
                            </span>
                            <div>
                                <h3 class="font-bold leading-tight">{{ isEdit ? 'Edit Product' : 'Add Product' }}</h3>
                                <p class="text-xs text-white/40">{{ isEdit ? product.name : 'Create a new catalog item' }}</p>
                            </div>
                        </div>
                        <button type="button" @click="close" class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center text-white/50 hover:text-white transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-6 overflow-y-auto">
                        <!-- Name + slug -->
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/40">Product Name</label>
                                <input v-model="form.name" type="text" class="w-full bg-charcoal border border-white/10 rounded-xl p-3 focus:border-red focus:ring-0 transition" required />
                                <p v-if="form.errors.name" class="text-xs text-red-light">{{ form.errors.name }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/40">Slug</label>
                                <input v-model="form.slug" @input="slugTouched = true" type="text" class="w-full bg-charcoal border border-white/10 rounded-xl p-3 focus:border-red focus:ring-0 transition" required />
                                <p v-if="form.errors.slug" class="text-xs text-red-light">{{ form.errors.slug }}</p>
                            </div>
                        </div>

                        <!-- Description (WYSIWYG) -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase tracking-widest text-white/40">Description</label>
                            <QuillEditor v-model="form.description" placeholder="Describe the product — use bold, lists, headings…" />
                            <p v-if="form.errors.description" class="text-xs text-red-light">{{ form.errors.description }}</p>
                        </div>

                        <!-- Price / stock / category / status -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/40">Price (Ksh)</label>
                                <input v-model="form.price" type="number" min="0" class="w-full bg-charcoal border border-white/10 rounded-xl p-3 focus:border-red focus:ring-0 transition" required />
                                <p v-if="form.errors.price" class="text-xs text-red-light">{{ form.errors.price }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/40">Stock</label>
                                <input v-model="form.stock" type="number" min="0" class="w-full bg-charcoal border border-white/10 rounded-xl p-3 focus:border-red focus:ring-0 transition" required />
                                <p v-if="form.errors.stock" class="text-xs text-red-light">{{ form.errors.stock }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/40">Category</label>
                                <select v-model="form.category_id" class="w-full bg-charcoal border border-white/10 rounded-xl p-3 focus:border-red focus:ring-0 transition" required>
                                    <option value="">Select…</option>
                                    <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                                </select>
                                <p v-if="form.errors.category_id" class="text-xs text-red-light">{{ form.errors.category_id }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/40">Condition</label>
                                <select v-model="form.status" class="w-full bg-charcoal border border-white/10 rounded-xl p-3 focus:border-red focus:ring-0 transition" required>
                                    <option value="Brand new">Brand new</option>
                                    <option value="Ex-UK">Ex-UK</option>
                                    <option value="Certified Refurbished">Certified Refurbished</option>
                                </select>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="grid md:grid-cols-[auto_1fr] gap-5 items-start">
                            <div class="w-24 h-24 rounded-xl bg-charcoal border border-white/10 overflow-hidden flex items-center justify-center shrink-0">
                                <img v-if="imagePreview" :src="imagePreview" alt="preview" class="w-full h-full object-cover" />
                                <i v-else class="fas fa-image text-white/20 text-2xl"></i>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-5 flex-1">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold uppercase tracking-widest text-white/40">Upload Image</label>
                                    <input @change="onImageFile" type="file" accept="image/*" class="w-full bg-charcoal border border-white/10 rounded-xl p-2.5 text-sm text-white/70 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-red file:text-white file:font-bold file:cursor-pointer" />
                                    <p v-if="form.errors.image_file" class="text-xs text-red-light">{{ form.errors.image_file }}</p>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold uppercase tracking-widest text-white/40">Or Image URL</label>
                                    <input v-model="form.image" type="text" placeholder="https://…" class="w-full bg-charcoal border border-white/10 rounded-xl p-3 focus:border-red focus:ring-0 transition" />
                                </div>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div class="rounded-xl border border-white/10 bg-charcoal p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="font-bold text-sm">Specifications</h4>
                                    <p class="text-white/40 text-xs">Technical details as key–value pairs.</p>
                                </div>
                                <button type="button" @click="addSpec" class="px-3 py-1.5 bg-red/10 text-red rounded-lg font-bold text-xs hover:bg-red/20 transition flex items-center gap-2">
                                    <i class="fas fa-plus text-[10px]"></i> Add
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div v-for="(spec, index) in form.specifications" :key="index" class="flex gap-3 items-center">
                                    <input v-model="spec.key" type="text" placeholder="Key (e.g. CPU)" class="flex-1 bg-charcoal-light border border-white/10 rounded-lg p-2.5 text-sm focus:border-red focus:ring-0 transition" />
                                    <input v-model="spec.value" type="text" placeholder="Value (e.g. Core i7)" class="flex-1 bg-charcoal-light border border-white/10 rounded-lg p-2.5 text-sm focus:border-red focus:ring-0 transition" />
                                    <button type="button" @click="removeSpec(index)" class="w-10 h-10 rounded-lg bg-white/5 text-white/30 hover:text-red transition flex items-center justify-center shrink-0">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <p v-if="form.specifications.length === 0" class="text-center py-3 text-white/20 italic text-sm">No specifications yet.</p>
                            </div>
                        </div>

                        <!-- Featured -->
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input v-model="form.is_featured" type="checkbox" class="w-5 h-5 bg-charcoal border-white/20 rounded text-red focus:ring-red" />
                            <span class="text-sm font-bold text-white/60">Feature this product on the homepage</span>
                        </label>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-white/10 flex items-center justify-end gap-3 shrink-0">
                        <button type="button" @click="close" class="px-5 py-2.5 rounded-lg text-sm font-bold text-white/60 hover:text-white hover:bg-white/5 transition">
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 bg-red hover:bg-red-light disabled:opacity-40 disabled:cursor-not-allowed text-white px-6 py-2.5 rounded-lg text-sm font-bold transition"
                        >
                            <i v-if="form.processing" class="fas fa-circle-notch fa-spin"></i>
                            <i v-else class="fas fa-check"></i>
                            {{ isEdit ? 'Save Changes' : 'Create Product' }}
                        </button>
                    </div>
                </form>
            </div>
    </Teleport>
</template>
