<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const props = defineProps({
    modelValue: String,
    placeholder: { type: String, default: 'Write here…' },
});

const emit = defineEmits(['update:modelValue']);

const container = ref(null);
let quill = null;
let internalChange = false;

onMounted(() => {
    quill = new Quill(container.value, {
        theme: 'snow',
        placeholder: props.placeholder,
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image'],
                ['clean'],
            ],
        },
    });

    if (props.modelValue) {
        quill.root.innerHTML = props.modelValue;
    }

    quill.on('text-change', () => {
        internalChange = true;
        const html = quill.root.innerHTML;
        emit('update:modelValue', html === '<p><br></p>' ? '' : html);
        internalChange = false;
    });
});

watch(() => props.modelValue, (newVal) => {
    if (quill && !internalChange && (newVal || '') !== quill.root.innerHTML) {
        quill.root.innerHTML = newVal || '';
    }
});

onBeforeUnmount(() => {
    quill = null;
});
</script>

<template>
    <div class="quill-wrapper">
        <div ref="container" class="bg-charcoal text-white rounded-2xl border-white/5 min-h-[300px]"></div>
    </div>
</template>

<style>
.ql-toolbar.ql-snow {
    border: 1px solid rgba(255, 255, 255, 0.05) !important;
    background: rgba(255, 255, 255, 0.02) !important;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
    padding: 1rem !important;
}
.ql-container.ql-snow {
    border: 1px solid rgba(255, 255, 255, 0.05) !important;
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
    font-family: inherit;
    font-size: 1rem;
}
.ql-editor {
    min-height: 220px;
    color: #fff;
}
.ql-editor.ql-blank::before {
    color: rgba(255, 255, 255, 0.3) !important;
    font-style: normal;
}
.ql-snow .ql-stroke {
    stroke: rgba(255, 255, 255, 0.5) !important;
}
.ql-snow .ql-fill {
    fill: rgba(255, 255, 255, 0.5) !important;
}
.ql-snow .ql-picker,
.ql-snow .ql-picker-options {
    color: rgba(255, 255, 255, 0.5) !important;
}
.ql-snow .ql-picker-options {
    background: #1a1a1a !important;
}
.ql-snow.ql-toolbar button:hover .ql-stroke,
.ql-snow.ql-toolbar button.ql-active .ql-stroke {
    stroke: #F05423 !important;
}
.ql-snow.ql-toolbar button:hover .ql-fill,
.ql-snow.ql-toolbar button.ql-active .ql-fill {
    fill: #F05423 !important;
}
</style>
