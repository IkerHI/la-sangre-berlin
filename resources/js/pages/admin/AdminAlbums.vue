<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="admin-title">{{ t('admin.albums') }}</h1>
            <button @click="openCreate" class="btn-primary">+ {{ t('admin.add') }}</button>
        </div>

        <div v-if="loading" class="text-muted">{{ t('common.loading') }}</div>
        <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div v-for="album in albums" :key="album.id" class="album-item">
                <div class="album-thumb">
                    <img v-if="album.cover_url" :src="album.cover_url" :alt="album.title" class="w-full h-full object-cover">
                    <div v-else class="placeholder">🎵</div>
                </div>
                <div class="p-3">
                    <div class="album-name">{{ album.title }}</div>
                    <div class="album-year">{{ album.release_year }}</div>
                    <div class="flex gap-2 mt-2">
                        <button @click="openEdit(album)" class="btn-xs">✏️ {{ t('admin.edit') }}</button>
                        <button @click="deleteAlbum(album)" class="btn-xs danger">🗑 {{ t('admin.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
            <div class="modal">
                <h2 class="modal-title">{{ editing ? t('admin.edit') : t('admin.add') }}</h2>
                <form @submit.prevent="saveForm" class="space-y-3 mt-4" enctype="multipart/form-data">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Título *</label>
                            <input v-model="form.title" type="text" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Año *</label>
                            <input v-model="form.release_year" type="number" required class="form-input" min="1900" max="2100">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Spotify Embed URL</label>
                        <input v-model="form.spotify_embed_url" type="text" class="form-input" placeholder="https://open.spotify.com/embed/album/...">
                    </div>
                    <div>
                        <label class="form-label">Descripción (ES)</label>
                        <textarea v-model="form.description_es" rows="2" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Descripción (DE)</label>
                        <textarea v-model="form.description_de" rows="2" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Descripción (EN)</label>
                        <textarea v-model="form.description_en" rows="2" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Portada (imagen)</label>
                        <input type="file" accept="image/*" @change="onCoverChange" class="form-input">
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
const albums = ref([]);
const loading = ref(true);
const showModal = ref(false);
const editing = ref(null);
const coverFile = ref(null);
const formError = ref('');
const form = ref({ title: '', release_year: new Date().getFullYear(), spotify_embed_url: '', description_es: '', description_de: '', description_en: '' });

async function load() {
    const { data } = await axios.get('/api/admin/albums');
    albums.value = data;
}

onMounted(async () => {
    try { await load(); } finally { loading.value = false; }
});

function openCreate() {
    editing.value = null;
    coverFile.value = null;
    form.value = { title: '', release_year: new Date().getFullYear(), spotify_embed_url: '', description_es: '', description_de: '', description_en: '' };
    showModal.value = true;
}

function openEdit(a) {
    editing.value = a;
    coverFile.value = null;
    form.value = { title: a.title, release_year: a.release_year, spotify_embed_url: a.spotify_embed_url || '', description_es: a.description_es || '', description_de: a.description_de || '', description_en: a.description_en || '' };
    showModal.value = true;
}

function onCoverChange(e) { coverFile.value = e.target.files[0] || null; }

async function saveForm() {
    formError.value = '';
    const fd = new FormData();
    Object.entries(form.value).forEach(([k, v]) => fd.append(k, v ?? ''));
    if (coverFile.value) fd.append('cover_image', coverFile.value);

    try {
        if (editing.value) {
            fd.append('_method', 'PUT');
            await axios.post(`/api/admin/albums/${editing.value.id}`, fd, {});
        } else {
            await axios.post('/api/admin/albums', fd, {});
        }
        showModal.value = false;
        await load();
    } catch (err) {
        const msgs = err.response?.data?.errors;
        formError.value = msgs ? Object.values(msgs).flat().join(' · ') : 'Error al guardar';
    }
}

async function deleteAlbum(a) {
    if (!confirm(t('admin.confirm_delete'))) return;
    await axios.delete(`/api/admin/albums/${a.id}`);
    await load();
}
</script>

<style scoped>
.admin-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.75rem; letter-spacing:0.1em; text-transform:uppercase; border-left:4px solid #A51C30; padding-left:1rem; }
.album-item { background:#1A1A1A; }
.album-thumb { width:100%; aspect-ratio:1; overflow:hidden; background:#2A2A2A; }
.album-thumb img { width:100%; height:100%; object-fit:cover; }
.placeholder { width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:2.5rem; }
.album-name { font-family:'Oswald',sans-serif; font-weight:700; font-size:0.9rem; color:#EDE8D8; }
.album-year { font-family:'JetBrains Mono',monospace; font-size:0.75rem; color:#B5AFA0; }
.btn-xs { font-family:'Oswald',sans-serif; font-size:0.7rem; text-transform:uppercase; padding:0.25rem 0.5rem; background:#2A2A2A; color:#EDE8D8; border:none; cursor:pointer; }
.btn-xs.danger { color:#C0392B; }
.btn-primary { background:#A51C30; color:#EDE8D8; font-family:'Oswald',sans-serif; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; padding:0.5rem 1rem; border:none; cursor:pointer; font-size:0.8rem; }
.btn-primary:hover { background:#7A0E0E; }
.btn-ghost { background:transparent; border:1px solid #2A2A2A; color:#B5AFA0; font-family:'Oswald',sans-serif; font-size:0.8rem; padding:0.5rem 1rem; cursor:pointer; }
.form-label { display:block; font-family:'Oswald',sans-serif; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:#B5AFA0; margin-bottom:0.25rem; }
.form-input { width:100%; background:#0D0D0D; border:1px solid #2A2A2A; color:#EDE8D8; padding:0.5rem 0.625rem; font-size:0.875rem; outline:none; }
.form-input:focus { border-color:#A51C30; }
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.8); display:flex; align-items:center; justify-content:center; z-index:50; padding:1rem; }
.modal { background:#1A1A1A; padding:1.5rem; width:100%; max-width:520px; border:1px solid #2A2A2A; max-height:90vh; overflow-y:auto; }
.modal-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.1rem; text-transform:uppercase; letter-spacing:0.1em; }
.text-muted { color:#B5AFA0; }
</style>
