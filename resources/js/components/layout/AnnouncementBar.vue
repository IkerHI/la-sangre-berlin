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
import { computed, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useSettingsStore } from '../../stores/settings';

const { locale } = useI18n();
const store = useSettingsStore();

const text = computed(() => {
    const s = store.settings['announcement'];
    if (!s) return '';
    const lang = locale.value;
    return s[`value_${lang}`] || s.value_es || '';
});

onMounted(() => store.load());
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
