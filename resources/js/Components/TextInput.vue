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
        class="w-full bg-surface-muted border border-line rounded-xl p-3.5 focus:border-red focus:ring-0 transition text-ink placeholder-muted"
        v-model="model"
        ref="input"
    />

</template>
