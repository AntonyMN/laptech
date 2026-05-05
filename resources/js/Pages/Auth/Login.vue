<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Access Hub - Laptech" />

        <div class="mb-10">
            <h2 class="text-3xl font-heading font-black">Access <span class="text-red">Hub</span></h2>
            <p class="text-white/40 text-sm mt-2">Enter your credentials to continue to the tech ecosystem.</p>
        </div>

        <div v-if="status" class="mb-6 p-4 bg-red/10 border border-red/20 rounded-2xl text-red text-sm font-bold">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Tech Email" />
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
                <div class="flex justify-between items-center mb-2">
                    <InputLabel for="password" value="Access Key" class="mb-0" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-white/30 hover:text-red transition"
                    >
                        Forgot?
                    </Link>
                </div>
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer group">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-3 text-sm text-white/40 group-hover:text-white transition">Keep me active</span>
                </label>
            </div>

            <div class="pt-2">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Initialize Session
                    <i class="fas fa-terminal text-xs opacity-50 ml-1"></i>
                </PrimaryButton>
            </div>

            <div class="text-center pt-6 border-t border-white/5">
                <p class="text-white/40 text-sm">
                    New to the hub? 
                    <Link :href="route('register')" class="text-red font-bold hover:underline">Register Platform</Link>
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
