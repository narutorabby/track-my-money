import { createStore } from 'vuex';

const store = createStore({
    state() {
        return {
            sessionData: localStorage.getItem('sessionData') || "",
        }
    },
    getters: {
        sessionData: (state) => {
            return window.atob(state.sessionData);
        },
    },

    actions: {
        setSessionData: (context, payload) => {
            context.commit("setSessionData", payload);
        },
        removeSessionData: context => {
            context.commit("removeSessionData");
        },
    },

    mutations: {
        setSessionData: (state, payload) => {
            state.sessionData = window.btoa(payload);
            localStorage.setItem('sessionData', window.btoa(payload));
        },
        removeSessionData: state => {
            state.sessionData = "";
            localStorage.removeItem('sessionData');
        },
    },
});

export default store;
