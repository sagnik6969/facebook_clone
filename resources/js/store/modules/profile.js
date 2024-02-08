import axios from "axios";

const state = () => ({
    profileUser: null,
    profileUserStatus: null,
});

const getters = {
    profileUser(state) {
        return state.profileUser;
    },
};
const mutations = {
    setProfileUser(state, user) {
        state.profileUser = user;
    },
    setProfileUserStatus(state, userStatus) {
        state.profileUserStatus = userStatus;
    },
};
const actions = {
    fetchProfileUser(context, userId) {
        context.commit("setProfileUserStatus", "loading");

        axios
            .get(`/api/users/${userId}`)
            .then((res) => {
                context.commit("setProfileUser", res.data);
                context.commit("setProfileUserStatus", "success");
            })
            .catch(() => {
                console.log("unable to fetch user");
                context.commit("setProfileUserStatus", "error");
            });
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
