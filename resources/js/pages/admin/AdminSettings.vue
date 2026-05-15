<template>
    <div>
        <h1 class="admin-title mb-6">Ajustes del sitio</h1>

        <div v-if="loading" class="text-muted">Cargando...</div>
        <div v-else>
            <section class="mb-8">
                <h2 class="section-sub mb-4">Texto "Sobre Nosotros"</h2>
                <p class="text-muted text-sm mb-4">Aparece en la página de inicio debajo del logo.</p>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Español</label>
                        <textarea v-model="about.value_es" rows="4" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Deutsch</label>
                        <textarea v-model="about.value_de" rows="4" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">English</label>
                        <textarea v-model="about.value_en" rows="4" class="form-input"></textarea>
                    </div>
                </div>
            </section>

            <section class="mb-8">
                <h2 class="section-sub mb-2">Barra de anuncio</h2>
                <p class="text-muted text-sm mb-4">Aparece en una tira roja debajo del menú. Déjalo vacío para ocultarla.</p>
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Texto (Español)</label>
                        <input v-model="announcement.value_es" type="text" class="form-input" placeholder="Ej: New release 🔥 — This City Wants Blood... (EP)">
                    </div>
                    <div>
                        <label class="form-label">Texto (Deutsch)</label>
                        <input v-model="announcement.value_de" type="text" class="form-input" placeholder="Neues Album 🔥 ...">
                    </div>
                    <div>
                        <label class="form-label">Texto (English)</label>
                        <input v-model="announcement.value_en" type="text" class="form-input" placeholder="New release 🔥 ...">
                    </div>
                </div>
            </section>

            <div class="flex items-center gap-3">
                <button @click="save" class="btn-primary">Guardar cambios</button>
                <span v-if="saved" class="text-sm" style="color:#4A7C59;">✓ Guardado correctamente</span>
                <span v-if="saveError" class="text-sm" style="color:#C0392B;">{{ saveError }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const loading = ref(true);
const saved = ref(false);
const saveError = ref('');
const about = ref({ key: 'about_text', value_es: '', value_de: '', value_en: '' });
const announcement = ref({ key: 'announcement', value_es: '', value_de: '', value_en: '' });

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/admin/settings');
        const s = data['about_text'];
        if (s) {
            about.value.value_es = s.value_es || '';
            about.value.value_de = s.value_de || '';
            about.value.value_en = s.value_en || '';
        }
        const a = data['announcement'];
        if (a) {
            announcement.value.value_es = a.value_es || '';
            announcement.value.value_de = a.value_de || '';
            announcement.value.value_en = a.value_en || '';
        }
    } finally {
        loading.value = false;
    }
});

async function save() {
    saveError.value = '';
    try {
        await axios.post('/api/admin/settings', { settings: [about.value, announcement.value] });
        saved.value = true;
        setTimeout(() => saved.value = false, 3000);
    } catch (err) {
        saveError.value = 'Error al guardar. Inténtalo de nuevo.';
    }
}
</script>

<style scoped>
.admin-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.75rem; letter-spacing:0.1em; text-transform:uppercase; border-left:4px solid #A51C30; padding-left:1rem; }
.section-sub { font-family:'Oswald',sans-serif; font-weight:700; font-size:0.9rem; text-transform:uppercase; letter-spacing:0.1em; color:#B5AFA0; }
.form-label { display:block; font-family:'Oswald',sans-serif; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:#B5AFA0; margin-bottom:0.25rem; }
.form-input { width:100%; background:#0D0D0D; border:1px solid #2A2A2A; color:#EDE8D8; padding:0.5rem 0.625rem; font-size:0.875rem; outline:none; font-family:'Inter',sans-serif; }
textarea.form-input { resize:vertical; }
.form-input:focus { border-color:#A51C30; }
.btn-primary { background:#A51C30; color:#EDE8D8; font-family:'Oswald',sans-serif; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; padding:0.5rem 1.5rem; border:none; cursor:pointer; font-size:0.875rem; }
.btn-primary:hover { background:#7A0E0E; }
.text-muted { color:#B5AFA0; }
</style>
