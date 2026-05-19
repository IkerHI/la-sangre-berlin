import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';

export const useSettingsStore = defineStore('settings', () => {
    const settings = ref({});
    const loaded = ref(false);

    async function load() {
        if (loaded.value) return;
        try {
            const { data } = await axios.get('/api/settings');
            settings.value = data;
        } catch {
            settings.value = {};
        }
        loaded.value = true;
    }

    return { settings, load };
});
