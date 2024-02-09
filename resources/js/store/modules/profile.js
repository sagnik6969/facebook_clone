import axios from "axios";

const state = () => ({
    profileUser: null,
    profileUserStatus: null,
    friendButtonText: null,
});

const getters = {
    profileUser(state) {
        return state.profileUser;
    },
    friendButtonText(state) {
        return state.friendButtonText;
    },
    friendShip(state) {
        return state.profileUser?.data?.attributes?.friendship;
    },
};
const mutations = {
    setProfileUser(state, user) {
        state.profileUser = user;
    },
    setProfileUserStatus(state, userStatus) {
        state.profileUserStatus = userStatus;
    },
    setButtonText(state, text) {
        state.friendButtonText = text;
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
                context.dispatch("setFriendButton");
            })
            .catch(() => {
                console.log("unable to fetch user");
                context.commit("setProfileUserStatus", "error");
            });
    },

    setFriendButton(context) {
        if (!context.getters.friendShip)
            context.commit("setButtonText", "Add Friend");
        else if (!context.getters.friendShip.data.attributes.confirmed_at) {
            context.commit("setButtonText", "Pending Friend Request");
        }
    },

    sendFriendsRequest(context, friendId) {
        axios
            .post("/api/friend-request", { friend_id: friendId })
            .then((res) => {
                context.commit("setButtonText", "Pending Friend Request");
            })
            .catch((err) => {
                context.commit("setButtonText", "Add Friend");
            });
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
