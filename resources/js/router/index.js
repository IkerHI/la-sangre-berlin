import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const Home         = () => import('../pages/Home.vue');
const Discography  = () => import('../pages/Discography.vue');
const Gallery      = () => import('../pages/Gallery.vue');
const Contact      = () => import('../pages/Contact.vue');
const AdminLogin   = () => import('../pages/admin/AdminLogin.vue');
const AdminLayout  = () => import('../pages/admin/AdminLayout.vue');
const AdminDashboard  = () => import('../pages/admin/AdminDashboard.vue');
const AdminConcerts   = () => import('../pages/admin/AdminConcerts.vue');
const AdminAlbums     = () => import('../pages/admin/AdminAlbums.vue');
const AdminGallery    = () => import('../pages/admin/AdminGallery.vue');
const AdminTechSpecs  = () => import('../pages/admin/AdminTechSpecs.vue');
const AdminMessages   = () => import('../pages/admin/AdminMessages.vue');
const AdminStats      = () => import('../pages/admin/AdminStats.vue');
const AdminSettings   = () => import('../pages/admin/AdminSettings.vue');
const AdminUsers      = () => import('../pages/admin/AdminUsers.vue');
const TechRider       = () => import('../pages/TechRider.vue');
const Legal           = () => import('../pages/Legal.vue');

const routes = [
    { path: '/',             component: Home,         meta: { title: 'La Sangre Berlín' } },
    { path: '/discography',  component: Discography,  meta: { title: 'Discografía — La Sangre Berlín' } },
    { path: '/gallery',      component: Gallery,      meta: { title: 'Galería — La Sangre Berlín' } },
    { path: '/contact',      component: Contact,      meta: { title: 'Contacto — La Sangre Berlín' } },
    { path: '/tech-rider',   component: TechRider,    meta: { title: 'Rider Técnico — La Sangre Berlín' } },
    { path: '/legal/:type',  component: Legal,        meta: { title: 'Legal — La Sangre Berlín' } },
    { path: '/admin/login',  component: AdminLogin,   meta: { title: 'Admin — La Sangre Berlín' } },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '',          component: AdminDashboard, meta: { title: 'Dashboard — Admin' } },
            { path: 'concerts',  component: AdminConcerts,  meta: { title: 'Conciertos — Admin' } },
            { path: 'albums',    component: AdminAlbums,    meta: { title: 'Discografía — Admin' } },
            { path: 'gallery',   component: AdminGallery,   meta: { title: 'Galería — Admin' } },
            { path: 'tech-specs',component: AdminTechSpecs, meta: { title: 'Rider Técnico — Admin' } },
            { path: 'messages',  component: AdminMessages,  meta: { title: 'Mensajes — Admin' } },
            { path: 'stats',     component: AdminStats,     meta: { title: 'Estadísticas — Admin' } },
            { path: 'settings',  component: AdminSettings,  meta: { title: 'Ajustes — Admin' } },
            { path: 'users',     component: AdminUsers,     meta: { title: 'Usuarios — Admin' } },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior: () => ({ top: 0 }),
});

router.beforeEach(async (to) => {
    if (to.meta.requiresAuth) {
        const auth = useAuthStore();
        if (!auth.isAuthenticated) {
            await auth.fetchUser();
        }
        if (!auth.isAuthenticated) {
            return { path: '/admin/login' };
        }
    }
});

router.afterEach((to) => {
    document.title = to.meta?.title ?? 'La Sangre Berlín';
});

export default router;
