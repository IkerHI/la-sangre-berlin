<template>
    <div class="dashboard">
        <!-- Page kicker + title -->
        <div class="page-head">
            <div class="page-kicker">// PANEL DE CONTROL</div>
            <h1 class="page-title">{{ t('admin.dashboard') }}</h1>
        </div>

        <div v-if="loading" class="loading-state">{{ t('common.loading') }}</div>
        <template v-else>

            <!-- Stat cards row -->
            <section class="stats-row">
                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-kicker">{{ t('admin.total_visits') }}</span>
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" class="stat-icon">
                            <rect x="1" y="9" width="3" height="6" stroke="currentColor" stroke-width="1.4"/>
                            <rect x="6.5" y="5" width="3" height="10" stroke="currentColor" stroke-width="1.4"/>
                            <rect x="12" y="2" width="3" height="13" stroke="currentColor" stroke-width="1.4"/>
                        </svg>
                    </div>
                    <div class="stat-value-row">
                        <span class="stat-value">{{ stats.total_visits?.toLocaleString() ?? '—' }}</span>
                    </div>
                    <svg class="stat-spark" viewBox="0 0 200 40" preserveAspectRatio="none">
                        <polyline fill="none" stroke="rgba(242,237,227,0.3)" stroke-width="1.2"
                            points="0,30 18,28 36,22 54,25 72,18 90,20 108,12 126,16 144,8 162,11 180,6 200,9"/>
                    </svg>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <span class="stat-kicker">{{ t('admin.upcoming_concerts') }}</span>
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" class="stat-icon">
                            <rect x="1.5" y="3" width="13" height="11.5" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M1.5 6.5h13" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M4.5 1.5v3M11.5 1.5v3" stroke="currentColor" stroke-width="1.4"/>
                        </svg>
                    </div>
                    <div class="stat-value-row">
                        <span class="stat-value">{{ stats.upcoming_concerts ?? '—' }}</span>
                    </div>
                    <svg class="stat-spark" viewBox="0 0 200 40" preserveAspectRatio="none">
                        <polyline fill="none" stroke="rgba(242,237,227,0.3)" stroke-width="1.2"
                            points="0,35 30,35 60,30 90,30 120,20 150,20 180,15 200,15"/>
                    </svg>
                </div>

                <div class="stat-card" :class="{ 'stat-card--alert': stats.unread_messages > 0 }">
                    <div class="stat-head">
                        <span class="stat-kicker">{{ t('admin.unread_messages') }}</span>
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" class="stat-icon" :class="{ 'stat-icon--red': stats.unread_messages > 0 }">
                            <rect x="1.5" y="3" width="13" height="10" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M1.5 3.5L8 9l6.5-5.5" stroke="currentColor" stroke-width="1.4"/>
                        </svg>
                    </div>
                    <div class="stat-value-row">
                        <span class="stat-value" :class="{ 'stat-value--red': stats.unread_messages > 0 }">{{ stats.unread_messages ?? '—' }}</span>
                    </div>
                    <svg class="stat-spark" viewBox="0 0 200 40" preserveAspectRatio="none">
                        <polyline fill="none"
                            :stroke="stats.unread_messages > 0 ? 'rgba(200,25,42,0.5)' : 'rgba(242,237,227,0.3)'"
                            stroke-width="1.2"
                            points="0,38 40,38 80,35 100,28 130,28 160,18 180,12 200,8"/>
                    </svg>
                </div>
            </section>

            <!-- Top concerts table -->
            <section class="panel" v-if="stats.top_concerts?.length">
                <div class="panel-head">
                    <div>
                        <div class="panel-kicker">// AGENDA</div>
                        <h2 class="panel-title">{{ t('admin.top_concerts') }}</h2>
                    </div>
                    <RouterLink to="/admin/concerts" class="btn-panel-action">Ver todos</RouterLink>
                </div>

                <div class="table-wrap">
                    <div class="table-th">
                        <span class="th-venue">VENUE</span>
                        <span class="th-city">CIUDAD</span>
                        <span class="th-views">VISITAS</span>
                    </div>
                    <div v-for="c in stats.top_concerts" :key="c.id" class="table-tr">
                        <span class="td-venue">{{ c.venue_name }}</span>
                        <span class="td-city">{{ c.city }}<span v-if="c.country">, {{ c.country }}</span></span>
                        <span class="td-views">{{ c.views }} <span class="views-unit">{{ t('common.views') }}</span></span>
                    </div>
                </div>
            </section>

            <!-- Quick links to sections -->
            <section class="quick-links">
                <RouterLink to="/admin/concerts"   class="quick-card">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><rect x="1.5" y="3" width="13" height="11.5" stroke="currentColor" stroke-width="1.4"/><path d="M1.5 6.5h13" stroke="currentColor" stroke-width="1.4"/><path d="M4.5 1.5v3M11.5 1.5v3" stroke="currentColor" stroke-width="1.4"/></svg>
                    <span>{{ t('admin.concerts') }}</span>
                </RouterLink>
                <RouterLink to="/admin/messages"   class="quick-card" :class="{ 'quick-card--alert': stats.unread_messages > 0 }">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><rect x="1.5" y="3" width="13" height="10" stroke="currentColor" stroke-width="1.4"/><path d="M1.5 3.5L8 9l6.5-5.5" stroke="currentColor" stroke-width="1.4"/></svg>
                    <span>{{ t('admin.messages') }}</span>
                    <span v-if="stats.unread_messages > 0" class="quick-badge">{{ stats.unread_messages }}</span>
                </RouterLink>
                <RouterLink to="/admin/albums"     class="quick-card">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/><circle cx="8" cy="8" r="1.6" fill="currentColor"/></svg>
                    <span>{{ t('admin.albums') }}</span>
                </RouterLink>
                <RouterLink to="/admin/gallery"    class="quick-card">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><rect x="1.5" y="2.5" width="13" height="11" stroke="currentColor" stroke-width="1.4"/><path d="M1.5 11L5 7l3 3 3-4 3.5 4" stroke="currentColor" stroke-width="1.4"/><circle cx="5" cy="6" r="1.2" fill="currentColor"/></svg>
                    <span>{{ t('admin.gallery') }}</span>
                </RouterLink>
                <RouterLink to="/admin/tech-specs" class="quick-card">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><path d="M2 13.5V2.5M5 13.5V5.5M8 13.5V1.5M11 13.5V4.5M14 13.5V7.5" stroke="currentColor" stroke-width="1.4"/></svg>
                    <span>{{ t('admin.tech_specs') }}</span>
                </RouterLink>
                <RouterLink to="/admin/settings"   class="quick-card">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="2.4" stroke="currentColor" stroke-width="1.4"/><path d="M8 1.5v2M8 12.5v2M1.5 8h2M12.5 8h2M3.4 3.4l1.4 1.4M11.2 11.2l1.4 1.4M3.4 12.6l1.4-1.4M11.2 4.8l1.4-1.4" stroke="currentColor" stroke-width="1.4"/></svg>
                    <span>{{ t('admin.settings') }}</span>
                </RouterLink>
            </section>

        </template>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();
const stats = ref({});
const loading = ref(true);

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/admin/stats');
        stats.value = data;
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
.dashboard { display: flex; flex-direction: column; gap: 2rem; }

/* Page header */
.page-head { margin-bottom: 0.5rem; }
.page-kicker {
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #888888;
    margin-bottom: 6px;
}
.page-title {
    font-family: 'Oswald', sans-serif;
    font-size: 1.6rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #F2EDE3;
    margin: 0;
    border-left: 3px solid #C8192A;
    padding-left: 0.75rem;
}

.loading-state {
    font-family: 'Space Mono', monospace;
    font-size: 0.68rem;
    color: #888888;
    letter-spacing: 0.1em;
    padding: 2rem 0;
}

/* Stat cards */
.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
@media (max-width: 900px) { .stats-row { grid-template-columns: 1fr 1fr; } }
@media (max-width: 600px) { .stats-row { grid-template-columns: 1fr; } }

.stat-card {
    position: relative;
    background: #141414;
    border: 1px solid #1E1E1E;
    padding: 20px 22px 28px;
    overflow: hidden;
    transition: border-color 0.18s;
}
.stat-card:hover { border-color: #2A2A2A; }
.stat-card--alert { border-color: rgba(200,25,42,0.3); }

.stat-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}
.stat-kicker {
    font-family: 'Space Mono', monospace;
    font-size: 0.5rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #888888;
}
.stat-icon { color: #2A2A2A; }
.stat-icon--red { color: #C8192A; }

.stat-value-row { display: flex; align-items: flex-end; gap: 6px; }
.stat-value {
    font-family: 'Oswald', sans-serif;
    font-size: 2.5rem;
    font-weight: 600;
    line-height: 1;
    letter-spacing: 0.02em;
    color: #F2EDE3;
}
.stat-value--red { color: #C8192A; }

.stat-spark {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 28px;
    width: 100%;
    opacity: 0.8;
}

/* Panel */
.panel {
    background: #141414;
    border: 1px solid #1E1E1E;
}
.panel-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    padding: 18px 22px 14px;
    border-bottom: 1px solid #1E1E1E;
}
.panel-kicker {
    font-family: 'Space Mono', monospace;
    font-size: 0.5rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #888888;
    margin-bottom: 4px;
}
.panel-title {
    font-family: 'Oswald', sans-serif;
    font-size: 1.1rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #F2EDE3;
    margin: 0;
}
.btn-panel-action {
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #888888;
    text-decoration: none;
    border: 1px solid #1E1E1E;
    padding: 6px 12px;
    transition: all 0.15s;
}
.btn-panel-action:hover { border-color: #C8192A; color: #C8192A; }

/* Table */
.table-wrap { padding: 4px 8px 12px; overflow-x: auto; }
.table-th {
    display: grid;
    grid-template-columns: 1fr 160px 100px;
    gap: 12px;
    padding: 10px 14px;
    font-family: 'Space Mono', monospace;
    font-size: 0.48rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #2A2A2A;
    border-bottom: 1px solid #1E1E1E;
}
.table-tr {
    display: grid;
    grid-template-columns: 1fr 160px 100px;
    gap: 12px;
    padding: 12px 14px;
    border-bottom: 1px solid #1E1E1E;
    align-items: center;
    transition: background 0.12s;
}
.table-tr:hover { background: rgba(255,255,255,0.015); }
.table-tr:last-child { border-bottom: none; }
.td-venue {
    font-family: 'Oswald', sans-serif;
    font-size: 0.85rem;
    color: #F2EDE3;
    font-weight: 500;
    letter-spacing: 0.04em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.td-city {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    color: #888888;
}
.td-views {
    font-family: 'Space Mono', monospace;
    font-size: 0.62rem;
    color: #C8192A;
    text-align: right;
}
.views-unit { color: #2A2A2A; }

@media (max-width: 480px) {
    .table-th, .table-tr { grid-template-columns: 1fr 80px; gap: 8px; }
    .th-city, .td-city { display: none; }
}

/* Quick links */
.quick-links {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: #1E1E1E;
}
@media (max-width: 700px) { .quick-links { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 440px) { .quick-links { grid-template-columns: 1fr; } }

.quick-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 20px;
    background: #141414;
    color: #888888;
    text-decoration: none;
    font-family: 'Oswald', sans-serif;
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    transition: background 0.15s, color 0.15s;
    position: relative;
}
.quick-card:hover { background: #1A1A1A; color: #F2EDE3; }
.quick-card--alert { color: #C8192A; }
.quick-badge {
    margin-left: auto;
    background: #C8192A;
    color: #F2EDE3;
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    font-weight: 700;
    padding: 2px 6px;
    letter-spacing: 0.05em;
}
</style>
