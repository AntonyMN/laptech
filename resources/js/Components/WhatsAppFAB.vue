<script setup>
import { ref, onMounted } from 'vue';

const showBubble = ref(false);

const openWhatsApp = () => {
    window.open('https://wa.me/254722964566', '_blank', 'noopener,noreferrer');
};

const dismissBubble = (e) => {
    e.stopPropagation(); // Avoid triggering openWhatsApp
    showBubble.value = false;
    localStorage.setItem('laptech_whatsapp_dismissed', 'true');
};

onMounted(() => {
    // Check if dismissed previously in localStorage
    const dismissed = localStorage.getItem('laptech_whatsapp_dismissed');
    if (!dismissed) {
        setTimeout(() => {
            showBubble.value = true;
        }, 4000); // Expose bubble after 4 seconds for maximum engagement
    }
});
</script>

<template>
    <div class="fixed bottom-6 right-6 z-[999] flex flex-col items-end gap-3 font-sans pointer-events-none">
        
        <!-- Interactive Tawk.to-style Chat Bubble Popup -->
        <transition
            enter-active-class="transition duration-500 ease-out transform"
            enter-from-class="opacity-0 translate-y-8 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-300 ease-in transform"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-4 scale-95"
        >
            <div 
                v-if="showBubble" 
                class="pointer-events-auto relative w-72 md:w-80 bg-surface backdrop-blur-md border border-line p-5 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.12)] text-ink mr-2 cursor-pointer hover:border-emerald-400/40 transition duration-300 group"
                @click="openWhatsApp"
            >
                <!-- Close Button -->
                <button
                    @click="dismissBubble"
                    class="absolute top-3 right-3 text-muted hover:text-ink hover:bg-surface-muted rounded-full w-6 h-6 flex items-center justify-center transition"
                    aria-label="Close message"
                >
                    <i class="fas fa-times text-xs"></i>
                </button>

                <!-- Header / Support Agent -->
                <div class="flex items-center gap-3 mb-3">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-tr from-emerald-500 to-green-400 rounded-full flex items-center justify-center shadow-md">
                            <i class="fab fa-whatsapp text-white text-xl"></i>
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-surface rounded-full animate-ping"></span>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-surface rounded-full"></span>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-sm text-ink flex items-center gap-1.5">
                            Laptech Support
                            <span class="inline-block px-1.5 py-0.5 rounded text-[9px] bg-emerald-100 text-emerald-700 font-bold uppercase tracking-wider">Online</span>
                        </h4>
                        <p class="text-xs text-muted">Typically replies instantly</p>
                    </div>
                </div>

                <!-- Chat message -->
                <div class="bg-surface-muted border border-line rounded-xl p-3 text-sm text-ink leading-relaxed mb-3">
                    <p class="mb-1 text-emerald-600 font-medium font-heading">👋 Hello there!</p>
                    <p>Looking for a laptop, accessory or a repair? Let's chat directly on WhatsApp!</p>
                </div>

                <!-- Call to action -->
                <div class="flex items-center justify-between text-xs font-bold text-emerald-400 group-hover:text-emerald-300 transition">
                    <span>Start Live Chat</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition duration-300"></i>
                </div>
            </div>
        </transition>

        <!-- Floating Action Button -->
        <div class="pointer-events-auto relative group">
            <!-- Sleek Ambient Ring Pulsing Glow -->
            <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-green-400 rounded-full opacity-70 blur-md group-hover:opacity-100 group-hover:blur-lg transition duration-500 animate-pulse"></div>
            
            <!-- Main WhatsApp FAB Button -->
            <button 
                @click="openWhatsApp"
                class="relative w-14 h-14 md:w-16 md:h-16 bg-gradient-to-tr from-emerald-600 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white rounded-full flex items-center justify-center shadow-[0_8px_30px_rgba(16,185,129,0.3)] hover:shadow-[0_8px_30px_rgba(16,185,129,0.5)] transform hover:scale-110 active:scale-95 transition duration-300 z-10"
                aria-label="Contact Laptech via WhatsApp"
            >
                <!-- SVG Icon (super-crisp vectors) -->
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 448 512" 
                    class="w-7 h-7 md:w-8 md:h-8 fill-current drop-shadow-md transition duration-300 group-hover:rotate-6"
                >
                    <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                </svg>
            </button>

            <!-- Hover Tooltip (visible on larger displays) -->
            <div class="absolute right-full top-1/2 -translate-y-1/2 mr-4 px-4 py-2 bg-charcoal text-white text-xs font-bold rounded-xl shadow-2xl opacity-0 group-hover:opacity-100 transition duration-300 whitespace-nowrap pointer-events-none translate-x-2 group-hover:translate-x-0">
                WhatsApp Us
            </div>
        </div>
    </div>
</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
