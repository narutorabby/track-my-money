import axios from 'axios';
import { createStore } from 'vuex';

const store = createStore({
    state() {
        return {
            session: localStorage.getItem('UAT') ? localStorage.getItem('UAT') : null,
            userData: null,
        }
    },
    getters: {
        session: (state) => {
            return state.session;
        },
        userData: (state) => {
            return state.userData;
        },
    },

    actions: {
        setSession: (context, payload) => {
            context.commit("setSession", payload);
        },
        setUserData: (context, payload) => {
            axios.get('/api/user/me')
                .then(res => {
                    if (res.data.response == "success") {
                        context.commit("setUserData", res.data.data);
                    }
                }).catch(err => {
                    //
                });
        },
        removeSession: context => {
            context.commit("removeSession");
        },
    },

    mutations: {
        setSession: (state, payload) => {
            state.session = payload;
            localStorage.setItem('UAT', payload);
        },
        setUserData: (state, payload) => {
            state.userData = payload;
        },
        removeSession: state => {
            state.session = null;
            state.userData = null;
            localStorage.removeItem('UAT');
        },
    },
});

export default store;
