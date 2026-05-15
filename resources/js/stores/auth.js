import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        loading: false,
    }),

    getters: {
        isAuthenticated: (state) => !!state.user,
        isAdmin: (state) => state.user?.is_admin === true,
    },

    actions: {
        async fetchUser() {
            try {
                const { data } = await axios.get('/api/me');
                this.user = data.user;
            } catch {
                this.user = null;
            }
        },

        async login(email, password) {
            await axios.get('/sanctum/csrf-cookie');
            const { data } = await axios.post('/api/login', { email, password });
            this.user = data.user;
        },

        async logout() {
            await axios.post('/api/logout');
            this.user = null;
        },
    },
});
