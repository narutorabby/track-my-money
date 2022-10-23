import { createStore } from 'vuex';

const store = createStore({
    state() {
        return {
            session: localStorage.getItem('session_id') ? window.atob(localStorage.getItem('session_id')) : null,
        }
    },
    getters: {
        session: (state) => {
            return state.session;
        },
    },

    actions: {
        setSession: (context, payload) => {
            context.commit("setSession", payload);
        },
        removeSession: context => {
            context.commit("removeSession");
        },
    },

    mutations: {
        setSession: (state, payload) => {
            state.session = window.btoa(payload);
            localStorage.setItem('session_id', window.btoa(state.session));
        },
        removeSession: state => {
            state.session = null;
            localStorage.removeItem('session_id');
        },
    },
});

export default store;
