<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Verify email — Laptech" />

        <div class="mb-8">
            <h2 class="text-2xl font-heading font-extrabold">Verify your <span class="text-red">email</span></h2>
            <p class="text-muted text-sm mt-3 leading-relaxed">
                Almost there! We've sent a verification link to your email. Please click it to activate your account.
            </p>
        </div>

        <div
            class="mb-8 p-4 bg-red/10 border border-red/20 rounded-xl text-red text-sm font-bold"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent. Check your inbox.
        </div>

        <form @submit.prevent="submit">
            <div class="space-y-6">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend verification email
                </PrimaryButton>

                <div class="text-center pt-6 border-t border-line">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-muted hover:text-red transition"
                    >
                        Log out
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
