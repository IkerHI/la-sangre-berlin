<template>
    <div>
        <h1 class="admin-title mb-6">{{ t('admin.stats') }}</h1>

        <div v-if="loading" class="text-muted">{{ t('common.loading') }}</div>
        <div v-else>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                <div class="stat-card">
                    <div class="stat-value">{{ stats.total_visits?.toLocaleString() }}</div>
                    <div class="stat-label">{{ t('admin.total_visits') }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ stats.upcoming_concerts }}</div>
                    <div class="stat-label">{{ t('admin.upcoming_concerts') }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ stats.total_concerts }}</div>
                    <div class="stat-label">Total {{ t('admin.concerts') }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" :class="{ 'text-sangre': stats.unread_messages > 0 }">{{ stats.unread_messages }}</div>
                    <div class="stat-label">{{ t('admin.unread_messages') }}</div>
                </div>
            </div>

            <h2 class="section-sub mb-4">{{ t('admin.top_concerts') }}</h2>
            <div class="space-y-2" v-if="stats.top_concerts?.length">
                <div v-for="(c, i) in stats.top_concerts" :key="c.id" class="flex items-center gap-4 p-3" style="background:#1A1A1A;">
                    <span class="rank">#{{ i + 1 }}</span>
                    <div class="flex-1">
                        <span class="concert-name">{{ c.venue_name }}</span>
                        <span class="concert-meta ml-2">{{ c.city }} · {{ formatDate(c.date) }}</span>
                    </div>
                    <div class="views">{{ c.views }} {{ t('common.views') }}</div>
                </div>
            </div>
            <p v-else class="text-muted">Sin datos de visualizaciones aún.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();
const stats = ref({});
const loading = ref(true);

function formatDate(d) {
    return new Date(d).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
}

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
.admin-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.75rem; letter-spacing:0.1em; text-transform:uppercase; border-left:4px solid #A51C30; padding-left:1rem; }
.section-sub { font-family:'Oswald',sans-serif; font-weight:700; font-size:0.9rem; text-transform:uppercase; letter-spacing:0.1em; color:#B5AFA0; }
.stat-card { background:#1A1A1A; padding:1.25rem; border-left:3px solid #A51C30; }
.stat-value { font-family:'Oswald',sans-serif; font-size:2rem; font-weight:700; color:#EDE8D8; }
.text-sangre { color:#A51C30 !important; }
.stat-label { font-size:0.75rem; font-family:'Oswald',sans-serif; text-transform:uppercase; letter-spacing:0.05em; color:#B5AFA0; margin-top:0.25rem; }
.rank { font-family:'JetBrains Mono',monospace; font-size:0.8rem; color:#A51C30; width:2rem; }
.concert-name { font-family:'Oswald',sans-serif; font-size:0.9rem; color:#EDE8D8; }
.concert-meta { font-size:0.8rem; color:#B5AFA0; }
.views { font-family:'JetBrains Mono',monospace; font-size:0.8rem; color:#A51C30; }
.text-muted { color:#B5AFA0; }
</style>
