<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <h1 class="admin-title">{{ t('admin.gallery') }}</h1>
            <button @click="openUpload" class="btn-primary">+ {{ t('admin.upload') }}</button>
        </div>

        <!-- Filter tabs -->
        <div class="filter-tabs mb-4">
            <button
                v-for="f in filters"
                :key="f.value"
                class="filter-btn"
                :class="{ active: activeFilter === f.value }"
                @click="activeFilter = f.value"
            >
                {{ f.label }}
                <span class="count">{{ countFor(f.value) }}</span>
            </button>
        </div>

        <!-- Grid -->
        <div v-if="loading" class="text-muted text-sm">{{ t('common.loading') }}</div>
        <div v-else-if="filtered.length === 0" class="text-muted text-sm py-8 text-center">
            Sin imágenes
        </div>
        <div v-else class="thumb-grid">
            <div
                v-for="img in filtered"
                :key="img.id"
                class="thumb-item"
                @click="openEdit(img)"
                :title="img.alt_es || img.filename"
            >
                <img :src="img.url" :alt="img.alt_es || ''" class="thumb-img">
                <span class="type-badge" :class="img.type">{{ img.type }}</span>
            </div>
        </div>

        <!-- Upload modal -->
        <div v-if="showUpload" class="modal-overlay" @click.self="showUpload = false">
            <div class="modal">
                <h2 class="modal-title">Subir imagen</h2>
                <form @submit.prevent="submitUpload" class="space-y-3 mt-4">
                    <div>
                        <label class="form-label">Imagen *</label>
                        <input ref="fileInputRef" type="file" accept="image/*" @change="onFileChange" required class="form-input">
                    </div>

                    <!-- Preview -->
                    <div v-if="previewUrl" class="preview-box">
                        <img :src="previewUrl" alt="preview" class="preview-img">
                    </div>

                    <div>
                        <label class="form-label">Tipo *</label>
                        <select v-model="uploadForm.type" class="form-input">
                            <option value="normal">Foto</option>
                            <option value="epk">EPK</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Alt text (ES)</label>
                        <input v-model="uploadForm.alt_es" type="text" class="form-input" placeholder="Descripción breve">
                    </div>
                    <p v-if="uploadError" class="error-msg">{{ uploadError }}</p>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" :disabled="uploading" class="btn-primary">
                            {{ uploading ? 'Subiendo…' : t('admin.upload') }}
                        </button>
                        <button type="button" @click="showUpload = false" class="btn-ghost">{{ t('admin.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit modal -->
        <div v-if="editTarget" class="modal-overlay" @click.self="editTarget = null">
            <div class="modal">
                <div class="edit-preview-row">
                    <img :src="editTarget.url" :alt="editTarget.alt_es || ''" class="edit-thumb">
                    <div class="edit-fields">
                        <p class="filename-text">{{ editTarget.filename }}</p>
                        <div>
                            <label class="form-label">Tipo</label>
                            <select v-model="editForm.type" class="form-input">
                                <option value="normal">Foto</option>
                                <option value="epk">EPK</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Alt text (ES)</label>
                            <input v-model="editForm.alt_es" type="text" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Alt text (DE)</label>
                            <input v-model="editForm.alt_de" type="text" class="form-input">
                        </div>
                    </div>
                </div>
                <p v-if="editError" class="error-msg mt-2">{{ editError }}</p>
                <div class="flex gap-2 pt-4 justify-between">
                    <button @click="confirmDelete" class="btn-danger">Eliminar</button>
                    <div class="flex gap-2">
                        <button @click="submitEdit" class="btn-primary">Guardar</button>
                        <button @click="editTarget = null" class="btn-ghost">{{ t('admin.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();

const images    = ref([]);
const loading   = ref(true);

const activeFilter = ref('all');
const filters = [
    { value: 'all',    label: 'Todas' },
    { value: 'normal', label: 'Fotos' },
    { value: 'epk',    label: 'EPK' },
];

const filtered = computed(() =>
    activeFilter.value === 'all'
        ? images.value
        : images.value.filter(i => i.type === activeFilter.value)
);
const countFor = v => v === 'all' ? images.value.length : images.value.filter(i => i.type === v).length;

// Upload
const showUpload  = ref(false);
const uploading   = ref(false);
const imageFile   = ref(null);
const previewUrl  = ref(null);
const fileInputRef = ref(null);
const uploadForm  = ref({ type: 'normal', alt_es: '' });
const uploadError = ref('');

function openUpload() {
    uploadForm.value = { type: 'normal', alt_es: '' };
    imageFile.value  = null;
    previewUrl.value = null;
    uploadError.value = '';
    showUpload.value = true;
}

function onFileChange(e) {
    const f = e.target.files[0] || null;
    imageFile.value  = f;
    previewUrl.value = f ? URL.createObjectURL(f) : null;
}

async function submitUpload() {
    if (!imageFile.value || uploading.value) return;
    uploading.value   = true;
    uploadError.value = '';
    const fd = new FormData();
    fd.append('image',  imageFile.value);
    fd.append('type',   uploadForm.value.type);
    fd.append('alt_es', uploadForm.value.alt_es);
    try {
        await axios.post('/api/admin/gallery', fd);
        showUpload.value = false;
        await load();
    } catch (err) {
        const msgs = err.response?.data?.errors;
        uploadError.value = msgs ? Object.values(msgs).flat().join(' · ') : 'Error al subir la imagen';
    } finally {
        uploading.value = false;
    }
}

// Edit
const editTarget = ref(null);
const editForm   = ref({});
const editError  = ref('');

function openEdit(img) {
    editTarget.value = img;
    editForm.value   = { type: img.type, alt_es: img.alt_es || '', alt_de: img.alt_de || '' };
    editError.value  = '';
}

async function submitEdit() {
    editError.value = '';
    try {
        await axios.patch(`/api/admin/gallery/${editTarget.value.id}`, editForm.value);
        editTarget.value = null;
        await load();
    } catch {
        editError.value = 'Error al guardar';
    }
}

async function confirmDelete() {
    if (!confirm(t('admin.confirm_delete'))) return;
    await axios.delete(`/api/admin/gallery/${editTarget.value.id}`);
    editTarget.value = null;
    await load();
}

async function load() {
    const { data } = await axios.get('/api/admin/gallery');
    images.value = data;
}

onMounted(async () => {
    try { await load(); } finally { loading.value = false; }
});
</script>

<style scoped>
.admin-title {
    font-family: 'Oswald', sans-serif;
    font-weight: 700;
    font-size: 1.75rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    border-left: 4px solid #A51C30;
    padding-left: 1rem;
}

/* Filter tabs */
.filter-tabs { display: flex; gap: 0.25rem; }
.filter-btn {
    font-family: 'Oswald', sans-serif;
    font-size: 0.7rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 0.3rem 0.75rem;
    background: #111;
    border: 1px solid #2A2A2A;
    color: #B5AFA0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    transition: border-color 0.15s;
}
.filter-btn:hover { border-color: #555; color: #EDE8D8; }
.filter-btn.active { border-color: #A51C30; color: #EDE8D8; }
.count {
    background: #2A2A2A;
    color: #888;
    font-size: 0.6rem;
    padding: 0.05rem 0.3rem;
    border-radius: 2px;
}
.filter-btn.active .count { background: #A51C30; color: #EDE8D8; }

/* Thumbnail grid */
.thumb-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.thumb-item {
    position: relative;
    width: 80px;
    height: 80px;
    overflow: hidden;
    background: #1A1A1A;
    cursor: pointer;
    flex-shrink: 0;
    border: 1px solid #2A2A2A;
    transition: border-color 0.15s;
}
.thumb-item:hover { border-color: #A51C30; }
.thumb-img { width: 100%; height: 100%; object-fit: cover; display: block; }
.type-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.5rem;
    text-transform: uppercase;
    padding: 0.1rem 0.25rem;
    line-height: 1.2;
}
.type-badge.normal { background: rgba(0,0,0,0.7); color: #888; }
.type-badge.epk    { background: #A51C30; color: #EDE8D8; }

/* Upload preview */
.preview-box {
    background: #0D0D0D;
    border: 1px solid #2A2A2A;
    padding: 4px;
    text-align: center;
    max-height: 180px;
    overflow: hidden;
}
.preview-img { max-height: 172px; max-width: 100%; object-fit: contain; margin: auto; display: block; }

/* Edit modal layout */
.edit-preview-row { display: flex; gap: 1rem; align-items: flex-start; }
.edit-thumb {
    width: 120px;
    height: 120px;
    object-fit: cover;
    flex-shrink: 0;
    border: 1px solid #2A2A2A;
}
.edit-fields { flex: 1; display: flex; flex-direction: column; gap: 0.6rem; }
.filename-text {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.65rem;
    color: #666;
    word-break: break-all;
    margin-bottom: 0.25rem;
}

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
.btn-danger {
    background: transparent; border: 1px solid #A51C30; color: #A51C30;
    font-family: 'Oswald', sans-serif; font-size: 0.8rem;
    padding: 0.5rem 1rem; cursor: pointer;
}
.btn-danger:hover { background: #A51C30; color: #EDE8D8; }
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
    position: fixed; inset: 0; background: rgba(0,0,0,0.8);
    display: flex; align-items: center; justify-content: center; z-index: 50;
}
.modal {
    background: #1A1A1A; padding: 1.5rem;
    width: 100%; max-width: 460px; border: 1px solid #2A2A2A;
}
.modal-title { font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 0.1em; }
.error-msg { color: #C0392B; font-size: 0.8rem; }
.text-muted { color: #B5AFA0; }
</style>
