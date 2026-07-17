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
        <Head title="Forgot password — Laptech" />

        <div class="mb-8">
            <h2 class="text-2xl font-heading font-extrabold">Forgot your <span class="text-red">password?</span></h2>
            <p class="text-muted text-sm mt-3 leading-relaxed">
                Enter your registered email and we'll send you a reset link.
            </p>
        </div>

        <div v-if="status" class="mb-6 p-4 bg-red/10 border border-red/20 rounded-xl text-red text-sm font-bold">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <div>
                <InputLabel for="email" value="Email" />
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
                    Send reset link
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
