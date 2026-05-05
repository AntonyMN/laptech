<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Key - Laptech" />

        <div class="mb-10">
            <h2 class="text-3xl font-heading font-black">Reset <span class="text-red">Key</span></h2>
            <p class="text-white/40 text-sm mt-2">Initialize a new access passphrase for your account.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Tech Email" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="name@example.com"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="New Passphrase" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autofocus
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Verify New Key" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-4">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Update Access Key
                    <i class="fas fa-key text-xs opacity-50 ml-1"></i>
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
