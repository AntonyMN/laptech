import { ref } from 'vue';

const THEME_KEY = 'laptech-theme';

// Module-level state so every component shares one source of truth.
const isDark = ref(false);

function persist(dark) {
    try {
        localStorage.setItem(THEME_KEY, dark ? 'dark' : 'light');
    } catch (e) {
        // localStorage may be unavailable (private mode / SSR) — ignore.
    }
}

function apply(dark) {
    isDark.value = dark;
    if (typeof document !== 'undefined') {
        document.documentElement.classList.toggle('dark', dark);
    }
    persist(dark);
}

export function useTheme() {
    // Sync reactive state with whatever the pre-paint blade script already set.
    function initTheme() {
        if (typeof document === 'undefined') return;
        isDark.value = document.documentElement.classList.contains('dark');
    }

    function toggleTheme() {
        apply(!isDark.value);
    }

    function setTheme(dark) {
        apply(dark);
    }

    return { isDark, initTheme, toggleTheme, setTheme };
}
