<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    activities: Object, // paginator of mapped activity rows
});

const eventStyle = (event) => ({
    created: 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
    updated: 'bg-blue-500/15 text-blue-400 border-blue-500/30',
    deleted: 'bg-red-500/15 text-red-400 border-red-500/30',
}[event] || 'bg-white/5 text-white/50 border-white/10');

const eventIcon = (event) => ({
    created: 'fa-plus',
    updated: 'fa-pen',
    deleted: 'fa-trash-alt',
}[event] || 'fa-bolt');

// Build [{key, from, to}] rows from a change set.
const changeRows = (changes) => {
    if (!changes || !changes.attributes) return [];
    const attrs = changes.attributes;
    const old = changes.old || {};
    return Object.keys(attrs).map((key) => ({
        key,
        from: old[key],
        to: attrs[key],
    }));
};

const short = (val) => {
    if (val === null || val === undefined || val === '') return '—';
    const s = typeof val === 'object' ? JSON.stringify(val) : String(val);
    return s.length > 60 ? s.slice(0, 60) + '…' : s;
};
</script>

<template>
    <AdminLayout title="Audit Trail">
        <div class="bg-charcoal-light border border-white/5 rounded-[2.5rem] overflow-hidden">
            <div class="p-10 border-b border-white/5">
                <h3 class="text-xl font-bold">Activity Log</h3>
                <p class="text-white/40 text-sm mt-1">Every create, update, delete and bulk import — who did what, and when.</p>
            </div>

            <div class="divide-y divide-white/5">
                <div v-for="a in activities.data" :key="a.id" class="p-6 md:px-10 flex flex-col md:flex-row md:items-start gap-4 hover:bg-white/5 transition">
                    <!-- Event + subject -->
                    <div class="flex items-start gap-4 md:w-80 shrink-0">
                        <span :class="eventStyle(a.event)" class="w-10 h-10 rounded-xl border flex items-center justify-center shrink-0">
                            <i class="fas" :class="eventIcon(a.event)"></i>
                        </span>
                        <div class="min-w-0">
                            <div class="font-bold text-sm capitalize">{{ a.description || a.event }}</div>
                            <div class="text-white/40 text-xs mt-0.5">
                                <span v-if="a.subject_type" class="font-semibold text-white/60">{{ a.subject_type }}<span v-if="a.subject_id"> #{{ a.subject_id }}</span></span>
                                <span v-else class="uppercase tracking-widest">{{ a.log_name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Changes -->
                    <div class="flex-1 min-w-0">
                        <div v-if="changeRows(a.changes).length" class="flex flex-wrap gap-2">
                            <span v-for="row in changeRows(a.changes)" :key="row.key"
                                class="inline-flex items-center gap-1.5 text-xs bg-charcoal border border-white/10 rounded-lg px-2.5 py-1.5">
                                <span class="font-bold text-white/70">{{ row.key }}</span>
                                <span class="text-white/30 line-through">{{ short(row.from) }}</span>
                                <i class="fas fa-arrow-right text-white/20 text-[9px]"></i>
                                <span class="text-emerald-400">{{ short(row.to) }}</span>
                            </span>
                        </div>
                        <div v-else class="text-white/30 text-xs italic">No field changes recorded.</div>
                    </div>

                    <!-- Causer + time -->
                    <div class="md:text-right md:w-48 shrink-0">
                        <div class="text-sm font-bold flex items-center md:justify-end gap-2">
                            <i class="fas fa-user-circle text-white/30"></i> {{ a.causer }}
                        </div>
                        <div class="text-white/40 text-xs mt-0.5" :title="a.created_at_full">{{ a.created_at }}</div>
                    </div>
                </div>

                <div v-if="activities.data.length === 0" class="p-16 text-center text-white/40">
                    <i class="fas fa-clipboard-list text-4xl text-white/10 mb-4"></i>
                    <p>No activity recorded yet.</p>
                </div>
            </div>

            <div v-if="activities.last_page > 1" class="p-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-sm text-white/40">Showing {{ activities.from }}–{{ activities.to }} of {{ activities.total }}</span>
                <div class="flex items-center gap-1 flex-wrap justify-center">
                    <template v-for="(link, i) in activities.links" :key="i">
                        <Link v-if="link.url" :href="link.url" preserve-scroll
                            :class="link.active ? 'bg-red text-white' : 'bg-white/5 text-white/60 hover:bg-white/10'"
                            class="min-w-9 h-9 px-3 rounded-lg text-sm font-bold flex items-center justify-center transition" v-html="link.label" />
                        <span v-else class="min-w-9 h-9 px-3 rounded-lg text-sm text-white/20 flex items-center justify-center" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
