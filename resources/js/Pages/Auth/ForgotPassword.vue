<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Recover Access - Laptech" />

        <div class="mb-8">
            <h2 class="text-3xl font-heading font-black">Recover <span class="text-red">Access</span></h2>
            <p class="text-white/40 text-sm mt-4 leading-relaxed">
                Lost your key? Provide your registered email and we'll transmit a recovery link.
            </p>
        </div>

        <div v-if="status" class="mb-6 p-4 bg-red/10 border border-red/20 rounded-2xl text-red text-sm font-bold">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <div>
                <InputLabel for="email" value="Recovery Email" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="name@example.com"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Transmit Recovery Link
                    <i class="fas fa-paper-plane text-xs opacity-50 ml-1"></i>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
