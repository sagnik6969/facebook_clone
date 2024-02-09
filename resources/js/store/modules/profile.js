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
    friendButtonText(_, getters) {
        if (!getters.friendShip) return "Add Friend";
        else if (!getters.friendShip.data.attributes.confirmed_at)
            return "Pending Friend Request";
    },
    friendShip(state) {
        return state.profileUser?.data?.attributes?.friendship;
        // getters are like computed properties getters are automatically updated
        // when its dependencies get updated.
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
    setUserFriendship(state, friendShip) {
        console.log(friendShip);
        state.profileUser.data.attributes.friendship = friendShip;
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
                // context.dispatch("setFriendButton");
            })
            .catch(() => {
                console.log("unable to fetch user");
                context.commit("setProfileUserStatus", "error");
            });
    },

    sendFriendsRequest(context, friendId) {
        axios
            .post("/api/friend-request", { friend_id: friendId })
            .then((res) => {
                context.commit("setUserFriendship", res.data);
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
