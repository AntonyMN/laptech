<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { useCartStore } from '../../Stores/cart';
import Navbar from '../../Components/Navbar.vue';


const cart = useCartStore();

const form = useForm({
    items: [],
    total: 0,
    shipping_address: {
        name: '',
        email: '',
        phone: '',
        city: '',
        address: '',
    },
});

onMounted(() => {
    if (cart.items.length === 0) {
        // Redirect back if cart is empty
        // In a real app we might want to handle this better
    }
    form.items = cart.items;
    form.total = cart.totalPrice;
});

const submitOrder = () => {
    form.post(route('orders.store'), {
        onSuccess: () => {
            cart.clearCart();
            window.swal.fire({
                title: 'Order Confirmed!',
                text: 'Your high-performance tech is on the way.',
                icon: 'success',
                confirmButtonColor: '#008080',
            });
        },
    });
};
</script>

<template>
    <Head title="Secure Checkout - Laptech" />

    <div class="min-h-screen bg-charcoal text-white font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="max-w-7xl mx-auto py-16 px-6">
            <div class="grid lg:grid-cols-3 gap-16 items-start">
                
                <!-- Shipping Info -->
                <div class="lg:col-span-2 space-y-12">
                    <section>
                        <h2 class="text-3xl font-heading font-black mb-8 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-xl bg-red/20 text-red flex items-center justify-center text-sm">1</span>
                            Shipping Information
                        </h2>

                        <div class="grid md:grid-cols-2 gap-8 bg-white/5 border border-white/10 rounded-[2rem] p-10">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30">Recipient Name</label>
                                <input v-model="form.shipping_address.name" type="text" placeholder="John Doe" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                                <InputError :message="form.errors['shipping_address.name']" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30">Email Address</label>
                                <input v-model="form.shipping_address.email" type="email" placeholder="john@example.com" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                                <InputError :message="form.errors['shipping_address.email']" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30">Phone Number</label>
                                <input v-model="form.shipping_address.phone" type="tel" placeholder="+254..." class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                                <InputError :message="form.errors['shipping_address.phone']" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30">City</label>
                                <input v-model="form.shipping_address.city" type="text" placeholder="Nairobi" class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required />
                                <InputError :message="form.errors['shipping_address.city']" />
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-white/30">Delivery Address</label>
                                <textarea v-model="form.shipping_address.address" rows="3" placeholder="Building, Street, Apartment..." class="w-full bg-charcoal border-white/5 rounded-2xl p-4 focus:border-red focus:ring-red transition" required></textarea>
                                <InputError :message="form.errors['shipping_address.address']" />
                            </div>

                        </div>
                    </section>

                    <section>
                        <h2 class="text-3xl font-heading font-black mb-8 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-xl bg-red/20 text-red flex items-center justify-center text-sm">2</span>
                            Payment Method
                        </h2>
                        <div class="bg-red/10 border border-red/20 rounded-[2rem] p-10 flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-wallet text-charcoal text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xl">Payment on Delivery</h4>
                                    <p class="text-white/40">Pay securely when your items arrive.</p>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full border-4 border-red flex items-center justify-center">
                                <div class="w-3 h-3 bg-red rounded-full"></div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Order Summary -->
                <div class="lg:sticky lg:top-24 space-y-8">
                    <div class="bg-charcoal-light border border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
                        <div class="p-10 border-b border-white/5">
                            <h3 class="text-2xl font-heading font-black">Order Summary</h3>
                        </div>
                        
                        <div class="p-10 space-y-6">
                            <div v-for="item in cart.items" :key="item.id" class="flex justify-between gap-4">
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm leading-tight">{{ item.name }}</h4>
                                    <p class="text-xs text-white/40">Qty: {{ item.quantity }}</p>
                                </div>
                                <div class="font-bold text-sm whitespace-nowrap">Ksh {{ (item.price * item.quantity).toLocaleString() }}</div>
                            </div>

                            <div class="pt-6 border-t border-white/5 space-y-4">
                                <div class="flex justify-between text-white/40 text-sm">
                                    <span>Subtotal</span>
                                    <span>Ksh {{ cart.totalPrice.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between text-white/40 text-sm">
                                    <span>Shipping</span>
                                    <span class="text-red">Calculated at arrival</span>
                                </div>
                                <div class="flex justify-between text-2xl font-black pt-4">
                                    <span>Total</span>
                                    <span class="text-red">Ksh {{ cart.totalPrice.toLocaleString() }}</span>
                                </div>
                            </div>

                            <button 
                                @click="submitOrder"
                                :disabled="form.processing"
                                class="w-full bg-red hover:bg-red-light disabled:opacity-50 text-white font-black py-5 rounded-2xl transition shadow-xl shadow-red/20 text-lg flex items-center justify-center gap-3 mt-8"
                            >
                                {{ form.processing ? 'Processing...' : 'Place Order' }}
                                <i class="fas fa-shield-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-8 text-center text-xs text-white/20 leading-relaxed">
                        By placing your order, you agree to Laptech's <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>.
                    </div>
                </div>

            </div>
        </main>

        <Footer />
    </div>

</template>

<style scoped>
.font-heading {
    font-family: 'Montserrat', sans-serif;
}
</style>
