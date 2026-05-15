<template>
    <div class="login-screen">
        <!-- Atmospheric backdrop -->
        <div class="bg-radial"></div>
        <div class="bg-scan"></div>

        <!-- Corner labels -->
        <div class="corner corner-tl"><span class="mono-label">// LSB · ADMIN PANEL · v3.2</span></div>
        <div class="corner corner-tr"><span class="mono-label">BERLIN · 52.520° N, 13.405° E</span></div>
        <div class="corner corner-bl"><span class="mono-label">SECURE CHANNEL · TLS 1.3</span></div>
        <div class="corner corner-br"><span class="mono-label">ROCK DESDE LAS ENTRAÑAS ★</span></div>

        <!-- Brand bar -->
        <div class="brand-bar">
            <span class="brand-bar-text">LA <span class="brand-bar-red">SANGRE</span> BERLÍN</span>
        </div>

        <!-- Login card -->
        <form @submit.prevent="handleLogin" class="login-card">
            <div class="card-accent-corner"></div>

            <div class="logo-row">
                <svg width="48" height="48" viewBox="0 0 60 60" fill="none" aria-hidden="true">
                    <rect x="6" y="6" width="48" height="48" stroke="#C8192A" stroke-width="3"/>
                    <path d="M14 14 L46 46 M46 14 L14 46" stroke="#C8192A" stroke-width="3"/>
                    <rect x="28" y="50" width="4" height="8" fill="#C8192A"/>
                </svg>
                <div>
                    <div class="zone-label">// ZONA RESTRINGIDA</div>
                    <h1 class="card-h1">Acceso<br><span class="h1-red">Administrador</span></h1>
                </div>
            </div>

            <div class="fields-col">
                <div class="field-group">
                    <label class="form-label" for="email">{{ t('admin.email') }}</label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        required
                        class="form-input"
                        autocomplete="email"
                        placeholder="banda@lasangreberlin.com"
                    >
                </div>
                <div class="field-group">
                    <label class="form-label" for="pw">{{ t('admin.password') }}</label>
                    <input
                        id="pw"
                        v-model="password"
                        type="password"
                        required
                        class="form-input"
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                </div>

                <div v-if="error" class="err-row">
                    <span class="err-prefix">ERR_</span>
                    <span class="err-msg">{{ error }}</span>
                </div>

                <button type="submit" class="btn-submit" :disabled="loading">
                    {{ loading ? 'Verificando…' : t('admin.login') + ' →' }}
                </button>
            </div>

            <div class="card-foot">
                <span class="foot-label">SOLO PERSONAL AUTORIZADO</span>
                <RouterLink to="/" class="foot-back">← Sitio</RouterLink>
            </div>
        </form>

        <!-- Marquee strip -->
        <div class="marquee-strip">
            <div class="marquee-track">
                <span v-for="i in 6" :key="i" class="marquee-item">
                    LA SANGRE BERLÍN <span class="marquee-star">★</span> THIS CITY WANTS BLOOD <span class="marquee-star">★</span> ROCK FROM THE GUTS OF BERLIN <span class="marquee-star">★</span>&nbsp;
                </span>
            </div>
        </div>

        <!-- Success flash overlay -->
        <div class="flash-overlay" :class="{ 'flash-active': flashActive }"></div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useAuthStore } from '../../stores/auth';

const { t } = useI18n();
const router = useRouter();
const auth = useAuthStore();

const email = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');
const flashActive = ref(false);

async function handleLogin() {
    loading.value = true;
    error.value = '';
    try {
        await auth.login(email.value, password.value);
        flashActive.value = true;
        setTimeout(() => {
            router.push('/admin');
        }, 380);
    } catch (err) {
        const data = err.response?.data;
        error.value =
            data?.errors?.email?.[0] ||
            data?.errors?.password?.[0] ||
            data?.message ||
            t('admin.login_error');
    } finally {
        loading.value = false;
    }
}
</script>

<style scoped>
/* ── Full screen shell ── */
.login-screen {
    position: relative;
    min-height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #080808;
    overflow: hidden;
}

/* Atmospheric layers */
.bg-radial {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 35%, rgba(200,25,42,0.14) 0%, rgba(200,25,42,0.04) 35%, transparent 65%);
    pointer-events: none;
}
.bg-scan {
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: 0.3;
    background-image: repeating-linear-gradient(0deg, rgba(255,255,255,0.012) 0px, rgba(255,255,255,0.012) 1px, transparent 1px, transparent 3px);
}

/* Corner labels */
.corner {
    position: absolute;
    pointer-events: none;
}
.corner-tl { top: 28px; left: 28px; }
.corner-tr { top: 28px; right: 28px; }
.corner-bl { bottom: 80px; left: 28px; }
.corner-br { bottom: 80px; right: 28px; }
.mono-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #2A2A2A;
}
@media (max-width: 600px) { .corner { display: none; } }

/* Brand bar */
.brand-bar {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 56px;
    border-bottom: 1px solid #1E1E1E;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}
.brand-bar-text {
    font-family: 'Oswald', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #F2EDE3;
}
.brand-bar-red { color: #C8192A; }

/* ── Login card ── */
.login-card {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 440px;
    background: #101010;
    border: 1px solid #1E1E1E;
    border-left: 3px solid #C8192A;
    padding: 48px 48px 40px;
    box-shadow: 0 30px 80px rgba(0,0,0,.55), 0 0 0 1px rgba(255,255,255,.02);
}
@media (max-width: 520px) { .login-card { padding: 32px 24px; } }

.card-accent-corner {
    position: absolute;
    top: -1px; right: -1px;
    width: 48px; height: 48px;
    background: linear-gradient(135deg, transparent 50%, #C8192A 50%, #C8192A 53%, transparent 53%);
    pointer-events: none;
}

/* Logo row */
.logo-row {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    margin-bottom: 32px;
}
.zone-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #888888;
    margin-bottom: 6px;
}
.card-h1 {
    font-family: 'Oswald', sans-serif;
    font-size: 1.8rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    line-height: 1.05;
    color: #F2EDE3;
    margin: 0;
}
.h1-red { color: #C8192A; }

/* Fields */
.fields-col { display: flex; flex-direction: column; gap: 18px; }
.field-group { display: flex; flex-direction: column; gap: 6px; }

.form-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.28em;
    text-transform: uppercase;
    color: #888888;
}
.form-input {
    background: #080808;
    border: 1px solid #1E1E1E;
    border-bottom: 2px solid #1E1E1E;
    color: #F2EDE3;
    padding: 0.75rem 0.875rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.78rem;
    outline: none;
    transition: border-color 0.18s;
    width: 100%;
}
.form-input:focus { border-color: #C8192A; }
.form-input::placeholder { color: #2A2A2A; }

/* Error */
.err-row {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(200,25,42,0.08);
    border-left: 2px solid #C8192A;
    padding: 10px 14px;
}
.err-prefix {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.1em;
    color: #C8192A;
}
.err-msg {
    font-family: 'Space Mono', monospace;
    font-size: 0.7rem;
    color: #F2EDE3;
}

/* Submit button */
.btn-submit {
    background: #C8192A;
    color: #F2EDE3;
    font-family: 'Space Mono', monospace;
    font-size: 0.65rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    padding: 1rem 1.5rem;
    border: none;
    cursor: pointer;
    transition: background 0.18s;
    clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);
    margin-top: 6px;
    width: 100%;
}
.btn-submit:hover { background: #E01E2F; }
.btn-submit:disabled { opacity: 0.55; cursor: not-allowed; }

/* Card footer */
.card-foot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 28px;
    padding-top: 16px;
    border-top: 1px solid #1E1E1E;
}
.foot-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.5rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #2A2A2A;
}
.foot-back {
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #2A2A2A;
    text-decoration: none;
    transition: color 0.15s;
}
.foot-back:hover { color: #888888; }

/* Marquee strip */
.marquee-strip {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 36px;
    overflow: hidden;
    border-top: 1px solid #1E1E1E;
    display: flex;
    align-items: center;
    background: rgba(0,0,0,.5);
}
.marquee-track {
    display: flex;
    white-space: nowrap;
    animation: marquee 40s linear infinite;
}
.marquee-item {
    font-family: 'Oswald', sans-serif;
    font-size: 0.72rem;
    letter-spacing: 0.12em;
    color: #F2EDE3;
    padding-right: 40px;
    font-weight: 500;
}
.marquee-star { color: #C8192A; }

@keyframes marquee {
    to { transform: translateX(-50%); }
}

/* Flash overlay */
.flash-overlay {
    position: fixed;
    inset: 0;
    pointer-events: none;
    background: #C8192A;
    opacity: 0;
    transition: opacity 0.35s ease;
    z-index: 9998;
}
.flash-active { opacity: 0.8; }
</style>
