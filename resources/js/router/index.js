import { createWebHistory, createRouter } from "vue-router";
import store from '../store';

const routes = [{
    path: '/',
    name: 'Root',
    component: () =>
        import ('../views/Root.vue'),
    children: [{
        path: "/",
        name: "Landing",
        component: () =>
            import ('../views/Landing.vue'),
        meta: {
            title: "Track My Money | The most useful personal income-expense tracker"
        }
    }]
}];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
