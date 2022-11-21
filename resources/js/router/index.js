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
                path: '/group/:slug/members',
                name: 'GroupMembers',
                component: () =>
                    import ('../views/group/Members.vue'),
            },
            {
                path: '/group/:slug/records',
                name: 'GroupRecords',
                component: () =>
                    import ('../views/group/Records.vue'),
            },
            {
                path: '/invitation',
                name: 'Invitation',
                component: () =>
                    import ('../views/Invitation.vue'),
            },
            {
                path: '/profile',
                name: 'Profile',
                component: () =>
                    import ('../views/Profile.vue'),
            },
        ]
    },
    {
        path: '/privacy-policy',
        name: 'PrivacyPolicy',
        component: () =>
            import ('../views/PrivacyPolicy.vue'),
    },
    {
        path: '/terms-of-service',
        name: 'TermsOfService',
        component: () =>
            import ('../views/TermsOfService.vue'),
    },
    {
        path: "/:pathMatch(.*)*",
        name: "NotFound",
        component: () =>
            import ('../views/NotFound.vue'),
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    var sessionToken = store.getters.session;
    window.axios.defaults.headers.common['Authorization'] = sessionToken ? "Bearer " + sessionToken : "";

    if (to.name != 'Home' && to.name != 'PrivacyPolicy' && to.name != 'TermsOfService' && to.name != 'NotFound') {
        if (sessionToken) {
            if (store.getters.userData == null) {
                store.dispatch("setUserData");
            }
            next();
        } else {
            next({ name: 'Home' });
        }
    } else {
        next();
    }
});

export default router;