<template>
    <div class="min-h-screen flex flex-col" style="background-color: #0D0D0D; color: #EDE8D8;">
        <AppHeader v-if="!isAdminRoute" />
        <AnnouncementBar v-if="!isAdminRoute" />
        <main class="flex-1">
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
