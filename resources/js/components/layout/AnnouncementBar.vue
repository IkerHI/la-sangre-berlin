<template>
    <div v-if="text" class="announcement-bar" aria-live="polite">
        <div class="marquee-wrapper">
            <span class="marquee-track">
                <span v-for="n in 4" :key="n" class="marquee-item">
                    {{ text }}&nbsp;&nbsp;&nbsp;✦&nbsp;&nbsp;&nbsp;
                </span>
            </span>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { locale } = useI18n();
const setting = ref(null);

const text = computed(() => {
    if (!setting.value) return '';
    const lang = locale.value;
    return setting.value[`value_${lang}`] || setting.value.value_es || '';
});

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/settings');
        setting.value = data['announcement'] ?? null;
    } catch { /* silent */ }
});
</script>

<style scoped>
.announcement-bar {
    background: #A51C30;
    height: 28px;
    overflow: hidden;
    position: relative;
}

.marquee-wrapper {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
}

.marquee-track {
    display: flex;
    white-space: nowrap;
    animation: marquee 28s linear infinite;
    will-change: transform;
}

.marquee-item {
    font-family: 'Oswald', sans-serif;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #EDE8D8;
    padding-right: 0.5rem;
}

@keyframes marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
</style>
