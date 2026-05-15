<template>
    <div class="bb-page">
        <!-- Page header -->
        <div class="page-header">
            <div class="bb-section-label">
                <span class="bb-num">02</span> {{ t('nav.discography') }}
            </div>
            <h1 class="page-title">{{ t('discography.title') }}</h1>
        </div>

        <div v-if="loading" class="bb-loading">{{ t('common.loading') }}</div>
        <p v-else-if="!albums.length" class="bb-empty">— {{ t('discography.no_albums') }} —</p>

        <div v-else class="albums-grid">
            <div
                v-for="album in albums"
                :key="album.id"
                class="album-card bb-reveal"
            >
                <!-- Cover art -->
                <div class="album-cover group">
                    <img
                        v-if="album.cover_url"
                        :src="album.cover_url"
                        :alt="album.title"
                        class="cover-img"
                        loading="lazy"
                    >
                    <div v-else class="cover-placeholder">
                        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#2A2A2A" stroke-width="1">
                            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span class="cover-placeholder-label">{{ album.title }}</span>
                    </div>

                    <!-- Hover overlay -->
                    <div class="cover-overlay">
                        <div class="cover-overlay-content">
                            <div class="cover-overlay-title">{{ album.title }}</div>
                            <div class="cover-overlay-year">{{ album.release_year }}</div>
                            <button
                                v-if="album.spotify_embed_url"
                                class="cover-listen-btn"
                                @click.prevent="activeAlbum = activeAlbum?.id === album.id ? null : album"
                            >{{ t('discography.listen') }}</button>
                        </div>
                    </div>
                </div>

                <!-- Info bar -->
                <div class="album-bar">
                    <div class="album-bar-left">
                        <div class="album-title">{{ album.title }}</div>
                        <div class="album-year">{{ album.release_year }}</div>
                    </div>
                    <button
                        v-if="album.spotify_embed_url"
                        @click="activeAlbum = activeAlbum?.id === album.id ? null : album"
                        class="album-play-btn"
                        :class="{ 'album-play-btn--active': activeAlbum?.id === album.id }"
                    >{{ activeAlbum?.id === album.id ? '▼' : '▶' }}</button>
                </div>

                <!-- Description -->
                <div v-if="albumDesc(album)" class="album-desc">{{ albumDesc(album) }}</div>

                <!-- Spotify embed -->
                <div v-if="activeAlbum?.id === album.id && album.spotify_embed_url" class="album-embed">
                    <iframe
                        :src="album.spotify_embed_url"
                        width="100%"
                        height="152"
                        frameborder="0"
                        allow="encrypted-media"
                        loading="lazy"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t, locale } = useI18n();
const albums = ref([]);
const loading = ref(true);
const activeAlbum = ref(null);

function albumDesc(album) {
    return album[`description_${locale.value}`] || album.description_es || '';
}

onMounted(async () => {
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('bb-visible'); });
    }, { threshold: 0.08 });
    setTimeout(() => {
        document.querySelectorAll('.bb-reveal').forEach(el => obs.observe(el));
    }, 100);

    try {
        const { data } = await axios.get('/api/albums');
        albums.value = data;
    } catch {
        albums.value = [];
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
.bb-page {
    max-width: 72rem;
    margin: 0 auto;
    padding: 4rem 2rem 6rem;
}

/* Albums grid */
.albums-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: #1E1E1E;
}
@media (max-width: 900px) { .albums-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 520px)  { .albums-grid { grid-template-columns: 1fr; } }

.album-card {
    background: #080808;
    position: relative;
}

/* Cover */
.album-cover {
    aspect-ratio: 1;
    width: 100%;
    overflow: hidden;
    position: relative;
    background: #101010;
    cursor: pointer;
}
.cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
    display: block;
}
.album-cover:hover .cover-img { transform: scale(1.05); }

.cover-placeholder {
    width: 100%; height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, #111 0%, #1a0808 100%);
}
.cover-placeholder-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.55rem;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: #2A2A2A;
}

.cover-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(200,25,42,0.88) 0%, rgba(8,8,8,0.35) 100%);
    display: flex;
    align-items: flex-end;
    padding: 1.25rem;
    opacity: 0;
    transition: opacity 0.3s;
}
.album-cover:hover .cover-overlay { opacity: 1; }

.cover-overlay-content {
    transform: translateY(8px);
    transition: transform 0.3s;
}
.album-cover:hover .cover-overlay-content { transform: translateY(0); }

.cover-overlay-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.25rem;
    letter-spacing: 0.05em;
    color: #F2EDE3;
}
.cover-overlay-year {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    color: rgba(242,237,227,0.6);
    margin-top: 0.15rem;
    letter-spacing: 0.1em;
}
.cover-listen-btn {
    display: inline-block;
    margin-top: 0.75rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #F2EDE3;
    border: 1px solid rgba(242,237,227,0.5);
    padding: 0.35rem 0.85rem;
    background: none;
    cursor: pointer;
    transition: background 0.2s;
}
.cover-listen-btn:hover { background: rgba(242,237,227,0.12); }

/* Info bar */
.album-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.875rem 1rem;
    background: #101010;
    border-top: 2px solid #1E1E1E;
}
.album-title {
    font-family: 'Oswald', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #F2EDE3;
}
.album-year {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    color: #888888;
    margin-top: 0.15rem;
    letter-spacing: 0.1em;
}
.album-play-btn {
    background: #C8192A;
    color: #F2EDE3;
    border: none;
    width: 2rem; height: 2rem;
    cursor: pointer;
    font-size: 0.65rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background 0.18s;
    clip-path: polygon(0 0, calc(100% - 4px) 0, 100% 4px, 100% 100%, 0 100%);
}
.album-play-btn:hover,
.album-play-btn--active { background: #9A0E1A; }

.album-desc {
    padding: 0.75rem 1rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.72rem;
    color: #888888;
    line-height: 1.75;
    background: #080808;
    border-top: 1px solid #1E1E1E;
}

.album-embed {
    background: #101010;
    border-top: 1px solid #1E1E1E;
}

/* Reveal */
.bb-reveal {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.bb-reveal.bb-visible { opacity: 1; transform: translateY(0); }
</style>
