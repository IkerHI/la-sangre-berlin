<template>
    <div class="admin-shell">
        <!-- ── Sidebar ── -->
        <aside class="sidebar">
            <div class="sidebar-accent-bar"></div>

            <!-- Brand -->
            <div class="sidebar-brand">
                <svg width="26" height="26" viewBox="0 0 60 60" fill="none" aria-hidden="true" class="brand-mark">
                    <rect x="6" y="6" width="48" height="48" stroke="#C8192A" stroke-width="3.5"/>
                    <path d="M14 14 L46 46 M46 14 L14 46" stroke="#C8192A" stroke-width="3.5"/>
                    <rect x="28" y="50" width="4" height="9" fill="#C8192A"/>
                </svg>
                <div>
                    <div class="brand-name">LA <span class="brand-red">SANGRE</span></div>
                    <div class="brand-sub">BERLÍN · ADMIN</div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <RouterLink
                    v-for="item in navItems"
                    :key="item.to"
                    :to="item.to"
                    class="nav-item"
                    :class="{ 'nav-item--active': isActive(item.to) }"
                >
                    <span class="nav-icon" v-html="item.icon"></span>
                    <span class="nav-label">{{ item.label }}</span>
                    <span v-if="item.badge" class="nav-badge">{{ item.badge }}</span>
                </RouterLink>
            </nav>

            <!-- Bottom: user chip + logout -->
            <div class="sidebar-bottom">
                <div class="user-chip">
                    <div class="user-avatar">{{ userInitial }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth.user?.name || 'Admin' }}</div>
                        <div class="user-email">{{ auth.user?.email || '' }}</div>
                    </div>
                </div>
                <RouterLink to="/" class="sidebar-link-site">
                    <svg width="11" height="11" viewBox="0 0 12 12" fill="none"><path d="M5 2H2v8h8V7M7 1h4v4M10 2L5 7" stroke="currentColor" stroke-width="1.5"/></svg>
                    Sitio público
                </RouterLink>
                <button @click="handleLogout" class="btn-logout">{{ t('admin.logout') }}</button>
            </div>
        </aside>

        <!-- ── Main ── -->
        <main class="admin-main">
            <RouterView />
        </main>
    </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import { useAuthStore } from '../../stores/auth';
import axios from 'axios';

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const auth = useAuthStore();
const unreadCount = ref(0);

const userInitial = computed(() => {
    const name = auth.user?.name || auth.user?.email || 'A';
    return name.charAt(0).toUpperCase();
});

onMounted(async () => {
    try {
        const { data } = await axios.get('/api/admin/stats');
        unreadCount.value = data.unread_messages ?? 0;
    } catch { /* silent */ }
});

const ICONS = {
    dash: `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="1" y="1" width="6" height="6" stroke="currentColor" stroke-width="1.4"/><rect x="9" y="1" width="6" height="6" stroke="currentColor" stroke-width="1.4"/><rect x="1" y="9" width="6" height="6" stroke="currentColor" stroke-width="1.4"/><rect x="9" y="9" width="6" height="6" stroke="currentColor" stroke-width="1.4"/></svg>`,
    cal:  `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="1.5" y="3" width="13" height="11.5" stroke="currentColor" stroke-width="1.4"/><path d="M1.5 6.5h13" stroke="currentColor" stroke-width="1.4"/><path d="M4.5 1.5v3M11.5 1.5v3" stroke="currentColor" stroke-width="1.4"/></svg>`,
    disc: `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.4"/><circle cx="8" cy="8" r="1.6" fill="currentColor"/></svg>`,
    img:  `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="1.5" y="2.5" width="13" height="11" stroke="currentColor" stroke-width="1.4"/><path d="M1.5 11L5 7l3 3 3-4 3.5 4" stroke="currentColor" stroke-width="1.4" stroke-linejoin="miter"/><circle cx="5" cy="6" r="1.2" fill="currentColor"/></svg>`,
    spec: `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><path d="M2 13.5V2.5M5 13.5V5.5M8 13.5V1.5M11 13.5V4.5M14 13.5V7.5" stroke="currentColor" stroke-width="1.4"/></svg>`,
    env:  `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="1.5" y="3" width="13" height="10" stroke="currentColor" stroke-width="1.4"/><path d="M1.5 3.5L8 9l6.5-5.5" stroke="currentColor" stroke-width="1.4"/></svg>`,
    bar:  `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="1" y="9" width="3" height="6" stroke="currentColor" stroke-width="1.4"/><rect x="6.5" y="5" width="3" height="10" stroke="currentColor" stroke-width="1.4"/><rect x="12" y="2" width="3" height="13" stroke="currentColor" stroke-width="1.4"/></svg>`,
    gear: `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="2.4" stroke="currentColor" stroke-width="1.4"/><path d="M8 1.5v2M8 12.5v2M1.5 8h2M12.5 8h2M3.4 3.4l1.4 1.4M11.2 11.2l1.4 1.4M3.4 12.6l1.4-1.4M11.2 4.8l1.4-1.4" stroke="currentColor" stroke-width="1.4"/></svg>`,
    user: `<svg width="15" height="15" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="5.5" r="2.8" stroke="currentColor" stroke-width="1.4"/><path d="M2 14c0-3.314 2.686-5 6-5s6 1.686 6 5" stroke="currentColor" stroke-width="1.4"/></svg>`,
};

const navItems = computed(() => [
    { to: '/admin',            icon: ICONS.dash, label: t('admin.dashboard') },
    { to: '/admin/concerts',   icon: ICONS.cal,  label: t('admin.concerts') },
    { to: '/admin/albums',     icon: ICONS.disc, label: t('admin.albums') },
    { to: '/admin/gallery',    icon: ICONS.img,  label: t('admin.gallery') },
    { to: '/admin/tech-specs', icon: ICONS.spec, label: t('admin.tech_specs') },
    { to: '/admin/messages',   icon: ICONS.env,  label: t('admin.messages'), badge: unreadCount.value || null },
    { to: '/admin/stats',      icon: ICONS.bar,  label: t('admin.stats') },
    { to: '/admin/settings',   icon: ICONS.gear, label: t('admin.settings') },
    { to: '/admin/users',      icon: ICONS.user, label: 'Usuarios' },
]);

function isActive(path) {
    return path === '/admin' ? route.path === '/admin' : route.path.startsWith(path);
}

async function handleLogout() {
    await auth.logout();
    router.push('/admin/login');
}
</script>

<style scoped>
.admin-shell {
    display: flex;
    min-height: 100vh;
    background: #0D0D0D;
    font-family: 'Space Mono', monospace;
}

/* ── SIDEBAR ── */
.sidebar {
    width: 220px;
    flex-shrink: 0;
    background: #141414;
    border-right: 1px solid #1E1E1E;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.sidebar-accent-bar {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: #C8192A;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 28px 18px 20px;
    border-bottom: 1px solid #1E1E1E;
}
.brand-mark { flex-shrink: 0; }
.brand-name {
    font-family: 'Oswald', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    color: #F2EDE3;
    line-height: 1.1;
}
.brand-red { color: #C8192A; }
.brand-sub {
    font-family: 'Space Mono', monospace;
    font-size: 0.48rem;
    letter-spacing: 0.22em;
    color: #888888;
    text-transform: uppercase;
    margin-top: 2px;
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    overflow-y: auto;
}
.nav-item {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 12px;
    border: none;
    border-left: 3px solid transparent;
    background: transparent;
    color: #888888;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    width: 100%;
}
.nav-item:hover {
    background: rgba(255,255,255,0.03);
    color: #F2EDE3;
}
.nav-item--active {
    background: rgba(200,25,42,0.1);
    border-left-color: #C8192A;
    color: #F2EDE3;
}
.nav-icon {
    display: inline-flex;
    width: 16px;
    flex-shrink: 0;
    color: inherit;
}
.nav-item--active .nav-icon { color: #C8192A; }
.nav-label {
    font-family: 'Oswald', sans-serif;
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    flex: 1;
    color: inherit;
}
.nav-badge {
    background: #C8192A;
    color: #F2EDE3;
    font-family: 'Space Mono', monospace;
    font-size: 0.55rem;
    font-weight: 700;
    padding: 2px 6px;
    letter-spacing: 0.05em;
}

/* User chip + bottom */
.sidebar-bottom {
    padding: 12px;
    border-top: 1px solid #1E1E1E;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.user-chip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 4px;
}
.user-avatar {
    width: 30px;
    height: 30px;
    background: #C8192A;
    color: #F2EDE3;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Oswald', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    flex-shrink: 0;
}
.user-info { min-width: 0; }
.user-name {
    font-family: 'Oswald', sans-serif;
    font-size: 0.78rem;
    font-weight: 500;
    color: #F2EDE3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    letter-spacing: 0.05em;
}
.user-email {
    font-family: 'Space Mono', monospace;
    font-size: 0.48rem;
    color: #888888;
    letter-spacing: 0.04em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sidebar-link-site {
    display: flex;
    align-items: center;
    gap: 7px;
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #2A2A2A;
    text-decoration: none;
    padding: 6px 4px;
    transition: color 0.15s;
}
.sidebar-link-site:hover { color: #888888; }
.btn-logout {
    background: transparent;
    border: 1px solid #1E1E1E;
    color: #888888;
    font-family: 'Space Mono', monospace;
    font-size: 0.52rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    padding: 9px;
    cursor: pointer;
    transition: border-color 0.15s, color 0.15s;
    width: 100%;
    text-align: center;
}
.btn-logout:hover { border-color: #C8192A; color: #C8192A; }

/* ── MAIN ── */
.admin-main {
    flex: 1;
    min-width: 0;
    overflow-y: auto;
    padding: 2rem;
    background: #0D0D0D;
}
</style>
