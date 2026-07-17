<script setup>
import { onMounted } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { useCartStore } from '../../Stores/cart';
import Navbar from '../../Components/Navbar.vue';
import Footer from '../../Components/Footer.vue';


const page = usePage();
const cart = useCartStore();

const form = useForm({
    items: [],
    total: 0,
    shipping_address: {
        name: page.props.auth.user?.name || '',
        email: page.props.auth.user?.email || '',
        phone: '',
        city: '',
        address: '',
    },
    password: '',
    create_account: false,
});

onMounted(() => {
    cart.isOpen = false;
    if (cart.items.length === 0) {
        window.location.href = route('products.index');
        return;
    }
    form.items = cart.items;
    form.total = cart.totalPrice;
});

const submitOrder = () => {
    form.post(route('orders.store'), {
        onSuccess: () => {
            cart.clearCart();
            window.Swal.fire({
                title: 'Order Confirmed!',
                text: 'Your order is on the way.',
                icon: 'success',
                confirmButtonColor: '#F05423',
            });
        },
    });
};
</script>

<template>
    <Head title="Secure Checkout — Laptech Electronics" />

    <div class="min-h-screen bg-page text-ink font-sans selection:bg-red selection:text-white">
        <Navbar :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />

        <main class="max-w-7xl mx-auto py-12 px-6">
            <h1 class="text-3xl font-heading font-extrabold mb-8">Checkout</h1>
            <div class="grid lg:grid-cols-3 gap-10 items-start">

                <!-- Shipping Info -->
                <div class="lg:col-span-2 space-y-10">
                    <section>
                        <h2 class="text-xl font-heading font-bold mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-red/10 text-red flex items-center justify-center text-sm font-bold">1</span>
                            Shipping Information
                        </h2>

                        <div class="grid md:grid-cols-2 gap-5 bg-surface border border-line rounded-2xl p-6 md:p-8">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Recipient Name</label>
                                <input v-model="form.shipping_address.name" type="text" placeholder="John Doe" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required />
                                <InputError :message="form.errors['shipping_address.name']" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Email Address</label>
                                <input v-model="form.shipping_address.email" type="email" placeholder="john@example.com" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required />
                                <InputError :message="form.errors['shipping_address.email']" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Phone Number</label>
                                <input v-model="form.shipping_address.phone" type="tel" placeholder="+254…" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required />
                                <InputError :message="form.errors['shipping_address.phone']" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">City</label>
                                <input v-model="form.shipping_address.city" type="text" placeholder="Nairobi" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required />
                                <InputError :message="form.errors['shipping_address.city']" />
                            </div>
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-xs font-bold uppercase tracking-widest text-muted">Delivery Address</label>
                                <textarea v-model="form.shipping_address.address" rows="3" placeholder="Building, Street, Apartment…" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" required></textarea>
                                <InputError :message="form.errors['shipping_address.address']" />
                            </div>

                            <!-- Guest Account Creation -->
                            <div v-if="!$page.props.auth.user" class="md:col-span-2 pt-5 border-t border-line space-y-5">
                                <div class="flex items-center gap-3 cursor-pointer group" @click="form.create_account = !form.create_account">
                                    <div :class="form.create_account ? 'bg-red border-red text-white' : 'border-line'" class="w-6 h-6 border-2 rounded-md flex items-center justify-center transition">
                                        <i v-if="form.create_account" class="fas fa-check text-[10px]"></i>
                                    </div>
                                    <span class="text-sm font-semibold text-muted group-hover:text-ink transition">Save my details with a free account</span>
                                </div>

                                <div v-if="form.create_account" class="animate-in fade-in slide-in-from-top-4 duration-300">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-bold uppercase tracking-widest text-muted">Create Password</label>
                                        <input v-model="form.password" type="password" placeholder="••••••••" class="w-full bg-surface-muted border border-line rounded-xl p-3.5 text-ink focus:border-red focus:ring-0 transition" />
                                        <InputError :message="form.errors.password" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-xl font-heading font-bold mb-5 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-red/10 text-red flex items-center justify-center text-sm font-bold">2</span>
                            Payment Method
                        </h2>
                        <div class="bg-red/5 border border-red/20 rounded-2xl p-6 md:p-8 flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-surface border border-line rounded-xl flex items-center justify-center">
                                    <i class="fas fa-wallet text-red text-2xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg">Payment on Delivery</h4>
                                    <p class="text-muted text-sm">Pay securely when your items arrive.</p>
                                </div>
                            </div>
                            <div class="w-7 h-7 rounded-full border-4 border-red flex items-center justify-center">
                                <div class="w-2.5 h-2.5 bg-red rounded-full"></div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Order Summary -->
                <div class="lg:sticky lg:top-40 space-y-6">
                    <div class="bg-surface border border-line rounded-2xl overflow-hidden shadow-lg">
                        <div class="p-6 border-b border-line">
                            <h3 class="text-lg font-heading font-bold">Order Summary</h3>
                        </div>

                        <div class="p-6 space-y-5">
                            <div v-for="item in cart.items" :key="item.id" class="flex gap-3">
                                <div class="w-14 h-14 bg-surface-muted rounded-lg overflow-hidden shrink-0 border border-line">
                                    <img :src="item.image" class="w-full h-full object-cover" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-sm leading-tight line-clamp-2">{{ item.name }}</h4>
                                    <p class="text-xs text-muted">Qty: {{ item.quantity }}</p>
                                </div>
                                <div class="font-bold text-sm whitespace-nowrap text-ink">Ksh {{ (item.price * item.quantity).toLocaleString() }}</div>
                            </div>

                            <div class="pt-5 border-t border-line space-y-3">
                                <div class="flex justify-between text-muted text-sm">
                                    <span>Subtotal</span>
                                    <span>Ksh {{ cart.totalPrice.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between text-muted text-sm">
                                    <span>Shipping</span>
                                    <span class="text-red">Calculated at arrival</span>
                                </div>
                                <div class="flex justify-between text-xl font-extrabold pt-3">
                                    <span>Total</span>
                                    <span class="text-red">Ksh {{ cart.totalPrice.toLocaleString() }}</span>
                                </div>
                            </div>

                            <button
                                @click="submitOrder"
                                :disabled="form.processing"
                                class="w-full bg-red hover:bg-red-dark disabled:opacity-50 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-red/20 flex items-center justify-center gap-3 mt-4"
                            >
                                {{ form.processing ? 'Processing…' : 'Place Order' }}
                                <i class="fas fa-shield-alt"></i>
                            </button>
                        </div>
                    </div>

                    <div class="px-4 text-center text-xs text-muted leading-relaxed">
                        By placing your order, you agree to Laptech's <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>.
                    </div>
                </div>

            </div>
        </main>

        <Footer />
    </div>
</template>
