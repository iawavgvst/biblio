import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import AboutUs from '../pages/AboutUs.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/about',
        name: 'About',
        component: AboutUs
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
