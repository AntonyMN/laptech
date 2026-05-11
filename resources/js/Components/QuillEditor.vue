<script setup>
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
    modelValue: String,
});

const emit = defineEmits(['update:modelValue']);

const container = ref(null);
let quill = null;

onMounted(() => {
    // Load Quill from CDN if not already loaded
    if (!window.Quill) {
        const link = document.createElement('link');
        link.href = 'https://cdn.quilljs.com/1.3.6/quill.snow.css';
        link.rel = 'stylesheet';
        document.head.appendChild(link);

        const script = document.createElement('script');
        script.src = 'https://cdn.quilljs.com/1.3.6/quill.js';
        script.onload = initQuill;
        document.head.appendChild(script);
    } else {
        initQuill();
    }
});

function initQuill() {
    quill = new window.Quill(container.value, {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    if (props.modelValue) {
        quill.root.innerHTML = props.modelValue;
    }

    quill.on('text-change', () => {
        emit('update:modelValue', quill.root.innerHTML);
    });
}

watch(() => props.modelValue, (newVal) => {
    if (quill && newVal !== quill.root.innerHTML) {
        quill.root.innerHTML = newVal || '';
    }
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
    min-height: 300px;
}
.ql-snow .ql-stroke {
    stroke: rgba(255, 255, 255, 0.5) !important;
}
.ql-snow .ql-fill {
    fill: rgba(255, 255, 255, 0.5) !important;
}
.ql-snow .ql-picker {
    color: rgba(255, 255, 255, 0.5) !important;
}
.ql-snow.ql-toolbar button:hover .ql-stroke,
.ql-snow.ql-toolbar button.ql-active .ql-stroke {
    stroke: #e31b23 !important;
}
</style>
