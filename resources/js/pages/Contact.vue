<template>
    <div class="bb-page">

        <!-- ── TECH SPECS ── -->
        <section class="bb-section">
            <div class="bb-section-label">
                <span class="bb-num">01</span> Rider
            </div>
            <h1 class="page-title bb-reveal">{{ t('contact.specs_title') }}</h1>

            <div v-if="specsLoading" class="bb-loading">{{ t('common.loading') }}</div>
            <div v-else>
                <div v-for="(items, category) in specs" :key="category" class="specs-group bb-reveal">
                    <div class="specs-category">// {{ category }}</div>
                    <table class="specs-table">
                        <tbody>
                            <tr v-for="spec in items" :key="spec.id" class="spec-row">
                                <td class="spec-key">{{ spec.key }}</td>
                                <td class="spec-val">{{ spec[`value_${locale}`] || spec.value_es }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="pdfs.length" class="pdf-row bb-reveal">
                    <a
                        v-for="pdf in pdfs"
                        :key="pdf.id"
                        :href="pdf.url"
                        target="_blank"
                        class="btn-download"
                    >
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        {{ t('contact.download_rider') }}
                        <span v-if="pdf.language !== 'all'" class="pdf-lang">({{ pdf.language }})</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- divider -->
        <div class="bb-divider"></div>

        <!-- ── CONTACT ── -->
        <section id="contact-form" class="bb-section" style="padding-top: 5rem;">
            <div class="contact-grid">

                <!-- Left: info -->
                <div>
                    <div class="bb-section-label">
                        <span class="bb-num">02</span> {{ t('nav.contact') }}
                    </div>
                    <h2 class="section-title bb-reveal">{{ t('contact.form_title') }}</h2>

                    <div class="info-stack">
                        <div class="info-block bb-reveal">
                            <div class="info-label">{{ t('contact.label_booking') }}</div>
                            <a href="mailto:contacto@lasangreberlin.com" class="info-link">
                                contacto@lasangreberlin.com
                            </a>
                        </div>
                        <div class="info-block bb-reveal">
                            <div class="info-label">{{ t('contact.label_base') }}</div>
                            <div class="info-value">{{ t('contact.location') }}</div>
                        </div>
                        <div class="info-block bb-reveal">
                            <div class="info-label">{{ t('contact.label_epk') }}</div>
                            <div v-if="pdfs.length">
                                <a v-for="pdf in pdfs" :key="pdf.id" :href="pdf.url" target="_blank" class="info-link info-link--small">
                                    {{ t('contact.download_pdf') }}
                                </a>
                            </div>
                            <div v-else class="info-value">{{ t('contact.on_request') }}</div>
                        </div>
                        <div class="info-block bb-reveal">
                            <div class="info-label">{{ t('contact.label_social') }}</div>
                            <div class="info-socials">
                                <a href="https://www.instagram.com/la.sangre.berlin/" target="_blank" rel="noopener" class="social-pill">Instagram</a>
                                <a href="https://open.spotify.com/artist/1glzEl3omzvBy2h1UCEaJg" target="_blank" rel="noopener" class="social-pill">Spotify</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: form -->
                <div>
                    <form @submit.prevent="submitForm" class="contact-form">
                        <div class="form-row">
                            <div class="field-group">
                                <label class="form-label">{{ t('contact.name') }} *</label>
                                <input v-model="form.name" type="text" required class="form-input" :placeholder="t('contact.name')">
                                <p v-if="errors.name" class="field-error">{{ errors.name[0] }}</p>
                            </div>
                            <div class="field-group">
                                <label class="form-label">{{ t('contact.email') }} *</label>
                                <input v-model="form.email" type="email" required class="form-input" :placeholder="t('contact.email')">
                                <p v-if="errors.email" class="field-error">{{ errors.email[0] }}</p>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="form-label">{{ t('contact.subject') }} *</label>
                            <input v-model="form.subject" type="text" required class="form-input" :placeholder="t('contact.subject')">
                            <p v-if="errors.subject" class="field-error">{{ errors.subject[0] }}</p>
                        </div>
                        <div class="field-group">
                            <label class="form-label">{{ t('contact.message') }} *</label>
                            <textarea v-model="form.message" required rows="7" class="form-input form-textarea" :placeholder="t('contact.message')"></textarea>
                            <p v-if="errors.message" class="field-error">{{ errors.message[0] }}</p>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-submit" :disabled="submitting">
                                {{ submitting ? '...' : t('contact.send') }}
                            </button>
                            <Transition name="fade">
                                <p v-if="success" class="success-msg">// {{ t('contact.success') }}</p>
                            </Transition>
                        </div>
                        <p v-if="submitError" class="field-error">{{ t('contact.error') }}</p>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t, locale } = useI18n();
const specs = ref({});
const pdfs = ref([]);
const specsLoading = ref(true);

const form = ref({ name: '', email: '', subject: '', message: '' });
const errors = ref({});
const submitting = ref(false);
const success = ref(false);
const submitError = ref(false);

onMounted(async () => {
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('bb-visible'); });
    }, { threshold: 0.08 });
    setTimeout(() => {
        document.querySelectorAll('.bb-reveal').forEach(el => obs.observe(el));
    }, 100);

    try {
        const { data } = await axios.get('/api/tech-specs');
        specs.value = data.specs ?? {};
        pdfs.value  = data.pdfs ?? [];
    } catch {
        specs.value = {};
    } finally {
        specsLoading.value = false;
    }
});

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

async function submitForm() {
    errors.value = {};
    success.value = false;
    submitError.value = false;

    if (!emailRegex.test(form.value.email)) {
        errors.value = { email: [t('contact.email_invalid')] };
        return;
    }

    submitting.value = true;

    try {
        await axios.post('/api/contact', form.value);
        success.value = true;
        form.value = { name: '', email: '', subject: '', message: '' };
        setTimeout(() => success.value = false, 5000);
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors ?? {};
        } else {
            submitError.value = true;
        }
    } finally {
        submitting.value = false;
    }
}
</script>

<style scoped>
.bb-page {
    max-width: 72rem;
    margin: 0 auto;
    padding: 0 2rem 6rem;
}

.bb-section {
    padding: 5rem 0;
}

.section-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(2.5rem, 5vw, 5rem);
    line-height: 0.88;
    letter-spacing: -0.01em;
    margin-bottom: 2.5rem;
}

.bb-divider { border-top: 1px solid #1E1E1E; }

/* ── SPECS ── */
.specs-group { margin-bottom: 2.5rem; }

.specs-category {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: #C8192A;
    margin-bottom: 0.75rem;
}

.specs-table { width: 100%; border-collapse: collapse; }
.spec-row { border-bottom: 1px solid #1E1E1E; }
.spec-key {
    font-family: 'Space Mono', monospace;
    font-size: 0.68rem;
    color: #888888;
    padding: 0.6rem 1.5rem 0.6rem 0;
    width: 35%;
    vertical-align: top;
    letter-spacing: 0.05em;
}
.spec-val {
    font-family: 'Space Mono', monospace;
    font-size: 0.75rem;
    color: #F2EDE3;
    padding: 0.6rem 0;
    letter-spacing: 0.03em;
}

.pdf-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 2.5rem;
}
.pdf-lang { opacity: 0.6; text-transform: uppercase; }

/* ── CONTACT GRID ── */
.contact-grid {
    display: grid;
    grid-template-columns: 2fr 3fr;
    gap: 5rem;
    align-items: start;
}
@media (max-width: 768px) {
    .contact-grid { grid-template-columns: 1fr; gap: 3rem; }
}

.info-stack { display: flex; flex-direction: column; gap: 1.75rem; }

.info-block {}
.info-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.55rem;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    color: #C8192A;
    margin-bottom: 0.35rem;
}
.info-value {
    font-family: 'Space Mono', monospace;
    font-size: 0.75rem;
    color: #888888;
    letter-spacing: 0.05em;
}
.info-link {
    font-family: 'Space Mono', monospace;
    font-size: 0.75rem;
    color: #888888;
    text-decoration: none;
    letter-spacing: 0.05em;
    transition: color 0.18s;
    display: block;
}
.info-link:hover { color: #F2EDE3; }
.info-link--small { font-size: 0.68rem; }

.info-socials { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.1rem; }
.social-pill {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #888888;
    border: 1px solid #1E1E1E;
    padding: 0.4rem 0.85rem;
    text-decoration: none;
    transition: all 0.18s;
}
.social-pill:hover { border-color: #C8192A; color: #C8192A; }

/* ── FORM ── */
.contact-form { display: flex; flex-direction: column; gap: 1.25rem; }

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
@media (max-width: 520px) { .form-row { grid-template-columns: 1fr; } }

.field-group { display: flex; flex-direction: column; }

.form-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: #888888;
    margin-bottom: 0.5rem;
}

.form-input {
    background: #101010;
    border: 1px solid #1E1E1E;
    border-bottom: 2px solid #1E1E1E;
    color: #F2EDE3;
    padding: 0.75rem 0.875rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.78rem;
    outline: none;
    transition: border-color 0.2s;
    width: 100%;
}
.form-input:focus { border-color: #C8192A; border-bottom-color: #C8192A; }
.form-input::placeholder { color: #2A2A2A; }
.form-textarea { resize: none; }

.form-actions {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.btn-submit {
    display: inline-block;
    background: #C8192A;
    color: #F2EDE3;
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    padding: 0.9rem 2.5rem;
    border: none;
    cursor: pointer;
    transition: all 0.18s;
    clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);
}
.btn-submit:hover { background: #FF2233; transform: translate(-1px,-1px); box-shadow: 2px 2px 0 rgba(200,25,42,0.35); }
.btn-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

.field-error { color: #C0392B; font-family: 'Space Mono', monospace; font-size: 0.65rem; margin-top: 0.35rem; letter-spacing: 0.05em; }
.success-msg { color: #4A7C59; font-family: 'Space Mono', monospace; font-size: 0.72rem; letter-spacing: 0.1em; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.4s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
