import axios from "axios";

const state = () => ({
    profileUser: null,
    profileUserStatus: null,
});

const getters = {
    profileUser(state) {
        return state.profileUser;
    },
    friendButtonText(state, getters, rootState) {
        if (state.profileUser.data.user_id == rootState.user.user.data.user_id)
            return "";

        if (!getters.friendShip) return "Add Friend";
        else if (
            !getters.friendShip.data.attributes.confirmed_at &&
            getters.friendShip.data.attributes.friend_id !=
                rootState.user.user.data.user_id
        )
            return "Pending Friend Request";
        else if (getters.friendShip.data.attributes.confirmed_at) {
            return "";
        }

        return "Accept";
    },
    friendShip(state) {
        return state.profileUser.data.attributes.friendship;
        // getters are like computed properties getters are automatically updated
        // when its dependencies get updated.
    },

    status(state, _, rootState) {
        return {
            user: state.userStatus,
            posts: rootState.posts.postsStatus,
        };
    },

    // posts(state) {
    //     return state.posts;
    // },
};
const mutations = {
    setProfileUser(state, user) {
        state.profileUser = user;
    },
    setProfileUserStatus(state, userStatus) {
        state.profileUserStatus = userStatus;
    },
    setUserFriendship(state, friendShip) {
        // console.log(friendShip);
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
        if (context.getters.friendButtonText != "Add Friend") return;

        axios
            .post("/api/friend-request", { friend_id: friendId })
            .then((res) => {
                context.commit("setUserFriendship", res.data);
            })
            .catch((err) => {
                console.log("unable to send friends request");
                // context.commit("setButtonText", "Add Friend");
            });
    },
    acceptFriendRequest(context, friendId) {
        axios
            .post("/api/friend-request-response", {
                user_id: friendId,
                status: 1,
            })
            .then((res) => {
                context.commit("setUserFriendship", res.data);
            })
            .catch((err) => {
                console.log("unable to accept friend request");
            });
    },

    ignoreFriendRequest(context, friendId) {
        axios
            .delete("/api/friend-request-response/delete", {
                data: {
                    user_id: friendId,
                    status: 1,
                },
                // in axios delete method works in slightly different way
                // here to pass data => we have to use above syntax
            })
            .then((res) => {
                context.commit("setUserFriendship", null);
            })
            .catch((err) => {
                console.log("unable to accept friend request");
            });
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
