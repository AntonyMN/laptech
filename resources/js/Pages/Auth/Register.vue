<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Join Hub - Laptech" />

        <div class="mb-10">
            <h2 class="text-3xl font-heading font-black">Join the <span class="text-red">Hub</span></h2>
            <p class="text-white/40 text-sm mt-2">Create your account to access high-performance tech.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="name" value="Full Name" />
                <TextInput
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="John Doe"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Work Email" />
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="password" value="Passphrase" />
                    <TextInput
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Verify" />
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
            </div>

            <div class="pt-4">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Create Identity
                    <i class="fas fa-plus-circle text-xs opacity-50 ml-1"></i>
                </PrimaryButton>
            </div>

            <div class="text-center pt-6 border-t border-white/5">
                <p class="text-white/40 text-sm">
                    Already a member? 
                    <Link :href="route('login')" class="text-red font-bold hover:underline">Log in</Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
