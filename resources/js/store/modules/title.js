const state = () => ({
    title: "facebook",
});

const getters = {
    pageTitle(state) {
        return state.title;
    },
};

const mutations = {
    setTitle(state, title) {
        state.title = title + " | facebook";
        document.title = state.title;
    },
};

const actions = {
    setPageTitle(context, title) {
        context.commit("setTitle", title);
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
