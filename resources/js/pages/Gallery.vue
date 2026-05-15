<template>
    <div class="bb-page">
        <!-- Header row -->
        <div class="page-header">
            <div>
                <div class="bb-section-label">
                    <span class="bb-num">03</span> {{ t('nav.gallery') }}
                </div>
                <h1 class="page-title">{{ t('gallery.title') }}</h1>
            </div>
            <div class="filter-group">
                <button
                    v-for="f in filters"
                    :key="f.value"
                    @click="activeFilter = f.value"
                    class="filter-btn"
                    :class="{ 'filter-btn--active': activeFilter === f.value }"
                >{{ f.label }}</button>
            </div>
        </div>

        <div v-if="loading" class="bb-loading">{{ t('common.loading') }}</div>
        <p v-else-if="!filtered.length" class="bb-empty">— {{ t('gallery.no_images') }} —</p>

        <div v-else class="gallery-grid">
            <button
                v-for="(img, idx) in filtered"
                :key="img.id"
                class="gallery-item"
                @click="openLightbox(idx)"
                :aria-label="img[`alt_${locale}`] || img.alt_es || t('gallery.title')"
            >
                <img
                    :src="img.url"
                    :alt="img[`alt_${locale}`] || img.alt_es || ''"
                    class="gallery-img"
                    loading="lazy"
                >
                <div class="gallery-overlay">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                        <path d="M11 8v6M8 11h6"/>
                    </svg>
                </div>
            </button>
        </div>

        <!-- Lightbox -->
        <Transition name="lb">
            <div
                v-if="lightboxIdx !== null"
                class="lightbox"
                @click.self="closeLightbox"
                role="dialog"
                aria-modal="true"
            >
                <div class="lb-top-bar">
                    <span class="lb-counter">{{ lightboxIdx + 1 }} / {{ filtered.length }}</span>
                    <button class="lb-close" @click="closeLightbox" :aria-label="t('common.close')">✕</button>
                </div>
                <button class="lb-prev" @click="prevImage" v-if="filtered.length > 1" :aria-label="t('common.prev')">‹</button>
                <button class="lb-next" @click="nextImage" v-if="filtered.length > 1" :aria-label="t('common.next')">›</button>
                <img
                    :src="filtered[lightboxIdx]?.url"
                    :alt="filtered[lightboxIdx]?.alt_es || ''"
                    class="lb-img"
                >
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t, locale } = useI18n();
const images = ref([]);
const loading = ref(true);
const activeFilter = ref('all');
const lightboxIdx = ref(null);

const filters = computed(() => [
    { value: 'all',    label: t('gallery.filter_all') },
    { value: 'epk',    label: t('gallery.filter_epk') },
    { value: 'normal', label: t('gallery.filter_normal') },
]);

const filtered = computed(() =>
    activeFilter.value === 'all' ? images.value : images.value.filter(i => i.type === activeFilter.value)
);

function openLightbox(idx) {
    lightboxIdx.value = idx;
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    lightboxIdx.value = null;
    document.body.style.overflow = '';
}
function prevImage() {
    if (lightboxIdx.value === null) return;
    lightboxIdx.value = (lightboxIdx.value - 1 + filtered.value.length) % filtered.value.length;
}
function nextImage() {
    if (lightboxIdx.value === null) return;
    lightboxIdx.value = (lightboxIdx.value + 1) % filtered.value.length;
}
function handleKey(e) {
    if (lightboxIdx.value === null) return;
    if (e.key === 'Escape')     closeLightbox();
    if (e.key === 'ArrowLeft')  prevImage();
    if (e.key === 'ArrowRight') nextImage();
}

onMounted(async () => {
    window.addEventListener('keydown', handleKey);
    try {
        const { data } = await axios.get('/api/gallery');
        images.value = data;
    } catch {
        images.value = [];
    } finally {
        loading.value = false;
    }
});
onUnmounted(() => {
    window.removeEventListener('keydown', handleKey);
    document.body.style.overflow = '';
});
</script>

<style scoped>
.bb-page {
    max-width: 72rem;
    margin: 0 auto;
    padding: 4rem 2rem 6rem;
}

.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1.5rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    gap: 1px;
    background: #1E1E1E;
    flex-shrink: 0;
}
.filter-btn {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    padding: 0.55rem 1.1rem;
    background: #080808;
    border: none;
    color: #888888;
    cursor: pointer;
    transition: all 0.18s;
}
.filter-btn:hover { color: #F2EDE3; background: #101010; }
.filter-btn--active {
    background: #C8192A;
    color: #F2EDE3;
}

/* Gallery grid */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2px;
    background: #1E1E1E;
}
@media (min-width: 640px)  { .gallery-grid { grid-template-columns: repeat(3, 1fr); } }
@media (min-width: 1024px) { .gallery-grid { grid-template-columns: repeat(4, 1fr); } }

.gallery-item {
    aspect-ratio: 1;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    background: #101010;
    border: none;
    padding: 0;
    display: block;
}
.gallery-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.45s;
    display: block;
}
.gallery-item:hover .gallery-img { transform: scale(1.06); }

.gallery-overlay {
    position: absolute; inset: 0;
    background: rgba(200, 25, 42, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F2EDE3;
    opacity: 0;
    transition: opacity 0.2s;
}
.gallery-item:hover .gallery-overlay { opacity: 1; }

/* Lightbox */
.lightbox {
    position: fixed; inset: 0;
    background: rgba(0, 0, 0, 0.97);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 200;
    padding: 5rem 3rem 3rem;
}

.lb-top-bar {
    position: absolute;
    top: 0; left: 0; right: 0;
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #1E1E1E;
}
.lb-counter {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.2em;
    color: #888888;
}
.lb-close {
    font-family: 'Space Mono', monospace;
    font-size: 0.9rem;
    color: #888888;
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.2s;
    padding: 0.25rem 0.5rem;
    letter-spacing: 0.1em;
}
.lb-close:hover { color: #C8192A; }

.lb-img {
    max-width: 90vw;
    max-height: 82vh;
    object-fit: contain;
    display: block;
}

.lb-prev, .lb-next {
    position: absolute;
    top: 50%; transform: translateY(-50%);
    font-family: 'Bebas Neue', sans-serif;
    font-size: 3rem;
    color: rgba(242, 237, 227, 0.25);
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.2s;
    padding: 0.5rem 1rem;
    line-height: 1;
}
.lb-prev { left: 0.5rem; }
.lb-next { right: 0.5rem; }
.lb-prev:hover, .lb-next:hover { color: #F2EDE3; }

/* Transitions */
.lb-enter-active, .lb-leave-active { transition: opacity 0.2s; }
.lb-enter-from, .lb-leave-to { opacity: 0; }
</style>
