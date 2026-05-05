<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition text-white placeholder-white/20"
        v-model="model"
        ref="input"
    />

</template>
