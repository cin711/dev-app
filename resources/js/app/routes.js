import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        component: () => import('./pages/Departments/IndexPage.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/departments',
        component: () => import('./pages/Departments/IndexPage.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/departments/create',
        component: () => import('./pages/Departments/CreatePage.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/departments/:id',
        component: () => import('./pages/Departments/EditPage.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/login',
        component: () => import('./pages/LoginPage.vue'),
    },
    {
        path: '/register',
        component: () => import('./pages/RegisterPage.vue'),
    },
];

const router = createRouter({
    history: createWebHistory('/app'),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !localStorage.getItem('api_token')) {
        next('/login');
    } else {
        if ((to.path == '/login' || to.path == '/register') && localStorage.getItem('api_token')) {
            next('/');
        }
        next();
    }
});

export default router;