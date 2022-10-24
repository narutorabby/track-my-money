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
        removeSession: context => {
            context.commit("removeSession");
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
    },

    mutations: {
        setSession: (state, payload) => {
            state.session = payload;
            localStorage.setItem('UAT', payload);
        },
        removeSession: state => {
            state.session = null;
            localStorage.removeItem('UAT');
        },
        setUserData: (state, payload) => {
            state.userData = payload;
        },
    },
});

export default store;