<template>
    <div>
        <h1 class="admin-title mb-6">{{ t('admin.tech_specs') }}</h1>

        <div v-if="loading" class="text-muted">{{ t('common.loading') }}</div>
        <div v-else>
            <!-- PDF Upload -->
            <section class="admin-card mb-8">
                <h2 class="section-sub mb-3">Rider PDF</h2>
                <div v-if="pdfs.length" class="mb-3 pdf-list">
                    <div v-for="pdf in pdfs" :key="pdf.id" class="pdf-item">
                        <a :href="pdf.url" target="_blank" class="text-sangre">{{ pdf.filename }}</a>
                        <span class="tag">{{ pdf.language }}</span>
                    </div>
                </div>
                <form @submit.prevent="uploadPdf" class="pdf-form">
                    <div>
                        <label class="form-label">PDF</label>
                        <input ref="pdfInputRef" type="file" accept=".pdf" @change="onPdfChange" class="form-input" style="width:auto;">
                    </div>
                    <div>
                        <label class="form-label">Idioma</label>
                        <select v-model="pdfLang" class="form-input" style="width:auto;">
                            <option value="all">Todos</option>
                            <option value="es">ES</option>
                            <option value="de">DE</option>
                            <option value="en">EN</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">{{ t('admin.upload') }}</button>
                    <span v-if="pdfError" class="error-msg">{{ pdfError }}</span>
                </form>
            </section>

            <!-- Lines Config Editor -->
            <section class="admin-card">
                <h2 class="section-sub mb-4">Channel List — Stage Plot</h2>

                <!-- Config tabs -->
                <div class="config-tabs mb-6">
                    <button
                        v-for="cfg in configKeys"
                        :key="cfg"
                        class="config-tab"
                        :class="{ 'config-tab--active': activeTab === cfg }"
                        @click="activeTab = cfg"
                    >{{ configLabels[cfg] }}</button>
                </div>

                <!-- Channel table -->
                <div class="table-wrap mb-4">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th style="width:48px">Ch</th>
                                <th>Instrument</th>
                                <th>Mic / DI</th>
                                <th>Effects</th>
                                <th style="width:90px">Artist</th>
                                <th>Notes</th>
                                <th style="width:40px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ch, i) in currentChannels" :key="i">
                                <td><input v-model.number="ch.ch" type="number" class="form-input-sm" style="width:48px"></td>
                                <td><input v-model="ch.instrument" class="form-input-sm"></td>
                                <td><input v-model="ch.mic" class="form-input-sm"></td>
                                <td><input v-model="ch.effects" class="form-input-sm"></td>
                                <td><input v-model="ch.artist" class="form-input-sm"></td>
                                <td><input v-model="ch.notes" class="form-input-sm"></td>
                                <td>
                                    <button @click="removeChannel(i)" class="btn-danger-sm">✕</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button @click="addChannel" class="btn-secondary mb-6">+ Añadir canal</button>

                <!-- Technical notes -->
                <div class="notes-grid mb-4">
                    <div>
                        <label class="form-label">Notas técnicas (ES)</label>
                        <textarea v-model="currentConfig.tech_notes_es" rows="5" class="form-input form-textarea"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Technische Hinweise (DE)</label>
                        <textarea v-model="currentConfig.tech_notes_de" rows="5" class="form-input form-textarea"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Technical Notes (EN)</label>
                        <textarea v-model="currentConfig.tech_notes_en" rows="5" class="form-input form-textarea"></textarea>
                    </div>
                </div>

                <div class="save-row">
                    <button @click="saveConfig" class="btn-primary">{{ t('admin.save') }}</button>
                    <span v-if="saveMsg" class="success-msg">{{ saveMsg }}</span>
                    <span v-if="saveError" class="error-msg">{{ saveError }}</span>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();
const loading = ref(true);
const pdfs = ref([]);
const pdfFile = ref(null);
const pdfInputRef = ref(null);
const pdfLang = ref('all');
const pdfError = ref('');
const saveMsg = ref('');
const saveError = ref('');

const configKeys = ['duo', 'pieter', 'full'];
const configLabels = { duo: 'Duo', pieter: '+Pieter', full: 'Full' };
const activeTab = ref('duo');

const configs = ref({
    duo:    { config: 'duo',    channels: [], tech_notes_es: '', tech_notes_de: '', tech_notes_en: '' },
    pieter: { config: 'pieter', channels: [], tech_notes_es: '', tech_notes_de: '', tech_notes_en: '' },
    full:   { config: 'full',   channels: [], tech_notes_es: '', tech_notes_de: '', tech_notes_en: '' },
});

const currentConfig = computed(() => configs.value[activeTab.value]);
const currentChannels = computed(() => configs.value[activeTab.value].channels);

async function load() {
    const [specsRes, linesRes] = await Promise.all([
        axios.get('/api/admin/tech-specs'),
        axios.get('/api/rider-lines'),
    ]);
    pdfs.value = specsRes.data.pdfs;

    for (const key of configKeys) {
        if (linesRes.data[key]) {
            configs.value[key] = {
                ...linesRes.data[key],
                channels: linesRes.data[key].channels ?? [],
            };
        }
    }
}

onMounted(async () => {
    try { await load(); } finally { loading.value = false; }
});

function addChannel() {
    const nextCh = currentChannels.value.length + 1;
    currentChannels.value.push({ ch: nextCh, instrument: '', mic: '', effects: '', artist: '', notes: '' });
}

function removeChannel(i) {
    currentChannels.value.splice(i, 1);
}

async function saveConfig() {
    saveMsg.value = '';
    saveError.value = '';
    try {
        await axios.put(`/api/admin/rider-lines/${activeTab.value}`, currentConfig.value);
        saveMsg.value = '✓ Guardado';
        setTimeout(() => saveMsg.value = '', 3000);
    } catch {
        saveError.value = 'Error al guardar';
    }
}

function onPdfChange(e) { pdfFile.value = e.target.files[0] || null; }

async function uploadPdf() {
    if (!pdfFile.value) return;
    pdfError.value = '';
    const fd = new FormData();
    fd.append('pdf', pdfFile.value);
    fd.append('language', pdfLang.value);
    try {
        await axios.post('/api/admin/tech-specs/pdf', fd);
        pdfFile.value = null;
        pdfInputRef.value && (pdfInputRef.value.value = '');
        await load();
    } catch (err) {
        const msgs = err.response?.data?.errors;
        pdfError.value = msgs ? Object.values(msgs).flat().join(' · ') : 'Error al subir el PDF';
    }
}
</script>

<style scoped>
.admin-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.75rem; letter-spacing:0.1em; text-transform:uppercase; border-left:4px solid #C8192A; padding-left:1rem; }
.section-sub { font-family:'Oswald',sans-serif; font-weight:700; font-size:0.9rem; text-transform:uppercase; letter-spacing:0.1em; color:#B5AFA0; }
.text-sangre { color:#C8192A; text-decoration:none; font-family:'Space Mono',monospace; font-size:0.75rem; }
.text-muted { color:#B5AFA0; font-family:'Space Mono',monospace; font-size:0.8rem; }
.tag { font-family:'Space Mono',monospace; font-size:0.6rem; text-transform:uppercase; background:#2A2A2A; color:#B5AFA0; padding:0.15rem 0.4rem; margin-left:0.5rem; }

.admin-card { background:#141414; border:1px solid #1E1E1E; border-left:3px solid #C8192A; padding:1.5rem; }

.pdf-list { display:flex; flex-direction:column; gap:0.5rem; margin-bottom:1rem; }
.pdf-item { display:flex; align-items:center; gap:0.5rem; }

.pdf-form { display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-end; }

/* Config tabs */
.config-tabs { display:flex; gap:2px; }
.config-tab { background:#1A1A1A; border:none; border-bottom:2px solid transparent; padding:0.5rem 1.25rem; color:#888; font-family:'Oswald',sans-serif; font-size:0.85rem; letter-spacing:0.1em; text-transform:uppercase; cursor:pointer; transition:all 0.15s; }
.config-tab:hover { color:#F2EDE3; background:#1E1E1E; }
.config-tab--active { background:#1E1E1E; border-bottom-color:#C8192A; color:#F2EDE3; }

/* Table */
.table-wrap { overflow-x:auto; }
.admin-table { border-collapse:collapse; min-width:700px; width:100%; }
.admin-table th { font-family:'Oswald',sans-serif; font-size:0.68rem; text-transform:uppercase; letter-spacing:0.08em; color:#888; text-align:left; padding:0.4rem 0.5rem; border-bottom:1px solid #2A2A2A; white-space:nowrap; }
.admin-table td { padding:0.2rem 0.25rem; border-bottom:1px solid #1A1A1A; }
.form-input-sm { width:100%; background:#0D0D0D; border:1px solid #2A2A2A; color:#EDE8D8; padding:0.3rem 0.4rem; font-size:0.78rem; font-family:'Space Mono',monospace; outline:none; }
.form-input-sm:focus { border-color:#C8192A; }

.btn-danger-sm { background:none; border:none; cursor:pointer; color:#888; font-size:0.75rem; padding:0.2rem 0.4rem; transition:color 0.15s; }
.btn-danger-sm:hover { color:#C8192A; }

/* Notes grid */
.notes-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }
@media (max-width:900px) { .notes-grid { grid-template-columns:1fr; } }

/* Form elements */
.form-label { display:block; font-family:'Oswald',sans-serif; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:#888; margin-bottom:0.25rem; }
.form-input { background:#0D0D0D; border:1px solid #2A2A2A; color:#EDE8D8; padding:0.5rem 0.625rem; font-size:0.8rem; font-family:'Space Mono',monospace; outline:none; width:100%; }
.form-input:focus { border-color:#C8192A; }
.form-textarea { resize:vertical; }

.btn-primary { background:#C8192A; color:#F2EDE3; font-family:'Oswald',sans-serif; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; padding:0.5rem 1.25rem; border:none; cursor:pointer; font-size:0.8rem; transition:background 0.15s; }
.btn-primary:hover { background:#a01420; }
.btn-secondary { background:#1E1E1E; color:#F2EDE3; font-family:'Oswald',sans-serif; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; padding:0.4rem 1rem; border:1px solid #2A2A2A; cursor:pointer; font-size:0.75rem; transition:background 0.15s; }
.btn-secondary:hover { background:#2A2A2A; }

.save-row { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
.success-msg { color:#4A7C59; font-family:'Space Mono',monospace; font-size:0.72rem; }
.error-msg { color:#C0392B; font-family:'Space Mono',monospace; font-size:0.72rem; }

@media (max-width:640px) {
    .pdf-form { flex-direction:column; }
    .config-tabs { flex-wrap:wrap; }
}
</style>
