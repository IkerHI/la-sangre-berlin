<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="admin-title">Usuarios</h1>
            <button @click="openAdd" class="btn-primary">+ Añadir usuario</button>
        </div>

        <div v-if="loading" class="text-muted text-sm">Cargando…</div>
        <div v-else class="user-list">
            <div v-for="u in users" :key="u.id" class="user-row">
                <div class="user-avatar">{{ initial(u) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ u.name }}</div>
                    <div class="user-email">{{ u.email }}</div>
                </div>
                <div class="user-date">{{ formatDate(u.created_at) }}</div>
                <div class="user-actions">
                    <button @click="openPassword(u)" class="btn-sm">Contraseña</button>
                    <button
                        @click="deleteUser(u)"
                        class="btn-sm btn-sm--danger"
                        :disabled="u.id === currentUserId"
                        :title="u.id === currentUserId ? 'No puedes eliminar tu propia cuenta' : ''"
                    >Eliminar</button>
                </div>
            </div>
        </div>

        <!-- Add user modal -->
        <div v-if="showAdd" class="modal-overlay" @click.self="showAdd = false">
            <div class="modal">
                <h2 class="modal-title">Nuevo usuario admin</h2>
                <form @submit.prevent="submitAdd" class="space-y-3 mt-4">
                    <div>
                        <label class="form-label">Nombre *</label>
                        <input v-model="addForm.name" type="text" required class="form-input" autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label">Email *</label>
                        <input v-model="addForm.email" type="email" required class="form-input" autocomplete="off">
                    </div>
                    <div>
                        <label class="form-label">Contraseña * <span class="hint">(mín. 8 caracteres)</span></label>
                        <input v-model="addForm.password" type="password" required minlength="8" class="form-input" autocomplete="new-password">
                    </div>
                    <p v-if="addError" class="error-msg">{{ addError }}</p>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" :disabled="saving" class="btn-primary">{{ saving ? 'Guardando…' : 'Crear usuario' }}</button>
                        <button type="button" @click="showAdd = false" class="btn-ghost">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change password modal -->
        <div v-if="pwTarget" class="modal-overlay" @click.self="pwTarget = null">
            <div class="modal">
                <h2 class="modal-title">Cambiar contraseña</h2>
                <p class="user-tag">{{ pwTarget.name }} · {{ pwTarget.email }}</p>
                <form @submit.prevent="submitPassword" class="space-y-3 mt-4">
                    <div>
                        <label class="form-label">Nueva contraseña * <span class="hint">(mín. 8 caracteres)</span></label>
                        <input v-model="pwForm.password" type="password" required minlength="8" class="form-input" autocomplete="new-password">
                    </div>
                    <div>
                        <label class="form-label">Confirmar contraseña *</label>
                        <input v-model="pwForm.confirm" type="password" required class="form-input" autocomplete="new-password">
                    </div>
                    <p v-if="pwError" class="error-msg">{{ pwError }}</p>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" :disabled="saving" class="btn-primary">{{ saving ? 'Guardando…' : 'Guardar' }}</button>
                        <button type="button" @click="pwTarget = null" class="btn-ghost">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';

const auth = useAuthStore();
const currentUserId = auth.user?.id;

const users  = ref([]);
const loading = ref(true);
const saving  = ref(false);

const showAdd = ref(false);
const addForm = ref({ name: '', email: '', password: '' });
const addError = ref('');

const pwTarget = ref(null);
const pwForm   = ref({ password: '', confirm: '' });
const pwError  = ref('');

function initial(u) {
    return (u.name || u.email || 'U').charAt(0).toUpperCase();
}

function formatDate(iso) {
    return new Date(iso).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
}

async function load() {
    const { data } = await axios.get('/api/admin/users');
    users.value = data;
}

onMounted(async () => {
    try { await load(); } finally { loading.value = false; }
});

function openAdd() {
    addForm.value  = { name: '', email: '', password: '' };
    addError.value = '';
    showAdd.value  = true;
}

async function submitAdd() {
    if (saving.value) return;
    addError.value = '';
    saving.value   = true;
    try {
        await axios.post('/api/admin/users', addForm.value);
        showAdd.value = false;
        await load();
    } catch (err) {
        const msgs = err.response?.data?.errors;
        addError.value = msgs ? Object.values(msgs).flat().join(' · ') : 'Error al crear usuario';
    } finally {
        saving.value = false;
    }
}

function openPassword(u) {
    pwTarget.value = u;
    pwForm.value   = { password: '', confirm: '' };
    pwError.value  = '';
}

async function submitPassword() {
    if (saving.value) return;
    pwError.value = '';
    if (pwForm.value.password !== pwForm.value.confirm) {
        pwError.value = 'Las contraseñas no coinciden';
        return;
    }
    saving.value = true;
    try {
        await axios.patch(`/api/admin/users/${pwTarget.value.id}/password`, { password: pwForm.value.password });
        pwTarget.value = null;
    } catch (err) {
        const msgs = err.response?.data?.errors;
        pwError.value = msgs ? Object.values(msgs).flat().join(' · ') : 'Error al cambiar contraseña';
    } finally {
        saving.value = false;
    }
}

async function deleteUser(u) {
    if (u.id === currentUserId) return;
    if (!confirm(`¿Eliminar el usuario "${u.name}"? Esta acción no se puede deshacer.`)) return;
    await axios.delete(`/api/admin/users/${u.id}`);
    await load();
}
</script>

<style scoped>
.admin-title {
    font-family: 'Oswald', sans-serif;
    font-weight: 700; font-size: 1.75rem;
    letter-spacing: 0.1em; text-transform: uppercase;
    border-left: 4px solid #A51C30; padding-left: 1rem;
}

.user-list { display: flex; flex-direction: column; gap: 2px; }

.user-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    background: #141414;
    border: 1px solid #1E1E1E;
}

.user-avatar {
    width: 34px; height: 34px; flex-shrink: 0;
    background: #A51C30; color: #EDE8D8;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 0.9rem;
}

.user-info { flex: 1; min-width: 0; }
.user-name {
    font-family: 'Oswald', sans-serif; font-size: 0.85rem;
    font-weight: 500; letter-spacing: 0.06em; color: #EDE8D8;
}
.user-email {
    font-family: 'Space Mono', monospace; font-size: 0.6rem;
    color: #888888; letter-spacing: 0.04em; margin-top: 1px;
}

.user-date {
    font-family: 'Space Mono', monospace; font-size: 0.58rem;
    color: #3A3A3A; flex-shrink: 0;
}

.user-actions { display: flex; gap: 0.4rem; flex-shrink: 0; }

.btn-sm {
    font-family: 'Oswald', sans-serif; font-size: 0.65rem;
    letter-spacing: 0.08em; text-transform: uppercase;
    padding: 0.3rem 0.6rem;
    background: transparent; border: 1px solid #2A2A2A; color: #B5AFA0;
    cursor: pointer; transition: border-color 0.15s, color 0.15s;
}
.btn-sm:hover:not(:disabled) { border-color: #555; color: #EDE8D8; }
.btn-sm--danger:hover:not(:disabled) { border-color: #A51C30; color: #A51C30; }
.btn-sm:disabled { opacity: 0.3; cursor: default; }

.user-tag {
    font-family: 'Space Mono', monospace; font-size: 0.65rem;
    color: #666; margin-top: 0.5rem;
}
.hint { font-size: 0.6rem; color: #555; font-family: 'Space Mono', monospace; }

/* Shared */
.btn-primary {
    background: #A51C30; color: #EDE8D8;
    font-family: 'Oswald', sans-serif; font-weight: 500;
    letter-spacing: 0.1em; text-transform: uppercase;
    padding: 0.5rem 1rem; border: none; cursor: pointer; font-size: 0.8rem;
}
.btn-primary:hover:not(:disabled) { background: #7A0E0E; }
.btn-primary:disabled { opacity: 0.5; cursor: default; }
.btn-ghost {
    background: transparent; border: 1px solid #2A2A2A; color: #B5AFA0;
    font-family: 'Oswald', sans-serif; font-size: 0.8rem;
    padding: 0.5rem 1rem; cursor: pointer;
}
.btn-ghost:hover { border-color: #555; color: #EDE8D8; }
.form-label {
    display: block;
    font-family: 'Oswald', sans-serif; font-size: 0.7rem;
    letter-spacing: 0.1em; text-transform: uppercase;
    color: #B5AFA0; margin-bottom: 0.25rem;
}
.form-input {
    width: 100%; background: #0D0D0D; border: 1px solid #2A2A2A;
    color: #EDE8D8; padding: 0.5rem 0.625rem; font-size: 0.875rem; outline: none;
}
.form-input:focus { border-color: #A51C30; }
.modal-overlay {
    position: fixed; inset: 0; background: rgba(0,0,0,0.85);
    display: flex; align-items: center; justify-content: center; z-index: 50;
}
.modal {
    background: #1A1A1A; padding: 1.5rem;
    width: 100%; max-width: 400px; border: 1px solid #2A2A2A;
}
.modal-title {
    font-family: 'Oswald', sans-serif; font-weight: 700;
    font-size: 1.1rem; text-transform: uppercase; letter-spacing: 0.1em;
}
.error-msg { color: #C0392B; font-size: 0.8rem; }
.text-muted { color: #B5AFA0; }
</style>
