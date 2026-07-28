<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    users: Object,   // paginator: { data:[{id,name,email,is_admin,role,created_at}], links, ... }
    roles: Array,    // ['admin','manager','staff']
    filters: Object,
});

const page = usePage();
const search = ref(props.filters?.search ?? '');

let searchTimer = null;
watch(search, (value) => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get(route('admin.users.index'), { search: value || undefined }, {
            preserveState: true, preserveScroll: true, replace: true,
        });
    }, 300);
});

// Edit modal
const editing = ref(null);
const form = useForm({ role: '' });

const openEdit = (user) => {
    editing.value = user;
    form.clearErrors();
    form.role = user.role || '';
};
const close = () => { editing.value = null; };

const save = () => {
    form.patch(route('admin.users.update', editing.value.id), {
        preserveScroll: true,
        onSuccess: () => close(),
    });
};

const isSelf = (u) => u.id === page.props.auth?.user?.id;

const roleBadge = (role) => ({
    admin: 'bg-red/15 text-red border-red/30',
    manager: 'bg-amber-500/15 text-amber-400 border-amber-500/30',
    staff: 'bg-blue-500/15 text-blue-400 border-blue-500/30',
}[role] || 'bg-white/5 text-white/40 border-white/10');

const roleLabel = (role) => role ? role.charAt(0).toUpperCase() + role.slice(1) : 'Customer';
</script>

<template>
    <AdminLayout title="Users & Roles">
        <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
            <div class="p-10 border-b border-white/5 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold">Team & Customers</h3>
                    <p class="text-white/40 text-sm mt-1">Assign roles to control what each person can access.</p>
                </div>
                <div class="relative flex-1 md:flex-none">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                    <input v-model="search" type="text" placeholder="Search name or email..."
                        class="bg-charcoal border border-white/10 rounded-xl pl-11 pr-4 py-3 text-sm w-full md:w-72 text-white placeholder-white/30 focus:outline-none focus:border-red transition" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/5 text-xs uppercase tracking-widest text-white/40 font-bold">
                            <th class="px-10 py-6">User</th>
                            <th class="px-10 py-6">Role</th>
                            <th class="px-10 py-6">Joined</th>
                            <th class="px-10 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr v-for="u in users.data" :key="u.id" class="hover:bg-white/5 transition">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-red/15 text-red flex items-center justify-center font-black shrink-0">
                                        {{ (u.name || '?').charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <div class="font-bold flex items-center gap-2">
                                            {{ u.name }}
                                            <span v-if="isSelf(u)" class="text-[10px] font-bold uppercase tracking-widest text-white/30">You</span>
                                        </div>
                                        <div class="text-white/40 text-sm">{{ u.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <span :class="roleBadge(u.role)" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter border">
                                    {{ roleLabel(u.role) }}
                                </span>
                            </td>
                            <td class="px-10 py-6 text-white/50">{{ u.created_at }}</td>
                            <td class="px-10 py-6 text-right">
                                <button @click="openEdit(u)" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-red hover:text-white transition text-sm font-bold">
                                    <i class="fas fa-user-shield mr-1.5 text-xs"></i> Manage
                                </button>
                            </td>
                        </tr>
                        <tr v-if="users.data.length === 0">
                            <td colspan="4" class="px-10 py-16 text-center text-white/40">No users found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="users.last_page > 1" class="p-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-sm text-white/40">Showing {{ users.from }}–{{ users.to }} of {{ users.total }}</span>
                <div class="flex items-center gap-1 flex-wrap justify-center">
                    <template v-for="(link, i) in users.links" :key="i">
                        <Link v-if="link.url" :href="link.url" preserve-scroll preserve-state
                            :class="link.active ? 'bg-red text-white' : 'bg-white/5 text-white/60 hover:bg-white/10'"
                            class="min-w-9 h-9 px-3 rounded-lg text-sm font-bold flex items-center justify-center transition" v-html="link.label" />
                        <span v-else class="min-w-9 h-9 px-3 rounded-lg text-sm text-white/20 flex items-center justify-center" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>

        <!-- Edit role modal -->
        <Teleport to="body">
            <div v-if="editing" class="fixed inset-0 z-[70] flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/75" @click="close"></div>
                <div class="relative w-full max-w-md bg-charcoal-light border border-white/10 rounded-2xl shadow-2xl text-white overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                        <div>
                            <h3 class="font-bold leading-tight">Manage Access</h3>
                            <p class="text-xs text-white/40">{{ editing.name }} · {{ editing.email }}</p>
                        </div>
                        <button @click="close" class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center text-white/50 hover:text-white transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-3">
                        <label class="text-xs font-bold uppercase tracking-widest text-white/40">Role</label>
                        <label v-for="opt in [{v:'',l:'Customer',d:'No admin access — regular shopper'},{v:'staff',l:'Staff',d:'Manage products only'},{v:'manager',l:'Manager',d:'Everything except user management'},{v:'admin',l:'Admin',d:'Full access, including users & audit'}]"
                            :key="opt.v"
                            class="flex items-start gap-3 p-4 rounded-xl border cursor-pointer transition"
                            :class="form.role === opt.v ? 'border-red bg-red/10' : 'border-white/10 hover:border-white/20'">
                            <input type="radio" :value="opt.v" v-model="form.role" class="mt-1 text-red focus:ring-red" />
                            <div>
                                <div class="font-bold text-sm">{{ opt.l }}</div>
                                <div class="text-white/40 text-xs">{{ opt.d }}</div>
                            </div>
                        </label>
                        <p v-if="isSelf(editing) && form.role !== 'admin'" class="text-xs text-amber-400 flex items-center gap-2">
                            <i class="fas fa-triangle-exclamation"></i> You can't remove your own admin access.
                        </p>
                    </div>

                    <div class="px-6 py-4 border-t border-white/10 flex items-center justify-end gap-3">
                        <button @click="close" class="px-5 py-2.5 rounded-lg text-sm font-bold text-white/60 hover:text-white hover:bg-white/5 transition">Cancel</button>
                        <button @click="save" :disabled="form.processing"
                            class="inline-flex items-center gap-2 bg-red hover:bg-red-light disabled:opacity-40 text-white px-6 py-2.5 rounded-lg text-sm font-bold transition">
                            <i v-if="form.processing" class="fas fa-circle-notch fa-spin"></i>
                            <i v-else class="fas fa-check"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </AdminLayout>
</template>
