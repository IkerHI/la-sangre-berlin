<template>
    <div>
        <!-- ── HERO ── -->
        <section class="hero">
            <div class="hero-accent-bar"></div>
            <div class="hero-ghost" aria-hidden="true">SANGRE</div>

            <div class="hero-content">
                <div class="hero-label">// {{ t('home.hero_subtitle') }}</div>

                <h1 class="hero-title">
                    <span class="glitch" :data-text="t('nav.home') ? 'LA SANGRE' : 'LA SANGRE'">LA SANGRE</span>
                    <span class="glitch glitch--red" data-text="BERLÍN">BERLÍN</span>
                </h1>

                <p class="hero-sub">
                    Berlin<span class="hero-dot">×</span>2019<span class="hero-dot">×</span>Acoustic Punk Rock
                </p>

                <div class="hero-ctas">
                    <a href="#concerts" class="btn-primary">{{ t('home.concerts_title') }}</a>
                    <RouterLink to="/discography" class="btn-outline">{{ t('nav.discography') }}</RouterLink>
                </div>
            </div>

            <div class="hero-scroll-hint" aria-hidden="true">
                <span>Scroll</span>
            </div>
        </section>

        <!-- ── MARQUEE ── -->
        <div class="marquee-wrapper" aria-hidden="true">
            <div class="marquee-track">
                <span v-for="i in 10" :key="i" class="marquee-item">
                    LA SANGRE BERLÍN <span class="marquee-star">★</span>
                    ROCK FROM THE GUTS OF BERLIN <span class="marquee-star">★</span>
                    THIS CITY WANTS BLOOD <span class="marquee-star">★</span>
                </span>
            </div>
        </div>

        <!-- ── ABOUT ── -->
        <section id="about" class="bb-section">
            <div class="bb-section-label">
                <span class="bb-num">01</span> {{ t('home.about_title') }}
            </div>
            <h2 class="bb-title bb-reveal" v-html="t('home.about_subtitle')"></h2>
            <div class="about-grid">
                <div class="about-photo bb-reveal">
                    <img src="/img/2025-La-Sangre-by-vgalleguillos-01.jpg" alt="La Sangre Berlín — Vitxo y Vini" class="about-photo-img">
                    <div class="about-photo-overlay"></div>
                </div>
                <div class="about-body">
                    <div class="about-text bb-reveal">
                    <p v-for="(para, i) in aboutParagraphs" :key="i" class="about-para">{{ para }}</p>
                </div>
                    <div class="about-stats bb-reveal">
                        <div class="stat-box">
                            <div class="stat-n">200+</div>
                            <div class="stat-l">{{ t('home.stat_concerts') }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-n">2</div>
                            <div class="stat-l">{{ t('home.stat_releases') }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-n">2019</div>
                            <div class="stat-l">{{ t('home.stat_founded') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── SONGS / SPOTIFY ── -->
        <section class="bb-section" style="padding-top:0">
            <div class="bb-section-label">
                <span class="bb-num">02</span> {{ t('home.songs_title') }}
            </div>
            <h2 class="bb-title bb-reveal">{{ t('home.songs_title') }}</h2>
            <div class="bb-reveal">
                <div v-if="spotifyLoading" class="bb-loading">{{ t('common.loading') }}</div>
                <div v-else-if="spotifyEmbedUrl" class="spotify-wrapper">
                    <iframe
                        :src="spotifyEmbedUrl"
                        width="100%"
                        height="380"
                        frameborder="0"
                        allowtransparency="true"
                        allow="encrypted-media"
                        loading="lazy"
                    ></iframe>
                </div>
                <p v-else class="bb-empty">— {{ t('discography.no_albums') }} —</p>
            </div>
        </section>

        <!-- ── TECH RIDER SUMMARY ── -->
        <section class="bb-section" style="padding-top:0">
            <div class="bb-section-label">
                <span class="bb-num">03</span> {{ t('tech_rider.title') }}
            </div>
            <h2 class="bb-title bb-reveal">{{ t('home.rider_title') }}</h2>

            <div class="rider-summary-grid bb-reveal">
                <div class="rider-lines">
                    <div class="rider-line"><span class="rider-qty">1×</span> {{ t('home.rider_guitar') }}</div>
                    <div class="rider-line"><span class="rider-qty">1×</span> {{ t('home.rider_bass') }}</div>
                    <div class="rider-line"><span class="rider-qty">2–3×</span> {{ t('home.rider_mics') }}</div>
                    <div class="rider-line"><span class="rider-qty">1×</span> {{ t('home.rider_kick') }} <span class="rider-note">{{ t('home.rider_phantom') }}</span></div>
                    <div class="rider-line"><span class="rider-qty">1–2×</span> {{ t('home.rider_stools') }}</div>
                    <div class="rider-line rider-guest"><span class="rider-plus">+</span> {{ t('home.rider_guests') }}</div>
                </div>
                <div class="rider-perf">
                    <div class="rider-perf-block">
                        <div class="rider-perf-label">{{ t('tech_rider.duration_title') }}</div>
                        <div class="rider-pills">
                            <span class="rider-pill">20–30 min</span>
                            <span class="rider-pill">45 min</span>
                            <span class="rider-pill">{{ t('tech_rider.duration_up_to') }} 1:15 h</span>
                        </div>
                    </div>
                    <div class="rider-perf-block">
                        <div class="rider-perf-label">{{ t('tech_rider.looking_title') }}</div>
                        <div class="rider-tags">
                            <span v-for="(tag, i) in tm('tech_rider.looking_tags').slice(0, 3)" :key="i" class="rider-tag">{{ tag }}</span>
                        </div>
                    </div>
                    <RouterLink to="/tech-rider" class="rider-link">{{ t('home.rider_link') }} →</RouterLink>
                </div>
            </div>
        </section>

        <!-- ── PHOTO CAROUSEL ── -->
        <!-- TODO: Photo carousel — user will provide images -->

        <!-- ── CONCERTS ── -->
        <section id="concerts" class="bb-section">
            <div class="bb-section-label">
                <span class="bb-num">04</span> {{ t('home.concerts_title') }}
            </div>
            <h2 class="bb-title bb-reveal">{{ t('home.concerts_title') }}</h2>

            <div v-if="concertsLoading" class="bb-loading">{{ t('common.loading') }}</div>
            <template v-else-if="upcomingConcerts.length">
                <div class="concert-header">
                    <span>{{ t('home.col_date') }}</span><span>{{ t('home.col_venue') }}</span><span>{{ t('home.col_city') }}</span><span>{{ t('home.col_tickets') }}</span>
                </div>
                <div
                    v-for="concert in upcomingConcerts"
                    :key="concert.id"
                    class="concert-row bb-reveal"
                >
                    <div class="concert-date">{{ formatDate(concert.date) }}</div>
                    <div class="concert-venue">{{ concert.venue_name }}</div>
                    <div class="concert-city">{{ concert.city }}, {{ concert.country }}</div>
                    <div class="concert-action">
                        <a v-if="concert.ticket_url" :href="concert.ticket_url" target="_blank" rel="noopener" class="btn-tickets">
                            {{ t('home.buy_tickets') }}
                        </a>
                        <span v-else class="concert-tba">TBA</span>
                    </div>
                </div>
            </template>
            <p v-else class="bb-empty">{{ t('home.no_concerts') }}</p>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';
import { useSettingsStore } from '../stores/settings';

const { t, tm, locale } = useI18n();
const settingsStore = useSettingsStore();
const upcomingConcerts = ref([]);
const concertsLoading = ref(true);
const spotifyEmbedUrl = ref(null);
const spotifyLoading = ref(true);

const DEFAULT_BIO = `Acoustic music with a punk rock vibe! La Sangre is an acoustic Latin punk rock duo that fuses the rich heritage of Latin rock music with punk's raw, rebellious energy. With an acoustic guitar and bass, their lyrics ignite with passion and darkness, speaking truth to power and fighting for social justice and freedom.

Originally from Chile, La Sangre began forming in 2023 in Friedrichshain-Kreuzberg, Berlin. The duo consists of Vitxo (guitar, vocals, and percussion) and Vini (bass and vocals).

The project revives the raw energy of classic punk and horror punk, blending it with the spirit of Latin punk rock. Alongside mashups, versions, and covers, La Sangre offers original songs that explore powerful themes — the struggles of migration (Hombre Lobo en Berlín), tormented relationships (Sangre y Huesos), and the suffering of abandoned animals (Abandonado), among others.`;

const aboutText = computed(() => {
    const s = settingsStore.settings['about_text'];
    return s ? (s[`value_${locale.value}`] || s.value_es || DEFAULT_BIO) : DEFAULT_BIO;
});

const aboutParagraphs = computed(() => aboutText.value.split('\n\n').filter(Boolean));

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' }).toUpperCase();
}

onMounted(async () => {
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('bb-visible'); });
    }, { threshold: 0.08 });
    // Re-run after each async fetch: rows rendered from API data aren't in the DOM at mount.
    const observeReveals = () => {
        document.querySelectorAll('.bb-reveal').forEach(el => obs.observe(el));
    };
    observeReveals();

    await settingsStore.load();
    await nextTick();
    observeReveals();

    try {
        const { data } = await axios.get('/api/concerts');
        upcomingConcerts.value = data.upcoming ?? [];
    } catch {
        upcomingConcerts.value = [];
    } finally {
        concertsLoading.value = false;
    }
    await nextTick();
    observeReveals();

    try {
        const { data } = await axios.get('/api/spotify/tracks');
        spotifyEmbedUrl.value = data.embed_url ?? null;
    } catch {
        spotifyEmbedUrl.value = null;
    } finally {
        spotifyLoading.value = false;
    }
    await nextTick();
    observeReveals();
});
</script>

<style scoped>
/* ── HERO ── */
.hero {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 7rem 2rem 5rem;
    max-width: 72rem;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
}

.hero-accent-bar {
    position: absolute;
    left: 2rem; top: 0; bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent 0%, #C8192A 25%, #C8192A 75%, transparent 100%);
}

.hero-ghost {
    position: absolute;
    bottom: -1rem; left: -0.5rem;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 22vw;
    color: rgba(200, 25, 42, 0.03);
    line-height: 1;
    white-space: nowrap;
    pointer-events: none;
    user-select: none;
    letter-spacing: -0.03em;
}

.hero-content { padding-left: 3rem; }

.hero-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    color: #C8192A;
    margin-bottom: 2rem;
}

.hero-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(4.5rem, 13vw, 15rem);
    line-height: 0.88;
    letter-spacing: -0.02em;
    display: flex;
    flex-direction: column;
}

/* glitch */
.glitch {
    position: relative;
    display: inline-block;
    animation: glitch-shake 9s infinite;
}
.glitch::before, .glitch::after {
    content: attr(data-text);
    position: absolute; top: 0; left: 0;
    width: 100%; height: 100%;
}
.glitch::before {
    color: #C8192A;
    animation: glitch-top 9s infinite;
    clip-path: polygon(0 0, 100% 0, 100% 40%, 0 40%);
}
.glitch::after {
    color: #00e5ff;
    animation: glitch-bot 9s infinite;
    clip-path: polygon(0 60%, 100% 60%, 100% 100%, 0 100%);
}
.glitch--red { color: #C8192A; }

@keyframes glitch-shake {
    0%,88%,100% { transform: none; }
    89% { transform: skewX(-0.6deg); }
    90% { transform: none; }
    91% { transform: skewX(0.4deg); }
    92% { transform: none; }
}
@keyframes glitch-top {
    0%,88%,100% { opacity:0; transform:none; }
    89% { opacity:1; transform:translateX(-4px) skewX(-1deg); }
    90.5% { opacity:1; transform:translateX(4px); }
    92% { opacity:0; }
}
@keyframes glitch-bot {
    0%,89.5%,100% { opacity:0; transform:none; }
    90% { opacity:1; transform:translateX(4px) skewX(1deg); }
    91.5% { opacity:1; transform:translateX(-2px); }
    93% { opacity:0; }
}

.hero-sub {
    margin-top: 2rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.7rem;
    letter-spacing: 0.35em;
    text-transform: uppercase;
    color: #888888;
}
.hero-dot { color: #C8192A; margin: 0 0.5rem; }

.hero-ctas {
    display: flex; gap: 1rem;
    margin-top: 3rem;
    flex-wrap: wrap;
}

.hero-scroll-hint {
    position: absolute;
    bottom: 2.5rem; right: 2rem;
    display: flex; flex-direction: column;
    align-items: center; gap: 0.75rem;
    color: #888888;
}
.hero-scroll-hint span {
    font-family: 'Space Mono', monospace;
    font-size: 0.5rem;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    writing-mode: vertical-lr;
}
.hero-scroll-hint::after {
    content: '';
    width: 1px; height: 45px;
    background: linear-gradient(to bottom, #888888, transparent);
}

/* ── BUTTONS ── */
.btn-primary {
    display: inline-block;
    background: #C8192A;
    color: #F2EDE3;
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    padding: 0.9rem 2.25rem;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s;
    clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);
}
.btn-primary:hover {
    background: #FF2233;
    transform: translate(-2px,-2px);
    box-shadow: 3px 3px 0 rgba(200,25,42,0.35);
}

.btn-outline {
    display: inline-block;
    background: transparent;
    color: #F2EDE3;
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    padding: 0.9rem 2.25rem;
    border: 1px solid #2A2A2A;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s;
}
.btn-outline:hover {
    border-color: #F2EDE3;
    transform: translate(-2px,-2px);
    box-shadow: 3px 3px 0 #888888;
}

/* ── MARQUEE ── */
.marquee-wrapper {
    overflow: hidden;
    border-top: 1px solid #1E1E1E;
    border-bottom: 1px solid #1E1E1E;
    background: #101010;
    padding: 0.85rem 0;
}
.marquee-track {
    display: flex;
    animation: marquee 25s linear infinite;
    width: max-content;
}
.marquee-track:hover { animation-play-state: paused; }
.marquee-item {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 0.95rem;
    letter-spacing: 0.3em;
    color: #F2EDE3;
    padding: 0 2rem;
    white-space: nowrap;
}
.marquee-star { color: #C8192A; margin: 0 0.75rem; }

@keyframes marquee {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}

.bb-section {
    max-width: 72rem;
    margin: 0 auto;
    padding: 6rem 2rem;
}

/* ── ABOUT ── */
.about-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 5rem;
    align-items: start;
}
@media (max-width: 768px) { .about-grid { grid-template-columns: 1fr; } }

.about-photo {
    width: 100%;
    aspect-ratio: 3/4;
    background: #101010;
    border: 1px solid #1E1E1E;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.about-photo::before {
    content: '';
    position: absolute; top: -8px; right: -8px;
    width: 50%; height: 2px;
    background: #C8192A;
}
.about-photo::after {
    content: '';
    position: absolute; bottom: -8px; left: -8px;
    width: 2px; height: 50%;
    background: #C8192A;
}
.about-photo-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
}
.about-photo-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(160deg, rgba(200,25,42,0.06) 0%, transparent 60%);
}

.about-text {
    margin-bottom: 2.5rem;
}
.about-para {
    font-family: 'Space Mono', monospace;
    font-size: 0.78rem;
    line-height: 1.9;
    color: #888888;
    margin-bottom: 1.25rem;
}
.about-para:last-child { margin-bottom: 0; }

.about-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: #1E1E1E;
}
.stat-box {
    background: #101010;
    padding: 1.25rem;
}
.stat-n {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 3rem;
    color: #C8192A;
    line-height: 1;
}
.stat-l {
    font-family: 'Space Mono', monospace;
    font-size: 0.55rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: #888888;
    margin-top: 0.25rem;
}

/* ── SPOTIFY ── */
.spotify-wrapper {
    background: #101010;
    border: 1px solid #1E1E1E;
    overflow: hidden;
}

/* ── CONCERTS ── */
.concert-header {
    display: grid;
    grid-template-columns: 140px 1fr 1fr auto;
    padding: 0.5rem 0 1rem;
    border-bottom: 1px solid #1E1E1E;
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    color: #2A2A2A;
    gap: 1rem;
}

.concert-row {
    display: grid;
    grid-template-columns: 140px 1fr 1fr auto;
    align-items: center;
    gap: 1rem;
    padding: 1.4rem 0;
    border-bottom: 1px solid #1E1E1E;
    cursor: default;
    transition: padding-left 0.2s, opacity 0.65s ease, transform 0.65s ease;
}
.concert-row:hover { padding-left: 0.75rem; }
.concert-row:hover .concert-date { color: #C8192A; }

.concert-date {
    font-family: 'Space Mono', monospace;
    font-size: 0.68rem;
    letter-spacing: 0.08em;
    color: #888888;
    transition: color 0.2s;
    white-space: nowrap;
}

.concert-venue {
    font-family: 'Oswald', sans-serif;
    font-size: 0.95rem;
    font-weight: 500;
    color: #F2EDE3;
    letter-spacing: 0.04em;
}

.concert-city {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #888888;
}

.concert-action { text-align: right; }

.btn-tickets {
    display: inline-block;
    font-family: 'Space Mono', monospace;
    font-size: 0.55rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    padding: 0.5rem 1rem;
    border: 1px solid #1E1E1E;
    color: #888888;
    text-decoration: none;
    transition: all 0.18s;
    white-space: nowrap;
}
.btn-tickets:hover { border-color: #C8192A; color: #C8192A; }

.concert-tba {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.18em;
    color: #2A2A2A;
}

@media (max-width: 640px) {
    .hero-content { padding-left: 1.5rem; }
    .concert-header { display: none; }
    .concert-row { grid-template-columns: auto 1fr; }
    .concert-city, .concert-action { display: none; }
}

/* ── RIDER SUMMARY ── */
.rider-summary-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: start;
}
@media (max-width: 768px) { .rider-summary-grid { grid-template-columns: 1fr; gap: 2rem; } }

.rider-lines {
    display: flex;
    flex-direction: column;
    border: 1px solid #1E1E1E;
}
.rider-line {
    font-family: 'Space Mono', monospace;
    font-size: 0.7rem;
    color: #F2EDE3;
    letter-spacing: 0.04em;
    padding: 0.7rem 1rem;
    border-bottom: 1px solid #1E1E1E;
}
.rider-line:last-child { border-bottom: none; }
.rider-qty {
    color: #C8192A;
    margin-right: 0.5rem;
}
.rider-note {
    font-size: 0.6rem;
    color: #888888;
}
.rider-guest {
    background: rgba(200, 25, 42, 0.04);
    color: #888888;
}
.rider-plus { color: #C8192A; margin-right: 0.5rem; }

.rider-perf { display: flex; flex-direction: column; gap: 1.75rem; }
.rider-perf-block {}
.rider-perf-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.35em;
    text-transform: uppercase;
    color: #888888;
    margin-bottom: 0.65rem;
}
.rider-pills { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.rider-pill {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.08em;
    color: #C8192A;
    border: 1px solid #C8192A;
    padding: 0.3rem 0.8rem;
}
.rider-tags { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.rider-tag {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.08em;
    color: #888888;
    border: 1px solid #1E1E1E;
    padding: 0.3rem 0.7rem;
}
.rider-link {
    display: inline-block;
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #C8192A;
    text-decoration: none;
    transition: letter-spacing 0.2s;
}
.rider-link:hover { letter-spacing: 0.35em; }
</style>
