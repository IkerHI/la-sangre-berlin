<template>
    <div>
        <h1 class="admin-title mb-6">{{ t('admin.tech_specs') }}</h1>

        <div v-if="loading" class="text-muted">{{ t('common.loading') }}</div>
        <div v-else>
            <!-- PDF Upload -->
            <section class="mb-8 p-4" style="background:#1A1A1A; border-left:3px solid #A51C30;">
                <h2 class="section-sub mb-3">Rider PDF</h2>
                <div v-if="pdfs.length" class="mb-3 space-y-1">
                    <div v-for="pdf in pdfs" :key="pdf.id" class="flex items-center gap-3">
                        <a :href="pdf.url" target="_blank" class="text-sangre text-sm">{{ pdf.filename }}</a>
                        <span class="tag">{{ pdf.language }}</span>
                    </div>
                </div>
                <form @submit.prevent="uploadPdf" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="form-label">PDF</label>
                        <input ref="pdfInputRef" type="file" accept=".pdf" @change="onPdfChange" class="form-input text-sm" style="width:auto;">
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
                    <span v-if="pdfError" class="text-sm ml-2" style="color:#C0392B;">{{ pdfError }}</span>
                </form>
            </section>

            <!-- Specs table -->
            <section>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="section-sub">Especificaciones</h2>
                    <button @click="addRow" class="btn-primary text-sm">+ Añadir fila</button>
                </div>
                <table class="admin-table w-full mb-4">
                    <thead>
                        <tr>
                            <th>Categoría</th><th>Campo</th><th>ES</th><th>DE</th><th>EN</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(spec, i) in editableSpecs" :key="i">
                            <td><input v-model="spec.category" class="form-input-sm"></td>
                            <td><input v-model="spec.key" class="form-input-sm"></td>
                            <td><input v-model="spec.value_es" class="form-input-sm"></td>
                            <td><input v-model="spec.value_de" class="form-input-sm"></td>
                            <td><input v-model="spec.value_en" class="form-input-sm"></td>
                            <td>
                                <button @click="removeRow(i, spec)" style="background:none;border:none;cursor:pointer;color:#C0392B;">🗑</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button @click="saveSpecs" class="btn-primary">{{ t('admin.save') }}</button>
                <span v-if="saveMsg" class="ml-3 text-sm" style="color:#4A7C59;">{{ saveMsg }}</span>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();
const loading = ref(true);
const editableSpecs = ref([]);
const pdfs = ref([]);
const pdfFile = ref(null);
const pdfInputRef = ref(null);
const pdfLang = ref('all');
const pdfError = ref('');
const saveMsg = ref('');

async function load() {
    const { data } = await axios.get('/api/admin/tech-specs');
    editableSpecs.value = data.specs.map(s => ({ ...s }));
    pdfs.value = data.pdfs;
}

onMounted(async () => {
    try { await load(); } finally { loading.value = false; }
});

function addRow() {
    editableSpecs.value.push({ category: '', key: '', value_es: '', value_de: '', value_en: '', sort_order: editableSpecs.value.length });
}

async function removeRow(i, spec) {
    if (spec.id && !confirm(t('admin.confirm_delete'))) return;
    if (spec.id) await axios.delete(`/api/admin/tech-specs/${spec.id}`);
    editableSpecs.value.splice(i, 1);
}

async function saveSpecs() {
    await axios.post('/api/admin/tech-specs', { specs: editableSpecs.value });
    saveMsg.value = '✓ Guardado';
    setTimeout(() => saveMsg.value = '', 3000);
    await load();
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
.admin-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.75rem; letter-spacing:0.1em; text-transform:uppercase; border-left:4px solid #A51C30; padding-left:1rem; }
.section-sub { font-family:'Oswald',sans-serif; font-weight:700; font-size:0.9rem; text-transform:uppercase; letter-spacing:0.1em; color:#B5AFA0; }
.admin-table { border-collapse:collapse; }
.admin-table th { font-family:'Oswald',sans-serif; font-size:0.7rem; text-transform:uppercase; letter-spacing:0.05em; color:#B5AFA0; text-align:left; padding:0.4rem 0.5rem; border-bottom:1px solid #2A2A2A; }
.admin-table td { padding:0.25rem 0.25rem; border-bottom:1px solid #1A1A1A; }
.form-input-sm { width:100%; background:#0D0D0D; border:1px solid #2A2A2A; color:#EDE8D8; padding:0.3rem 0.4rem; font-size:0.8rem; outline:none; }
.form-input-sm:focus { border-color:#A51C30; }
.form-label { display:block; font-family:'Oswald',sans-serif; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:#B5AFA0; margin-bottom:0.25rem; }
.form-input { background:#0D0D0D; border:1px solid #2A2A2A; color:#EDE8D8; padding:0.5rem 0.625rem; font-size:0.875rem; outline:none; }
.form-input:focus { border-color:#A51C30; }
.btn-primary { background:#A51C30; color:#EDE8D8; font-family:'Oswald',sans-serif; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; padding:0.5rem 1rem; border:none; cursor:pointer; font-size:0.8rem; }
.btn-primary:hover { background:#7A0E0E; }
.tag { font-family:'JetBrains Mono',monospace; font-size:0.65rem; text-transform:uppercase; background:#2A2A2A; color:#B5AFA0; padding:0.1rem 0.35rem; }
.text-sangre { color:#A51C30; text-decoration:none; }
.text-muted { color:#B5AFA0; }
</style>
