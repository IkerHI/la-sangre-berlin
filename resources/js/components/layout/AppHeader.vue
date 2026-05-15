<template>
    <header class="site-header">
        <!-- red accent bar -->
        <div class="header-accent"></div>

        <div class="header-inner">
            <!-- Logo -->
            <RouterLink to="/" class="header-logo">
                LA <span>SANGRE</span> BERLÍN
            </RouterLink>

            <!-- Desktop nav -->
            <nav class="header-nav">
                <RouterLink
                    v-for="item in navItems"
                    :key="item.to"
                    :to="item.to"
                    class="nav-link"
                    :class="{ 'nav-link--active': isActive(item.to) }"
                >
                    {{ item.label }}
                </RouterLink>
            </nav>

            <div class="header-right">
                <!-- Language switcher -->
                <div class="lang-switcher" @click.stop>
                    <button
                        v-for="lang in ['es', 'de', 'en']"
                        :key="lang"
                        @click="setLocale(lang)"
                        class="lang-btn"
                        :class="{ 'lang-btn--active': locale === lang }"
                    >{{ lang }}</button>
                </div>

                <!-- Mobile hamburger -->
                <button @click="menuOpen = !menuOpen" class="hamburger" :aria-label="menuOpen ? t('common.close') : t('common.menu')">
                    <span class="hamburger-line" :class="{ open: menuOpen }"></span>
                    <span class="hamburger-line" :class="{ open: menuOpen }"></span>
                    <span class="hamburger-line" :class="{ open: menuOpen }"></span>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <Transition name="mobile-nav">
            <div v-if="menuOpen" class="mobile-menu">
                <RouterLink
                    v-for="item in navItems"
                    :key="item.to"
                    :to="item.to"
                    class="mobile-nav-link"
                    :class="{ 'mobile-nav-link--active': isActive(item.to) }"
                    @click="menuOpen = false"
                >
                    <span class="mobile-nav-num">// {{ String(navItems.indexOf(item) + 1).padStart(2, '0') }}</span>
                    {{ item.label }}
                </RouterLink>
            </div>
        </Transition>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute } from 'vue-router';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();
const route = useRoute();
const menuOpen = ref(false);

const navItems = computed(() => [
    { to: '/',            label: t('nav.home') },
    { to: '/discography', label: t('nav.discography') },
    { to: '/gallery',     label: t('nav.gallery') },
    { to: '/tech-rider',  label: t('nav.tech_rider') },
    { to: '/contact',     label: t('nav.contact') },
]);

function isActive(path) {
    return path === '/' ? route.path === '/' : route.path.startsWith(path);
}

function setLocale(lang) {
    locale.value = lang;
    localStorage.setItem('locale', lang);
}
</script>

<style scoped>
.site-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: rgba(8, 8, 8, 0.94);
    border-bottom: 1px solid #1E1E1E;
    backdrop-filter: blur(12px);
}

.header-accent {
    height: 2px;
    background: linear-gradient(to right, #C8192A 0%, #C8192A 30%, transparent 100%);
}

.header-inner {
    max-width: 72rem;
    margin: 0 auto;
    padding: 0 2rem;
    height: 3.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

.header-logo {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.2rem;
    letter-spacing: 0.2em;
    color: #F2EDE3;
    text-decoration: none;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.header-logo span { color: #C8192A; }

.header-nav {
    display: none;
    align-items: center;
    gap: 2.5rem;
}
@media (min-width: 768px) {
    .header-nav { display: flex; }
}

.nav-link {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: #888888;
    text-decoration: none;
    transition: color 0.15s;
    position: relative;
}
.nav-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 1px;
    background: #C8192A;
    transition: width 0.2s;
}
.nav-link:hover { color: #F2EDE3; }
.nav-link:hover::after,
.nav-link--active::after { width: 100%; }
.nav-link--active { color: #C8192A; }

.header-right {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.lang-switcher {
    display: flex;
    gap: 0.1rem;
}
.lang-btn {
    font-family: 'Space Mono', monospace;
    font-size: 0.55rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    background: none;
    border: none;
    color: #888888;
    cursor: pointer;
    padding: 0.3rem 0.5rem;
    transition: color 0.15s;
}
.lang-btn:hover { color: #F2EDE3; }
.lang-btn--active { color: #C8192A; }

/* Hamburger */
.hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.25rem;
}
@media (max-width: 767px) {
    .hamburger { display: flex; }
}
.hamburger-line {
    width: 22px;
    height: 1px;
    background: #F2EDE3;
    transition: all 0.2s;
    display: block;
}
.hamburger-line.open:nth-child(1) { transform: translateY(6px) rotate(45deg); }
.hamburger-line.open:nth-child(2) { opacity: 0; }
.hamburger-line.open:nth-child(3) { transform: translateY(-6px) rotate(-45deg); }

/* Mobile menu */
.mobile-menu {
    border-top: 1px solid #1E1E1E;
    background: #080808;
    padding: 0;
    display: flex;
    flex-direction: column;
}
.mobile-nav-link {
    font-family: 'Space Mono', monospace;
    font-size: 0.7rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: #888888;
    text-decoration: none;
    padding: 1rem 2rem;
    border-bottom: 1px solid #1E1E1E;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.15s;
}
.mobile-nav-link:hover,
.mobile-nav-link--active { color: #F2EDE3; padding-left: 2.5rem; }
.mobile-nav-link--active { color: #C8192A; }
.mobile-nav-num {
    font-size: 0.55rem;
    color: #C8192A;
    opacity: 0.7;
}

/* Mobile nav transition */
.mobile-nav-enter-active,
.mobile-nav-leave-active { transition: opacity 0.2s, transform 0.2s; }
.mobile-nav-enter-from,
.mobile-nav-leave-to { opacity: 0; transform: translateY(-8px); }
</style>
