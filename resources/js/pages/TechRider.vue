<template>
    <div class="bb-page">

        <!-- Page header -->
        <div class="page-header">
            <div class="bb-section-label">
                <span class="bb-num">04</span> {{ t('tech_rider.page_label') }}
            </div>
            <h1 class="page-title">{{ t('tech_rider.title') }}</h1>
            <p class="page-sub">{{ t('tech_rider.subtitle') }}</p>
        </div>

        <!-- Download PDF -->
        <div class="pdf-bar bb-reveal">
            <div class="pdf-bar-text">
                <span class="pdf-label">{{ t('tech_rider.pdf_label') }}</span>
                <span class="pdf-desc">{{ t('tech_rider.pdf_desc') }}</span>
            </div>
            <div class="pdf-actions">
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
                    {{ t('tech_rider.pdf_download') }}
                    <span v-if="pdf.language !== 'all'" class="pdf-lang">({{ pdf.language }})</span>
                </a>
                <!-- fallback if no PDFs loaded yet -->
                <span v-if="!pdfs.length && !specsLoading" class="pdf-na">{{ t('tech_rider.pdf_na') }}</span>
            </div>
        </div>

        <!-- ── BACKLINE ── -->
        <section class="rider-section bb-reveal">
            <div class="rider-section-head">
                <div class="rider-num">01</div>
                <div>
                    <div class="rider-label">{{ t('tech_rider.section_backline_label') }}</div>
                    <h2 class="rider-title">{{ t('tech_rider.section_backline_title') }}</h2>
                </div>
            </div>

            <div class="rider-grid">
                <div class="rider-card">
                    <div class="rider-card-icon">♩</div>
                    <div class="rider-card-name">{{ t('tech_rider.card_guitar') }}</div>
                    <div class="rider-card-detail">1 × Electro-acoustic guitar</div>
                </div>
                <div class="rider-card">
                    <div class="rider-card-icon">♩</div>
                    <div class="rider-card-name">{{ t('tech_rider.card_bass') }}</div>
                    <div class="rider-card-detail">1 × Electro-acoustic / electric bass</div>
                </div>
                <div class="rider-card">
                    <div class="rider-card-icon">🎙</div>
                    <div class="rider-card-name">{{ t('tech_rider.card_vocals') }}</div>
                    <div class="rider-card-detail">2–3 × Microphones for vocals</div>
                </div>
                <div class="rider-card">
                    <div class="rider-card-icon">🥁</div>
                    <div class="rider-card-name">{{ t('tech_rider.card_percussion') }}</div>
                    <div class="rider-card-detail">1 × Kick drum — Suitcase design<br><span class="rider-note">+48V Phantom power required</span></div>
                </div>
                <div class="rider-card">
                    <div class="rider-card-icon">🪑</div>
                    <div class="rider-card-name">{{ t('tech_rider.card_seats') }}</div>
                    <div class="rider-card-detail">1–2 × High stools &amp; 1 drum throne / seat</div>
                </div>
                <div class="rider-card rider-card--optional">
                    <div class="rider-card-badge">{{ t('tech_rider.optional') }}</div>
                    <div class="rider-card-name">{{ t('tech_rider.card_guests') }}</div>
                    <div class="rider-card-detail">+ 1 electric guitar / ukelele<br>and / or piano</div>
                </div>
            </div>
        </section>

        <!-- ── LINES ── -->
        <section class="rider-section bb-reveal">
            <div class="rider-section-head">
                <div class="rider-num">02</div>
                <div>
                    <div class="rider-label">{{ t('tech_rider.section_lines_label') }}</div>
                    <h2 class="rider-title">{{ t('tech_rider.section_lines_title') }}</h2>
                </div>
            </div>

            <!-- Configuration tabs -->
            <div class="config-tabs">
                <button
                    v-for="(cfg, i) in configs"
                    :key="i"
                    class="config-tab"
                    :class="{ 'config-tab--active': activeConfig === i }"
                    @click="activeConfig = i"
                >
                    <span class="tab-label">{{ cfg.label }}</span>
                    <span class="tab-desc">{{ cfg.desc }}</span>
                </button>
            </div>

            <div class="lines-table-wrap">
                <div class="lines-table">
                    <div class="lines-header">
                        <span>{{ t('tech_rider.col_ch') }}</span>
                        <span>{{ t('tech_rider.col_instrument') }}</span>
                        <span>{{ t('tech_rider.col_mic') }}</span>
                        <span>{{ t('tech_rider.col_effects') }}</span>
                        <span>{{ t('tech_rider.col_artist') }}</span>
                        <span>{{ t('tech_rider.col_notes') }}</span>
                    </div>
                    <div
                        v-for="line in configs[activeConfig].channels"
                        :key="line.ch"
                        class="lines-row"
                        :class="{
                            'lines-row--optional': line.notes === 'Optional',
                            'lines-row--guest': line.artist !== 'Vitxo' && line.artist !== 'Vini'
                        }"
                    >
                        <span class="lines-ch">{{ String(line.ch).padStart(2,'0') }}</span>
                        <span class="lines-inst">{{ line.instrument }}</span>
                        <span class="lines-conn">{{ line.mic }}</span>
                        <span class="lines-fx">{{ line.effects || '—' }}</span>
                        <span class="lines-artist" :class="{ 'lines-artist--guest': line.artist !== 'Vitxo' && line.artist !== 'Vini' }">{{ line.artist }}</span>
                        <span class="lines-note">{{ line.notes }}</span>
                    </div>
                </div>
            </div>

            <!-- Technical notes -->
            <div class="tech-notes">
                <div class="tech-notes-label">{{ t('tech_rider.tech_notes_label') }}</div>
                <ul class="tech-notes-list">
                    <li v-for="(note, i) in configs[activeConfig].technicalNotes" :key="i" class="tech-note">{{ note }}</li>
                </ul>
            </div>

            <!-- From API if available -->
            <div v-if="!specsLoading && Object.keys(specs).length" class="api-specs">
                <div v-for="(items, category) in specs" :key="category" class="specs-group">
                    <div class="specs-cat">// {{ category }}</div>
                    <div class="specs-rows">
                        <div v-for="spec in items" :key="spec.id" class="spec-row">
                            <span class="spec-key">{{ spec.key }}</span>
                            <span class="spec-val">{{ spec[`value_${locale}`] || spec.value_es }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── PERFORMANCE ── -->
        <section class="rider-section bb-reveal">
            <div class="rider-section-head">
                <div class="rider-num">03</div>
                <div>
                    <div class="rider-label">{{ t('tech_rider.section_perf_label') }}</div>
                    <h2 class="rider-title">{{ t('tech_rider.section_perf_title') }}</h2>
                </div>
            </div>

            <div class="perf-grid">
                <div class="perf-block">
                    <div class="perf-label">{{ t('tech_rider.duration_title') }}</div>
                    <div class="perf-durations">
                        <div class="duration-pill">20–30 min</div>
                        <div class="duration-pill">45 min</div>
                        <div class="duration-pill">{{ t('tech_rider.duration_up_to') }} 1:15 h</div>
                    </div>
                </div>
                <div class="perf-block">
                    <div class="perf-label">{{ t('tech_rider.looking_title') }}</div>
                    <div class="perf-looking">
                        <span v-for="tag in lookingFor" :key="tag" class="looking-tag">{{ tag }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── CONTACT ── -->
        <section class="contact-cta bb-reveal">
            <div class="cta-left">
                <div class="cta-label">{{ t('tech_rider.booking_label') }}</div>
                <div class="cta-title">{{ t('tech_rider.booking_title') }}</div>
            </div>
            <div class="cta-right">
                <a href="mailto:lasangreberlin@gmail.com" class="btn-primary">
                    lasangreberlin@gmail.com
                </a>
                <RouterLink to="/contact" class="btn-outline">{{ t('tech_rider.contact_form') }}</RouterLink>
            </div>
        </section>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t, tm, locale } = useI18n();
const specs = ref({});
const pdfs = ref([]);
const specsLoading = ref(true);

const activeConfig = ref(0);

const configs = [
    {
        label: 'Duo',
        desc: 'Vitxo + Vini',
        channels: [
            { ch: 1,  instrument: 'Vocals',               mic: 't.bone GM 55 / Shure SM58',          effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vitxo',  notes: '' },
            { ch: 2,  instrument: 'e-Acoustic guitar',    mic: 'Shure SM81 / AKG C451 — DI Line',    effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vitxo',  notes: '' },
            { ch: 3,  instrument: 'Kick drum (suitcase)', mic: 'Behringer BA 19a (XLR)',              effects: '',                           artist: 'Vitxo',  notes: '+48v Phantom power' },
            { ch: 4,  instrument: 'Vocals',               mic: 't.bone GM 55 / Shure SM58',          effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vini',   notes: '' },
            { ch: 5,  instrument: 'e-Acoustic bass',      mic: 'Shure SM81 / AKG C451 — DI Line',    effects: '',                           artist: 'Vini',   notes: '' },
            { ch: 6,  instrument: 'Electric bass',        mic: 'DI Line — Pedal',                    effects: '',                           artist: 'Vini',   notes: 'Optional' },
        ],
        technicalNotes: [
            't.bone GM 55 mics (#1 & #4); Behringer BA 19a mic (#3), drum throne/seat & pedal, all instruments — band-supplied.',
            '2 mic stands + 1 high stool (bass player) necessary.',
            '2 guitar/bass TRS cables (6.35mm) + 3 XLR cables necessary.',
            '8-channel (USB-battery) mixer can be band-provided if needed.',
        ],
    },
    {
        label: '+Pieter',
        desc: 'Vitxo + Vini + Pieter',
        channels: [
            { ch: 1,  instrument: 'Vocals',               mic: 't.bone GM 55 / Shure SM58',          effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vitxo',  notes: '' },
            { ch: 2,  instrument: 'e-Acoustic guitar',    mic: 'Shure SM81 / AKG C451 — DI Line',    effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vitxo',  notes: '' },
            { ch: 3,  instrument: 'Kick drum (suitcase)', mic: 'Behringer BA 19a (XLR)',              effects: '',                           artist: 'Vitxo',  notes: '+48v Phantom power' },
            { ch: 4,  instrument: 'Vocals',               mic: 't.bone GM 55 / Shure SM58',          effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vini',   notes: '' },
            { ch: 5,  instrument: 'e-Acoustic bass',      mic: 'Shure SM81 / AKG C451 — DI Line',    effects: '',                           artist: 'Vini',   notes: '' },
            { ch: 6,  instrument: 'Electric bass',        mic: 'Pedal — DI Line',                    effects: '',                           artist: 'Vini',   notes: 'Optional' },
            { ch: 7,  instrument: 'Vocals',               mic: 'Shure SM58',                         effects: '',                           artist: 'Pieter', notes: '' },
            { ch: 8,  instrument: 'Acoustic ukelele',     mic: 'Shure SM81 / AKG C451',              effects: '',                           artist: 'Pieter', notes: '' },
            { ch: 9,  instrument: 'Electric guitar',      mic: 'Pedal — DI Line',                    effects: '',                           artist: 'Pieter', notes: '' },
        ],
        technicalNotes: [
            't.bone GM 55 mics (#1 & #4); Behringer BA 19a mic (#3), drum throne/seat & pedal, piano stand, all instruments — band-supplied.',
            '3 mic stands + 1 high stool (bass player) necessary.',
            '3 guitar/bass TRS cables (6.35mm) + 3 XLR cables necessary.',
            '8-channel (USB-battery) mixer can be band-provided if needed.',
        ],
    },
    {
        label: 'Full',
        desc: 'Vitxo + Vini + Pieter + Rosita',
        channels: [
            { ch: 1,  instrument: 'Vocals',               mic: 't.bone GM 55 / Shure SM58',          effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vitxo',  notes: '' },
            { ch: 2,  instrument: 'e-Acoustic guitar',    mic: 'Shure SM81 / AKG C451 — DI Line',    effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vitxo',  notes: '' },
            { ch: 3,  instrument: 'Kick drum (suitcase)', mic: 'Behringer BA 19a (XLR)',              effects: '',                           artist: 'Vitxo',  notes: '+48v Phantom power' },
            { ch: 4,  instrument: 'Vocals',               mic: 't.bone GM 55 / Shure SM58',          effects: 'Reverb 10% · Echo/delay 5%', artist: 'Vini',   notes: '' },
            { ch: 5,  instrument: 'e-Acoustic bass',      mic: 'Shure SM81 / AKG C451 — DI Line',    effects: '',                           artist: 'Vini',   notes: '' },
            { ch: 6,  instrument: 'Electric bass',        mic: 'Pedal — DI Line',                    effects: '',                           artist: 'Vini',   notes: 'Optional' },
            { ch: 7,  instrument: 'Vocals',               mic: 'Shure SM58',                         effects: '',                           artist: 'Pieter', notes: '' },
            { ch: 8,  instrument: 'Acoustic ukelele',     mic: 'Shure SM81 / AKG C451',              effects: '',                           artist: 'Pieter', notes: '' },
            { ch: 9,  instrument: 'Electric guitar',      mic: 'Pedal — DI Line',                    effects: '',                           artist: 'Pieter', notes: '' },
            { ch: 10, instrument: 'Electric piano',       mic: 'DI Line',                            effects: '',                           artist: 'Rosita', notes: '' },
        ],
        technicalNotes: [
            't.bone GM 55 mics (#1 & #4); Behringer BA 19a mic (#3), drum throne/seat & pedal, piano stand, all instruments — band-supplied.',
            '3 mic stands + 1 high stool (bass player) + 1 piano stool necessary.',
            '4 guitar/bass/piano TRS cables (6.35mm) + 3 XLR cables necessary.',
            '8-channel (USB-battery) mixer can be band-provided if needed.',
        ],
    },
];

const lookingFor = computed(() => tm('tech_rider.looking_tags'));

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
</script>

<style scoped>
.bb-page {
    max-width: 72rem;
    margin: 0 auto;
    padding: 4rem 2rem 6rem;
}

/* ── PAGE HEADER ── */
.page-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(3.5rem, 8vw, 9rem);
    line-height: 0.88;
    letter-spacing: -0.01em;
}
.page-sub {
    font-family: 'Space Mono', monospace;
    font-size: 0.65rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #888888;
    margin-top: 0.75rem;
}

/* ── PDF BAR ── */
.pdf-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 1.5rem 2rem;
    background: #101010;
    border: 1px solid #1E1E1E;
    border-left: 2px solid #C8192A;
    margin-bottom: 4rem;
    flex-wrap: wrap;
}
.pdf-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.35em;
    text-transform: uppercase;
    color: #C8192A;
    display: block;
    margin-bottom: 0.25rem;
}
.pdf-desc {
    font-family: 'Space Mono', monospace;
    font-size: 0.68rem;
    color: #888888;
    letter-spacing: 0.05em;
}
.pdf-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; flex-shrink: 0; }
.pdf-lang { opacity: 0.6; }
.pdf-na { font-family: 'Space Mono', monospace; font-size: 0.62rem; color: #2A2A2A; letter-spacing: 0.1em; }

/* ── RIDER SECTIONS ── */
.rider-section {
    border-top: 1px solid #1E1E1E;
    padding: 4rem 0;
}

.rider-section-head {
    display: flex;
    align-items: flex-start;
    gap: 2rem;
    margin-bottom: 2.5rem;
}
.rider-num {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 5rem;
    line-height: 1;
    color: rgba(200,25,42,0.12);
    flex-shrink: 0;
    user-select: none;
}
.rider-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    color: #C8192A;
    margin-bottom: 0.3rem;
}
.rider-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(2rem, 4vw, 3.5rem);
    line-height: 0.9;
    letter-spacing: -0.01em;
}

/* Rider cards grid */
.rider-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: #1E1E1E;
}
@media (max-width: 768px) { .rider-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .rider-grid { grid-template-columns: 1fr; } }

.rider-card {
    background: #101010;
    padding: 1.75rem;
    position: relative;
    transition: background 0.18s;
}
.rider-card:hover { background: #141414; }

.rider-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 0; height: 2px;
    background: #C8192A;
    transition: width 0.3s;
}
.rider-card:hover::after { width: 100%; }

.rider-card--optional { background: #080808; }
.rider-card--optional .rider-card-name { color: #888888; }

.rider-card-badge {
    font-family: 'Space Mono', monospace;
    font-size: 0.5rem;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: #888888;
    border: 1px solid #2A2A2A;
    padding: 0.2rem 0.5rem;
    display: inline-block;
    margin-bottom: 0.75rem;
}
.rider-card-icon {
    font-size: 1.2rem;
    margin-bottom: 0.75rem;
    opacity: 0.4;
}
.rider-card-name {
    font-family: 'Oswald', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #F2EDE3;
    margin-bottom: 0.35rem;
}
.rider-card-detail {
    font-family: 'Space Mono', monospace;
    font-size: 0.68rem;
    color: #888888;
    line-height: 1.7;
    letter-spacing: 0.03em;
}
.rider-note {
    color: #C8192A;
    font-size: 0.6rem;
}

/* Configuration tabs */
.config-tabs {
    display: flex;
    gap: 1px;
    background: #1E1E1E;
    margin-bottom: 2rem;
}
.config-tab {
    flex: 1;
    background: #101010;
    border: none;
    padding: 1rem 1.25rem;
    cursor: pointer;
    text-align: left;
    transition: background 0.18s;
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}
.config-tab:hover { background: #141414; }
.config-tab--active { background: #141414; border-bottom: 2px solid #C8192A; }
.tab-label {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.1rem;
    letter-spacing: 0.08em;
    color: #F2EDE3;
}
.config-tab--active .tab-label { color: #C8192A; }
.tab-desc {
    font-family: 'Space Mono', monospace;
    font-size: 0.55rem;
    letter-spacing: 0.15em;
    color: #888888;
    text-transform: uppercase;
}

/* Lines table */
.lines-table-wrap { overflow-x: auto; }
.lines-table { width: 100%; min-width: 680px; }

.lines-header,
.lines-row {
    display: grid;
    grid-template-columns: 48px 1fr 1.4fr 1.2fr 80px 1fr;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #1E1E1E;
    align-items: center;
}
.lines-header {
    font-family: 'Space Mono', monospace;
    font-size: 0.5rem;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    color: #2A2A2A;
    border-bottom: 1px solid #2A2A2A;
    padding-bottom: 0.75rem;
    margin-bottom: 0.25rem;
}
.lines-row:hover { background: rgba(255,255,255,0.01); }
.lines-row:hover .lines-ch { color: #C8192A; }
.lines-row--optional { opacity: 0.55; }
.lines-row--guest { border-left: 2px solid rgba(200,25,42,0.25); padding-left: 0.5rem; }

.lines-ch {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1rem;
    color: #888888;
    transition: color 0.18s;
}
.lines-inst {
    font-family: 'Oswald', sans-serif;
    font-size: 0.85rem;
    font-weight: 400;
    color: #F2EDE3;
    letter-spacing: 0.04em;
}
.lines-conn {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    color: #888888;
    letter-spacing: 0.03em;
}
.lines-fx {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    color: #2A2A2A;
    letter-spacing: 0.03em;
}
.lines-artist {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    letter-spacing: 0.1em;
    color: #888888;
    text-transform: uppercase;
}
.lines-artist--guest { color: #C8192A; }
.lines-note {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    color: #888888;
    letter-spacing: 0.03em;
}

/* Technical notes */
.tech-notes {
    margin-top: 2rem;
    padding: 1.5rem;
    background: #101010;
    border: 1px solid #1E1E1E;
    border-left: 2px solid rgba(200,25,42,0.3);
}
.tech-notes-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    color: #C8192A;
    margin-bottom: 0.75rem;
}
.tech-notes-list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.tech-note {
    font-family: 'Space Mono', monospace;
    font-size: 0.65rem;
    color: #888888;
    letter-spacing: 0.03em;
    line-height: 1.7;
    padding-left: 1rem;
    position: relative;
}
.tech-note::before {
    content: '»';
    position: absolute;
    left: 0;
    color: rgba(200,25,42,0.5);
}

/* API specs (if available) */
.api-specs { margin-top: 3rem; }
.specs-group { margin-bottom: 2rem; }
.specs-cat {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.35em;
    text-transform: uppercase;
    color: #C8192A;
    margin-bottom: 0.75rem;
}
.specs-rows {}
.spec-row {
    display: grid;
    grid-template-columns: 35% 1fr;
    gap: 1rem;
    padding: 0.55rem 0;
    border-bottom: 1px solid #1E1E1E;
}
.spec-key {
    font-family: 'Space Mono', monospace;
    font-size: 0.65rem;
    color: #888888;
    letter-spacing: 0.05em;
}
.spec-val {
    font-family: 'Space Mono', monospace;
    font-size: 0.72rem;
    color: #F2EDE3;
}

/* ── PERFORMANCE ── */
.perf-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
}
@media (max-width: 640px) { .perf-grid { grid-template-columns: 1fr; } }

.perf-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.35em;
    text-transform: uppercase;
    color: #C8192A;
    margin-bottom: 1.25rem;
}

.perf-durations { display: flex; flex-direction: column; gap: 0.5rem; }
.duration-pill {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.75rem;
    letter-spacing: 0.05em;
    color: #F2EDE3;
    line-height: 1;
    border-left: 2px solid #C8192A;
    padding-left: 0.75rem;
}

.perf-looking { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.looking-tag {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    border: 1px solid #1E1E1E;
    padding: 0.45rem 1rem;
    color: #888888;
    transition: all 0.18s;
}
.looking-tag:hover { border-color: #C8192A; color: #C8192A; }

/* ── CONTACT CTA ── */
.contact-cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    border-top: 1px solid #1E1E1E;
    padding: 4rem 0 0;
    flex-wrap: wrap;
}
.cta-label {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    letter-spacing: 0.4em;
    text-transform: uppercase;
    color: #C8192A;
    margin-bottom: 0.5rem;
}
.cta-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(2rem, 4vw, 3.5rem);
    line-height: 0.9;
}
.cta-right { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }

.btn-primary {
    display: inline-block;
    background: #C8192A;
    color: #F2EDE3;
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    padding: 0.9rem 2rem;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s;
    clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 0 100%);
}
.btn-primary:hover { background: #FF2233; transform: translate(-2px,-2px); box-shadow: 3px 3px 0 rgba(200,25,42,0.35); }

.btn-outline {
    display: inline-block;
    background: transparent;
    color: #F2EDE3;
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    padding: 0.9rem 2rem;
    border: 1px solid #2A2A2A;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s;
}
.btn-outline:hover { border-color: #F2EDE3; }
</style>
