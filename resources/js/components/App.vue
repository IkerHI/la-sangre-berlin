<template>
    <div class="layout-shell">
        <AppHeader v-if="!isAdminRoute" />
        <AnnouncementBar v-if="!isAdminRoute" />
        <main class="layout-main">
            <RouterView />
        </main>
        <AppFooter v-if="!isAdminRoute" />
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import AppHeader from './layout/AppHeader.vue';
import AppFooter from './layout/AppFooter.vue';
import AnnouncementBar from './layout/AnnouncementBar.vue';

const route = useRoute();
const isAdminRoute = computed(() => route.path.startsWith('/admin'));
</script>

<style scoped>
.layout-shell {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background-color: #0D0D0D;
    color: #EDE8D8;
}
.layout-main {
    flex: 1;
}
</style>
