import { createWebHistory, createRouter } from "vue-router";
import store from '../store';

const routes = [{
    path: "/welcome",
    name: "Welcome",
    component: () =>
        import ('../views/Welcome.vue'),
}, {
    path: '/',
    name: 'Root',
    component: () =>
        import ('../views/Root.vue'),
    children: [{
        path: '/',
        name: 'Dashboard',
        component: () =>
            import ('../views/Dashboard.vue'),
    }]
}];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    var sessionToken = store.getters.session;
    window.axios.defaults.headers.common['Authorization'] = sessionToken ? "Bearer " + sessionToken : "";

    if (sessionToken) {
        to.name == 'Welcome' ? next('/') : next();
    } else {
        to.name == 'Welcome' ? next() : next({ name: 'Welcome' });
    }
});

export default router;
