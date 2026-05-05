import { defineStore } from 'pinia';
import { ref, computed, watch } from 'vue';

export const useCartStore = defineStore('cart', () => {
    const items = ref([]);
    const isOpen = ref(false);

    // Persistence
    const savedCart = localStorage.getItem('laptech_cart');
    if (savedCart) {
        items.value = JSON.parse(savedCart);
    }

    watch(items, (newItems) => {
        localStorage.setItem('laptech_cart', JSON.stringify(newItems));
    }, { deep: true });

    // Actions
    const addItem = (product, quantity = 1) => {
        const existing = items.value.find(i => i.id === product.id);
        if (existing) {
            existing.quantity += quantity;
        } else {
            items.value.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                slug: product.slug,
                quantity
            });
        }
        window.toastr.success(`${product.name} added to cart`);
    };

    const removeItem = (id) => {
        items.value = items.value.filter(i => i.id !== id);
    };

    const updateQuantity = (id, quantity) => {
        const item = items.value.find(i => i.id === id);
        if (item) {
            item.quantity = quantity;
            if (item.quantity <= 0) removeItem(id);
        }
    };

    const clearCart = () => {
        items.value = [];
    };

    const toggleCart = () => {
        isOpen.value = !isOpen.value;
    };

    // Getters
    const totalItems = computed(() => items.value.reduce((acc, i) => acc + i.quantity, 0));
    const totalPrice = computed(() => items.value.reduce((acc, i) => acc + (i.price * i.quantity), 0));

    return {
        items,
        isOpen,
        addItem,
        removeItem,
        updateQuantity,
        clearCart,
        toggleCart,
        totalItems,
        totalPrice
    };
});
