<template>
    <footer class="site-footer">
        <div class="footer-body">
            <div class="max-w-6xl mx-auto px-8">

                <!-- Main footer grid -->
                <div class="footer-grid">
                    <!-- Brand -->
                    <div class="footer-brand">
                        <div class="footer-brand-name">
                            LA <span>SANGRE</span><br>BERLÍN
                        </div>
                        <div class="footer-tagline">// {{ t('home.hero_subtitle') }}</div>
                    </div>

                    <!-- Nav -->
                    <nav class="footer-nav" aria-label="Footer navigation">
                        <RouterLink
                            v-for="item in navItems"
                            :key="item.to"
                            :to="item.to"
                            class="footer-nav-link"
                        >{{ item.label }}</RouterLink>
                    </nav>

                    <!-- Social -->
                    <div class="footer-social">
                        <a href="https://instagram.com/lasangreberlin" target="_blank" rel="noopener" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/>
                            </svg>
                            Instagram
                        </a>
                        <a href="https://open.spotify.com/artist/1glzEl3omzvBy2h1UCEaJg" target="_blank" rel="noopener" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <circle cx="12" cy="12" r="10"/><path d="M8 15.5c2.5-1 5.5-1 8 0M7 12c3-1.5 7-1.5 10 0M8 8.5c2.5-1 5.5-1 8 0"/>
                            </svg>
                            Spotify
                        </a>
                        <a href="https://youtube.com/@la.sangre" target="_blank" rel="noopener" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.54C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
                                <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>
                            </svg>
                            YouTube
                        </a>
                        <a href="https://la-sangre.bandcamp.com" target="_blank" rel="noopener" class="social-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M0 18.75l7.437-13.5H24l-7.438 13.5z"/>
                            </svg>
                            Bandcamp
                        </a>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div class="footer-bottom">
                    <span>© {{ new Date().getFullYear() }} La Sangre Berlín · {{ t('footer.location') }}</span>
                    <span class="footer-legal">
                        <RouterLink to="/legal/impressum" class="footer-legal-link">{{ t('footer.impressum') }}</RouterLink>
                        <span class="footer-sep">·</span>
                        <RouterLink to="/legal/datenschutz" class="footer-legal-link">{{ t('footer.datenschutz') }}</RouterLink>
                        <span class="footer-sep">·</span>
                        <RouterLink to="/legal/cookies" class="footer-legal-link">{{ t('footer.cookies') }}</RouterLink>
                    </span>
                    <span>{{ t('footer.rights') }}</span>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const navItems = computed(() => [
    { to: '/',             label: t('nav.home') },
    { to: '/discography',  label: t('nav.discography') },
    { to: '/gallery',      label: t('nav.gallery') },
    { to: '/tech-rider',   label: t('nav.tech_rider') },
    { to: '/contact',      label: t('nav.contact') },
]);
</script>

<style scoped>
.site-footer {
    margin-top: 6rem;
    border-top: 1px solid #1E1E1E;
}

.footer-body {
    padding: 4rem 0 2.5rem;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2.5rem;
    padding-bottom: 3rem;
    border-bottom: 1px solid #1E1E1E;
}
@media (min-width: 768px) {
    .footer-grid {
        grid-template-columns: 1fr auto 1fr;
        align-items: start;
    }
}

.footer-brand-name {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 2.75rem;
    letter-spacing: 0.05em;
    line-height: 0.9;
}
.footer-brand-name span { color: #C8192A; }

.footer-tagline {
    font-family: 'Space Mono', monospace;
    font-size: 0.58rem;
    color: #888888;
    letter-spacing: 0.15em;
    margin-top: 0.75rem;
}

.footer-nav {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}
@media (min-width: 768px) { .footer-nav { align-items: center; } }

.footer-nav-link {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: #888888;
    text-decoration: none;
    transition: color 0.15s;
}
.footer-nav-link:hover { color: #C8192A; }

.footer-social {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
@media (min-width: 768px) { .footer-social { align-items: flex-end; } }

.social-link {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #888888;
    text-decoration: none;
    transition: color 0.15s;
}
.social-link:hover { color: #F2EDE3; }

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding-top: 1.5rem;
    font-family: 'Space Mono', monospace;
    font-size: 0.55rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: #2A2A2A;
}
.footer-legal { display: flex; align-items: center; gap: 0.5rem; }
.footer-legal-link {
    color: #2A2A2A;
    text-decoration: none;
    transition: color 0.15s;
}
.footer-legal-link:hover { color: #C8192A; }
.footer-sep { opacity: 0.4; }
</style>
