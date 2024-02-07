import axios from "axios";

const state = () => ({
    user: null,
    userStatus: null,
});
const getters = {
    authUser(state) {
        return state.user;
    },
};
const mutations = {
    setUser(state, user) {
        state.user = user;
    },
};
const actions = {
    fetchAuthUser(context) {
        axios
            .get("/api/auth-user")
            .then((res) => {
                context.commit("setUser", res.data);
            })
            .catch((err) => {
                console.log("unable to fetch user");
            });
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
