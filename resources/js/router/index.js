import { createWebHistory, createRouter } from "vue-router";
import store from '../store';

const routes = [{
    path: "/home",
    name: "Home",
    component: () =>
        import ('../views/Home.vue'),
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
        },
        {
            path: '/personal',
            name: 'Personal',
            component: () =>
                import ('../views/Personal.vue'),
        },
        {
            path: '/group',
            name: 'Group',
            component: () =>
                import ('../views/Group.vue'),
        },
        {
            path: '/group/details/:slug',
            name: 'GroupDetails',
            component: () =>
                import ('../views/GroupDetails.vue'),
        },
        {
            path: '/account',
            name: 'Account',
            component: () =>
                import ('../views/Account.vue'),
        },
    ]
}];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    var sessionToken = store.getters.session;
    window.axios.defaults.headers.common['Authorization'] = sessionToken ? "Bearer " + sessionToken : "";

    if (sessionToken) {
        if (store.getters.userData == null) {
            store.dispatch("setUserData");
        }
        to.name == 'Home' ? next({ name: 'Dashboard' }) : next();
    } else {
        to.name == 'Home' ? next() : next({ name: 'Home' });
    }
});

export default router;