<template>
    <div class="bb-page">

        <div class="page-header">
            <div class="bb-section-label">
                <span class="bb-num">00</span> LEGAL
            </div>
            <h1 class="page-title">{{ pageTitle }}</h1>
        </div>

        <!-- ═══ IMPRESSUM / AVISO LEGAL / LEGAL NOTICE ═══ -->
        <section v-if="type === 'impressum'" class="legal-section">

            <!-- DEUTSCH -->
            <template v-if="locale === 'de'">
                <div class="legal-block">
                    <h2 class="legal-h2">Angaben gemäß § 5 TMG</h2>
                    <p class="legal-text">Iker Díaz Herrán<br>Calle Virgen del Puy 11<br>31011 Pamplona (Navarra)<br>España</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Kontakt</h2>
                    <p class="legal-text">
                        Telefon: +34 666 904 174<br>
                        E-Mail: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a>
                    </p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
                    <p class="legal-text">Iker Díaz Herrán<br>Calle Virgen del Puy 11<br>31011 Pamplona (Navarra)<br>España</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Haftungsausschluss</h2>
                    <p class="legal-text">Die Inhalte dieser Seite wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte können wir keine Gewähr übernehmen. Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich.</p>
                </div>
            </template>

            <!-- ENGLISH -->
            <template v-else-if="locale === 'en'">
                <div class="legal-block">
                    <h2 class="legal-h2">General Information</h2>
                    <p class="legal-text">
                        In accordance with applicable law, the following details are provided for this website:<br><br>
                        Iker Díaz Herrán<br>
                        Calle Virgen del Puy 11, 31011 Pamplona (Navarra), Spain<br>
                        Phone: +34 666 904 174<br>
                        Email: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a><br>
                        Activity: Independent artist and musician
                    </p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Intellectual Property</h2>
                    <p class="legal-text">All content on this website — including text, images, graphic design, and source code — is protected by intellectual property law. Reproduction in whole or in part without express written authorisation is prohibited.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Disclaimer</h2>
                    <p class="legal-text">The website operator is not responsible for the content of external pages linked from this site. External links are provided for informational purposes only.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">German Impressum</h2>
                    <p class="legal-text">This website is operated by a person resident in Spain. In accordance with German Telemedia Act (TMG § 5), the full Impressum is available in the German version of this page (switch language to DE).</p>
                </div>
            </template>

            <!-- ESPAÑOL -->
            <template v-else>
                <div class="legal-block">
                    <h2 class="legal-h2">Información General</h2>
                    <p class="legal-text">
                        En cumplimiento del artículo 10 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y Comercio Electrónico (LSSI-CE), se pone a disposición del usuario la siguiente información:<br><br>
                        Nombre: Iker Díaz Herrán<br>
                        Domicilio: Calle Virgen del Puy 11, 31011 Pamplona (Navarra), España<br>
                        Teléfono: +34 666 904 174<br>
                        Correo electrónico: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a><br>
                        Actividad: Artista y músico independiente
                    </p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Propiedad Intelectual e Industrial</h2>
                    <p class="legal-text">Los contenidos de este sitio web — incluyendo textos, imágenes, diseño gráfico y código fuente — están protegidos por la normativa vigente en materia de propiedad intelectual e industrial. Queda prohibida su reproducción total o parcial sin autorización expresa y por escrito del titular.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Responsabilidad sobre Contenidos Externos</h2>
                    <p class="legal-text">El titular no se responsabiliza de los contenidos de páginas externas enlazadas desde este sitio. Los enlaces externos se facilitan únicamente a título informativo.</p>
                </div>
            </template>

        </section>

        <!-- ═══ DATENSCHUTZ / POLÍTICA DE PRIVACIDAD / PRIVACY POLICY ═══ -->
        <section v-else-if="type === 'datenschutz'" class="legal-section">

            <!-- DEUTSCH -->
            <template v-if="locale === 'de'">
                <div class="legal-block">
                    <h2 class="legal-h2">1. Datenschutz auf einen Blick</h2>
                    <p class="legal-text">Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website besuchen.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">2. Verantwortliche Stelle</h2>
                    <p class="legal-text">Iker Díaz Herrán<br>Calle Virgen del Puy 11<br>31011 Pamplona (Navarra)<br>España<br>E-Mail: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a></p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">3. Erhebung und Verarbeitung von Daten</h2>
                    <p class="legal-text">Diese Website erhebt keine personenbezogenen Daten, außer jenen, die Sie uns freiwillig über das Kontaktformular mitteilen. Die übermittelten Daten (Name, E-Mail, Nachricht) werden ausschließlich zur Beantwortung Ihrer Anfrage verwendet und nicht an Dritte weitergegeben.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">4. Rechtsgrundlage</h2>
                    <p class="legal-text">Die Verarbeitung erfolgt auf Grundlage Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO). Sie können Ihre Einwilligung jederzeit widerrufen.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">5. Externe Dienste</h2>
                    <ul class="legal-list">
                        <li><strong>Spotify Web API</strong> — zum Anzeigen von Musik-Informationen. Es werden keine persönlichen Daten an Spotify übermittelt.</li>
                        <li><strong>Bandsintown</strong> — zum Abrufen von Konzertdaten. Es werden keine persönlichen Daten an Bandsintown übermittelt.</li>
                    </ul>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">6. Cookies</h2>
                    <p class="legal-text">Diese Website verwendet nur technisch notwendige Cookies. Weitere Informationen finden Sie in unserer <RouterLink to="/legal/cookies" class="legal-link">Cookie-Richtlinie</RouterLink>.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">7. Ihre Rechte</h2>
                    <p class="legal-text">Sie haben das Recht auf Auskunft, Berichtigung, Löschung, Einschränkung der Verarbeitung sowie Datenübertragbarkeit. Kontakt: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a></p>
                </div>
            </template>

            <!-- ENGLISH -->
            <template v-else-if="locale === 'en'">
                <div class="legal-block">
                    <h2 class="legal-h2">1. Overview</h2>
                    <p class="legal-text">This policy explains how we handle personal data collected through this website, in accordance with the General Data Protection Regulation (GDPR).</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">2. Data Controller</h2>
                    <p class="legal-text">Iker Díaz Herrán<br>Calle Virgen del Puy 11, 31011 Pamplona (Navarra), Spain<br>Email: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a></p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">3. Data We Collect</h2>
                    <p class="legal-text">We only collect data you voluntarily submit via the contact form: your name, email address, and message. This data is used exclusively to respond to your enquiry and is not shared with third parties.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">4. Legal Basis</h2>
                    <p class="legal-text">Processing is based on your consent (Art. 6(1)(a) GDPR). You may withdraw your consent at any time.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">5. Third-Party Services</h2>
                    <ul class="legal-list">
                        <li><strong>Spotify Web API</strong> — to display music information. No personal data is transmitted to Spotify.</li>
                        <li><strong>Bandsintown</strong> — to display concert dates. No personal data is transmitted to Bandsintown.</li>
                    </ul>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">6. Cookies</h2>
                    <p class="legal-text">This website uses only technically necessary cookies. See our <RouterLink to="/legal/cookies" class="legal-link">Cookie Policy</RouterLink> for details.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">7. Your Rights</h2>
                    <p class="legal-text">You have the right to access, rectify, erase, restrict, object to, and port your personal data. Contact: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a></p>
                </div>
            </template>

            <!-- ESPAÑOL -->
            <template v-else>
                <div class="legal-block">
                    <h2 class="legal-h2">1. Información General</h2>
                    <p class="legal-text">Esta política explica cómo tratamos los datos personales recogidos a través de este sitio web, en cumplimiento del Reglamento (UE) 2016/679 (RGPD) y la Ley Orgánica 3/2018 de Protección de Datos Personales y garantía de los derechos digitales (LOPDGDD).</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">2. Responsable del Tratamiento</h2>
                    <p class="legal-text">Iker Díaz Herrán<br>Calle Virgen del Puy 11, 31011 Pamplona (Navarra), España<br>Correo electrónico: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a></p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">3. Datos que Recogemos</h2>
                    <p class="legal-text">Solo recogemos los datos que nos envías voluntariamente a través del formulario de contacto: nombre, correo electrónico y mensaje. Estos datos se utilizan exclusivamente para responder a tu consulta y no se ceden a terceros.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">4. Finalidad y Legitimación</h2>
                    <p class="legal-text">Los datos se tratan para responder a tu consulta o solicitud de booking. Base jurídica: consentimiento del interesado (art. 6.1.a RGPD). Puedes retirar tu consentimiento en cualquier momento.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">5. Conservación de los Datos</h2>
                    <p class="legal-text">Los datos se conservan únicamente durante el tiempo necesario para tramitar tu consulta, salvo obligación legal de conservación más prolongada.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">6. Servicios Externos</h2>
                    <ul class="legal-list">
                        <li><strong>Spotify Web API</strong> — para mostrar información musical. No se transmiten datos personales a Spotify.</li>
                        <li><strong>Bandsintown</strong> — para mostrar fechas de conciertos. No se transmiten datos personales a Bandsintown.</li>
                    </ul>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">7. Cookies</h2>
                    <p class="legal-text">Este sitio web solo utiliza cookies técnicas necesarias. Consulta nuestra <RouterLink to="/legal/cookies" class="legal-link">Política de Cookies</RouterLink> para más información.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">8. Tus Derechos</h2>
                    <p class="legal-text">Tienes derecho a acceder, rectificar, suprimir, limitar u oponerte al tratamiento de tus datos, así como el derecho a la portabilidad. Puedes ejercerlos contactando a: <a href="mailto:contacto@lasangreberlin.com" class="legal-link">contacto@lasangreberlin.com</a></p>
                </div>
            </template>

        </section>

        <!-- ═══ POLÍTICA DE COOKIES / COOKIE POLICY / COOKIE-RICHTLINIE ═══ -->
        <section v-else-if="type === 'cookies'" class="legal-section">

            <!-- DEUTSCH -->
            <template v-if="locale === 'de'">
                <div class="legal-block">
                    <h2 class="legal-h2">Verwendung von Cookies</h2>
                    <p class="legal-text">Diese Website verwendet ausschließlich technisch notwendige Cookies und Speichermechanismen:</p>
                    <ul class="legal-list">
                        <li><strong>Session-Cookie (Laravel-Session):</strong> Wird nur beim Zugriff auf den Administrationsbereich erstellt. Bei normaler öffentlicher Nutzung wird kein Cookie gesetzt.</li>
                        <li><strong>localStorage (Sprachpräferenz):</strong> Ihre gewählte Sprache wird im lokalen Speicher Ihres Browsers gespeichert. Dies ist kein Cookie, wird nicht an den Server übertragen und kann jederzeit in den Browsereinstellungen gelöscht werden.</li>
                    </ul>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Keine Tracking-Cookies</h2>
                    <p class="legal-text">Wir verwenden keine Analyse-, Werbe- oder Tracking-Cookies. Es werden keine Drittanbieter-Cookies gesetzt.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Einwilligung</h2>
                    <p class="legal-text">Da im normalen öffentlichen Browsing keine Cookies gesetzt werden, ist für die technisch notwendigen Cookies kein Cookie-Consent-Banner gemäß den geltenden Regelungen erforderlich.</p>
                </div>
            </template>

            <!-- ENGLISH -->
            <template v-else-if="locale === 'en'">
                <div class="legal-block">
                    <h2 class="legal-h2">Use of Cookies</h2>
                    <p class="legal-text">This website uses only technically necessary cookies and storage mechanisms:</p>
                    <ul class="legal-list">
                        <li><strong>Session cookie (Laravel session):</strong> Created only when accessing the administration panel. It is not set during normal public browsing.</li>
                        <li><strong>localStorage (language preference):</strong> Your selected language is stored in your browser's local storage. This is not a cookie, is not transmitted to the server, and can be cleared at any time in your browser settings.</li>
                    </ul>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">No Tracking Cookies</h2>
                    <p class="legal-text">We do not use analytics, advertising, or tracking cookies. No third-party cookies are set on this website.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Consent</h2>
                    <p class="legal-text">As no cookies are placed during normal public browsing, a cookie consent banner is not required for strictly necessary cookies under applicable regulations.</p>
                </div>
            </template>

            <!-- ESPAÑOL -->
            <template v-else>
                <div class="legal-block">
                    <h2 class="legal-h2">Uso de Cookies</h2>
                    <p class="legal-text">Este sitio web utiliza únicamente cookies técnicas necesarias y mecanismos de almacenamiento local:</p>
                    <ul class="legal-list">
                        <li><strong>Cookie de sesión (sesión Laravel):</strong> Solo se crea al acceder al panel de administración. No se instala durante la navegación pública normal del sitio.</li>
                        <li><strong>localStorage (preferencia de idioma):</strong> Tu idioma seleccionado se guarda en el almacenamiento local del navegador. No es una cookie, no se transmite al servidor y puedes eliminarlo en cualquier momento desde la configuración de tu navegador.</li>
                    </ul>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Sin Cookies de Seguimiento</h2>
                    <p class="legal-text">No utilizamos cookies de análisis, publicidad ni seguimiento. No se instalan cookies de terceros en este sitio web.</p>
                </div>
                <div class="legal-block">
                    <h2 class="legal-h2">Consentimiento</h2>
                    <p class="legal-text">Al no existir cookies durante la navegación pública normal, no se requiere banner de consentimiento de cookies para las cookies estrictamente necesarias según los criterios de la AEPD y el RGPD.</p>
                </div>
            </template>

        </section>

        <!-- Ruta desconocida -->
        <section v-else class="legal-section">
            <p class="legal-text">{{ t('common.not_found') }}</p>
        </section>

        <div class="legal-back">
            <RouterLink to="/" class="btn-back">{{ t('common.back_home') }}</RouterLink>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();
const route = useRoute();
const type = computed(() => route.params.type);

const pageTitle = computed(() => {
    if (type.value === 'impressum') {
        if (locale.value === 'de') return 'Impressum';
        if (locale.value === 'en') return 'Legal Notice';
        return 'Aviso Legal';
    }
    if (type.value === 'datenschutz') {
        if (locale.value === 'de') return 'Datenschutzerklärung';
        if (locale.value === 'en') return 'Privacy Policy';
        return 'Política de Privacidad';
    }
    if (type.value === 'cookies') {
        if (locale.value === 'de') return 'Cookie-Richtlinie';
        if (locale.value === 'en') return 'Cookie Policy';
        return 'Política de Cookies';
    }
    return 'Legal';
});
</script>

<style scoped>
.bb-page {
    max-width: 52rem;
    margin: 0 auto;
    padding: 4rem 2rem 6rem;
}

.legal-section { border-top: 1px solid #1E1E1E; padding-top: 2rem; }

.legal-block { margin-bottom: 2.5rem; }

.legal-h2 {
    font-family: 'Oswald', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #F2EDE3;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #1E1E1E;
}

.legal-text {
    font-family: 'Space Mono', monospace;
    font-size: 0.7rem;
    color: #888888;
    line-height: 1.9;
    letter-spacing: 0.03em;
}

.legal-list {
    font-family: 'Space Mono', monospace;
    font-size: 0.7rem;
    color: #888888;
    line-height: 1.9;
    letter-spacing: 0.03em;
    padding-left: 1.5rem;
    margin-top: 0.5rem;
}
.legal-list li { margin-bottom: 0.5rem; }

.legal-link {
    color: #C8192A;
    text-decoration: none;
}
.legal-link:hover { text-decoration: underline; }

.legal-back { margin-top: 4rem; padding-top: 2rem; border-top: 1px solid #1E1E1E; }

.btn-back {
    font-family: 'Space Mono', monospace;
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #888888;
    text-decoration: none;
    transition: color 0.15s;
}
.btn-back:hover { color: #C8192A; }
</style>
