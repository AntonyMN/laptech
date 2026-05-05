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
        <Head title="Verify Identity - Laptech" />

        <div class="mb-8">
            <h2 class="text-3xl font-heading font-black">Verify <span class="text-red">Identity</span></h2>
            <p class="text-white/40 text-sm mt-4 leading-relaxed">
                Almost there! We've dispatched a verification signal to your email. Please activate it to initialize your profile.
            </p>
        </div>

        <div
            class="mb-8 p-4 bg-red/10 border border-red/20 rounded-2xl text-red text-sm font-bold"
            v-if="verificationLinkSent"
        >
            Signal re-transmitted. Check your inbox for the activation link.
        </div>

        <form @submit.prevent="submit">
            <div class="space-y-6">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Signal
                    <i class="fas fa-sync-alt text-xs opacity-50 ml-1"></i>
                </PrimaryButton>

                <div class="text-center pt-6 border-t border-white/5">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-white/30 hover:text-red-400 transition"
                    >
                        De-authenticate Session
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
