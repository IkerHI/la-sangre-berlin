<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="admin-title">{{ t('admin.concerts') }}</h1>
            <div class="flex gap-2">
                <button @click="syncConcerts" class="btn-secondary" :disabled="syncing">
                    {{ syncing ? t('admin.syncing') : t('admin.sync') }}
                </button>
                <button @click="openCreate" class="btn-primary">+ {{ t('admin.add') }}</button>
            </div>
        </div>

        <p v-if="syncMsg" class="mb-4 text-sm" style="color: #4A7C59;">{{ syncMsg }}</p>

        <div v-if="loading" class="text-muted">{{ t('common.loading') }}</div>
        <div v-else>
            <div class="table-scroll">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Fecha</th><th>Venue</th><th>Ciudad</th><th>Estado</th><th>Vistas</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="concert in concerts" :key="concert.id">
                        <td class="mono">{{ formatDate(concert.date) }}</td>
                        <td>{{ concert.venue_name }}</td>
                        <td style="color: #B5AFA0;">{{ concert.city }}, {{ concert.country }}</td>
                        <td><span class="status-badge" :class="concert.status">{{ concert.status }}</span></td>
                        <td class="mono">{{ concert.views }}</td>
                        <td class="text-right">
                            <button @click="openEdit(concert)" class="action-btn">✏️</button>
                            <button @click="deleteConcert(concert)" class="action-btn ml-1" style="color: #C0392B;">🗑</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
            <div class="modal">
                <h2 class="modal-title">{{ editing ? t('admin.edit') : t('admin.add') }}</h2>
                <form @submit.prevent="saveForm" class="space-y-3 mt-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Fecha *</label>
                            <input v-model="form.date" type="datetime-local" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Ticket URL</label>
                            <input v-model="form.ticket_url" type="url" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Venue *</label>
                        <input v-model="form.venue_name" type="text" required class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Ciudad *</label>
                            <input v-model="form.city" type="text" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">País *</label>
                            <input v-model="form.country" type="text" required class="form-input">
                        </div>
                    </div>
                    <p v-if="formError" class="text-sm" style="color:#C0392B;">{{ formError }}</p>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="btn-primary">{{ t('admin.save') }}</button>
                        <button type="button" @click="showModal = false" class="btn-ghost">{{ t('admin.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();
const concerts = ref([]);
const loading = ref(true);
const showModal = ref(false);
const editing = ref(null);
const syncing = ref(false);
const syncMsg = ref('');
const formError = ref('');
const form = ref({ date: '', venue_name: '', city: '', country: '', ticket_url: '' });

function formatDate(d) {
    return new Date(d).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
}

async function load() {
    const { data } = await axios.get('/api/admin/concerts');
    concerts.value = data;
}

onMounted(async () => {
    try { await load(); } finally { loading.value = false; }
});

function openCreate() {
    editing.value = null;
    form.value = { date: '', venue_name: '', city: '', country: '', ticket_url: '' };
    showModal.value = true;
}

function openEdit(c) {
    editing.value = c;
    form.value = {
        date: new Date(c.date).toISOString().slice(0, 16),
        venue_name: c.venue_name,
        city: c.city,
        country: c.country,
        ticket_url: c.ticket_url || '',
    };
    showModal.value = true;
}

async function saveForm() {
    formError.value = '';
    try {
        if (editing.value) {
            await axios.put(`/api/admin/concerts/${editing.value.id}`, form.value);
        } else {
            await axios.post('/api/admin/concerts', form.value);
        }
        showModal.value = false;
        await load();
    } catch (err) {
        const msgs = err.response?.data?.errors;
        formError.value = msgs ? Object.values(msgs).flat().join(' · ') : 'Error al guardar';
    }
}

async function deleteConcert(c) {
    if (!confirm(t('admin.confirm_delete'))) return;
    await axios.delete(`/api/admin/concerts/${c.id}`);
    await load();
}

async function syncConcerts() {
    syncing.value = true;
    syncMsg.value = '';
    try {
        const { data } = await axios.post('/api/admin/concerts/sync');
        syncMsg.value = `✓ ${data.synced} sincronizados · ${data.with_ticket_offer} con ticket directo`;
        await load();
    } catch {
        syncMsg.value = 'Error al sincronizar';
    } finally {
        syncing.value = false;
    }
}
</script>

<style scoped>
.admin-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.75rem; letter-spacing:0.1em; text-transform:uppercase; border-left:4px solid #A51C30; padding-left:1rem; }
.admin-table { border-collapse:collapse; }
.admin-table th { font-family:'Oswald',sans-serif; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:#B5AFA0; text-align:left; padding:0.5rem 0.75rem; border-bottom:1px solid #2A2A2A; }
.admin-table td { padding:0.625rem 0.75rem; border-bottom:1px solid #1A1A1A; font-size:0.875rem; color:#EDE8D8; }
.mono { font-family:'JetBrains Mono',monospace; font-size:0.8rem; }
.status-badge { font-family:'Oswald',sans-serif; font-size:0.7rem; text-transform:uppercase; padding:0.15rem 0.5rem; }
.status-badge.upcoming { background:rgba(74,124,89,0.2); color:#4A7C59; }
.status-badge.past { background:rgba(42,42,42,0.5); color:#B5AFA0; }
.action-btn { background:none; border:none; cursor:pointer; font-size:1rem; padding:0.25rem; }
.btn-primary { background:#A51C30; color:#EDE8D8; font-family:'Oswald',sans-serif; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; padding:0.5rem 1rem; border:none; cursor:pointer; font-size:0.8rem; }
.btn-primary:hover { background:#7A0E0E; }
.btn-secondary { background:transparent; border:1px solid #A51C30; color:#A51C30; font-family:'Oswald',sans-serif; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; padding:0.5rem 1rem; cursor:pointer; font-size:0.8rem; transition:all 0.2s; }
.btn-secondary:hover { background:#A51C30; color:#EDE8D8; }
.btn-secondary:disabled { opacity:0.5; cursor:not-allowed; }
.btn-ghost { background:transparent; border:1px solid #2A2A2A; color:#B5AFA0; font-family:'Oswald',sans-serif; font-size:0.8rem; padding:0.5rem 1rem; cursor:pointer; }
.form-label { display:block; font-family:'Oswald',sans-serif; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:#B5AFA0; margin-bottom:0.25rem; }
.form-input { width:100%; background:#0D0D0D; border:1px solid #2A2A2A; color:#EDE8D8; padding:0.5rem 0.625rem; font-size:0.875rem; outline:none; }
.form-input:focus { border-color:#A51C30; }
.table-scroll { overflow-x:auto; }
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.8); display:flex; align-items:center; justify-content:center; z-index:50; padding:1rem; }
.modal { background:#1A1A1A; padding:1.5rem; width:100%; max-width:500px; border:1px solid #2A2A2A; max-height:90vh; overflow-y:auto; }
.modal-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.1rem; text-transform:uppercase; letter-spacing:0.1em; }
.text-muted { color:#B5AFA0; }
@media (max-width:600px) {
    .modal { padding:1rem; }
    .admin-table th, .admin-table td { padding:0.4rem 0.5rem; font-size:0.8rem; }
}
</style>
