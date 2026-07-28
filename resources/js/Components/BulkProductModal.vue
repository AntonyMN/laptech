<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    show: { type: Boolean, default: false },
});
const emit = defineEmits(['close']);

const fileInput = ref(null);
const file = ref(null);
const preview = ref(null);
const errorMessage = ref('');
const validating = ref(false);
const importing = ref(false);

// Read Laravel's XSRF cookie so the axios POSTs pass CSRF verification.
const xsrfToken = () => {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : '';
};

const reset = () => {
    file.value = null;
    preview.value = null;
    errorMessage.value = '';
    validating.value = false;
    importing.value = false;
    if (fileInput.value) fileInput.value.value = '';
};

const close = () => {
    reset();
    emit('close');
};

const onFileChange = (e) => {
    preview.value = null;
    errorMessage.value = '';
    file.value = e.target.files?.[0] ?? null;
    if (file.value) validate();
};

const validate = async () => {
    if (!file.value) return;
    validating.value = true;
    errorMessage.value = '';
    preview.value = null;

    const data = new FormData();
    data.append('file', file.value);

    try {
        const res = await window.axios.post(route('admin.products.import.preview'), data, {
            headers: { 'X-XSRF-TOKEN': xsrfToken() },
        });
        preview.value = res.data;
    } catch (err) {
        errorMessage.value = err.response?.data?.message
            || 'Could not read that file. Please upload the exported .xlsx spreadsheet.';
    } finally {
        validating.value = false;
    }
};

const apply = async () => {
    if (!file.value || !preview.value?.valid) return;
    importing.value = true;
    errorMessage.value = '';

    const data = new FormData();
    data.append('file', file.value);

    try {
        const res = await window.axios.post(route('admin.products.import'), data, {
            headers: { 'X-XSRF-TOKEN': xsrfToken() },
        });
        window.toastr?.success(res.data.message || 'Import complete.');
        close();
        router.reload({ only: ['products'] });
    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'The import could not be completed.';
    } finally {
        importing.value = false;
    }
};
</script>

<template>
    <Teleport to="body">
        <div v-if="show" class="fixed inset-0 z-[70] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/70" @click="close"></div>

                <div class="relative w-full max-w-lg bg-charcoal-light border border-white/10 rounded-2xl shadow-2xl text-white overflow-hidden">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-red/15 flex items-center justify-center">
                                <i class="fas fa-file-excel text-red"></i>
                            </span>
                            <div>
                                <h3 class="font-bold leading-tight">Bulk Update Products</h3>
                                <p class="text-xs text-white/40">Download, edit, then upload to apply changes</p>
                            </div>
                        </div>
                        <button @click="close" class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center text-white/50 hover:text-white transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                        <!-- Step 1: Download -->
                        <div class="rounded-xl border border-white/10 bg-charcoal p-5">
                            <div class="flex items-start gap-3">
                                <span class="text-xs font-bold w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0">1</span>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm mb-1">Download current products</h4>
                                    <p class="text-xs text-white/50 mb-4 leading-relaxed">
                                        Get an Excel sheet of every product. Edit prices, stock, status, category and image URL — keep the <strong class="text-white/70">Product ID</strong> column untouched. Leave the ID blank on a new row to add a product.
                                    </p>
                                    <a
                                        :href="route('admin.products.export')"
                                        class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 text-white px-4 py-2.5 rounded-lg text-sm font-bold transition"
                                    >
                                        <i class="fas fa-download"></i> Download spreadsheet
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Upload -->
                        <div class="rounded-xl border border-white/10 bg-charcoal p-5">
                            <div class="flex items-start gap-3">
                                <span class="text-xs font-bold w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0">2</span>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm mb-1">Upload your updated file</h4>
                                    <p class="text-xs text-white/50 mb-4 leading-relaxed">
                                        We'll check it and show you a summary before anything is saved.
                                    </p>

                                    <input ref="fileInput" type="file" accept=".xlsx,.xls,.csv" class="hidden" @change="onFileChange" />
                                    <button
                                        @click="fileInput?.click()"
                                        class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 text-white px-4 py-2.5 rounded-lg text-sm font-bold transition"
                                    >
                                        <i class="fas fa-upload"></i> {{ file ? 'Choose a different file' : 'Choose file' }}
                                    </button>
                                    <span v-if="file" class="ml-3 text-xs text-white/50">{{ file.name }}</span>

                                    <div v-if="validating" class="mt-4 text-sm text-white/60 flex items-center gap-2">
                                        <i class="fas fa-circle-notch fa-spin"></i> Checking your file…
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Error -->
                        <div v-if="errorMessage" class="rounded-xl border border-red/40 bg-red/10 p-4 text-sm text-red-light flex items-start gap-3">
                            <i class="fas fa-exclamation-circle mt-0.5"></i>
                            <span>{{ errorMessage }}</span>
                        </div>

                        <!-- Preview summary -->
                        <div v-if="preview" class="rounded-xl border border-white/10 bg-charcoal p-5 space-y-4">
                            <h4 class="font-bold text-sm flex items-center gap-2">
                                <i class="fas fa-clipboard-check text-red"></i> Preview
                            </h4>

                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="rounded-lg bg-white/5 py-3">
                                    <div class="text-xl font-extrabold">{{ preview.toUpdate }}</div>
                                    <div class="text-[10px] uppercase tracking-widest text-white/40">Update</div>
                                </div>
                                <div class="rounded-lg bg-white/5 py-3">
                                    <div class="text-xl font-extrabold text-emerald-400">{{ preview.toCreate }}</div>
                                    <div class="text-[10px] uppercase tracking-widest text-white/40">New</div>
                                </div>
                                <div class="rounded-lg bg-white/5 py-3">
                                    <div class="text-xl font-extrabold text-amber-400">{{ preview.errors.length }}</div>
                                    <div class="text-[10px] uppercase tracking-widest text-white/40">Errors</div>
                                </div>
                            </div>

                            <div v-if="preview.newCategories.length" class="text-xs text-white/60">
                                <span class="font-bold text-amber-400">New categories will be created:</span>
                                {{ preview.newCategories.join(', ') }}
                            </div>

                            <div v-if="preview.errors.length" class="space-y-1.5 max-h-40 overflow-y-auto">
                                <div
                                    v-for="err in preview.errors"
                                    :key="err.row"
                                    class="text-xs text-red-light bg-red/5 border border-red/20 rounded-lg px-3 py-2"
                                >
                                    <span class="font-bold">Row {{ err.row }}:</span> {{ err.messages.join('; ') }}
                                </div>
                            </div>

                            <p v-if="preview.valid" class="text-xs text-emerald-400 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> Looks good — ready to import.
                            </p>
                            <p v-else class="text-xs text-amber-400 flex items-center gap-2">
                                <i class="fas fa-triangle-exclamation"></i> Fix the errors above and re-upload before importing.
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-white/10 flex items-center justify-end gap-3">
                        <button @click="close" class="px-5 py-2.5 rounded-lg text-sm font-bold text-white/60 hover:text-white hover:bg-white/5 transition">
                            Cancel
                        </button>
                        <button
                            @click="apply"
                            :disabled="!preview?.valid || importing"
                            class="inline-flex items-center gap-2 bg-red hover:bg-red-light disabled:opacity-40 disabled:cursor-not-allowed text-white px-5 py-2.5 rounded-lg text-sm font-bold transition"
                        >
                            <i v-if="importing" class="fas fa-circle-notch fa-spin"></i>
                            <i v-else class="fas fa-check"></i>
                            {{ importing ? 'Importing…' : 'Apply Import' }}
                        </button>
                    </div>
                </div>
            </div>
    </Teleport>
</template>
