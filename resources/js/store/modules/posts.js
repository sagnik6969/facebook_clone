import axios from "axios";

const state = () => ({
    newsPosts: null,
    newsPostsStatus: null,
    postMessage: "",
});
const getters = {
    newsPosts(state) {
        return state.newsPosts;
    },
    newsStatus(state) {
        return state.newsPostsStatus;
    },
    postMessage(state) {
        return state.postMessage;
    },
};
const mutations = {
    setPosts(state, newsPosts) {
        state.newsPosts = newsPosts;
    },
    setPostsStatus(state, status) {
        state.newsPostsStatus = status;
    },
    updateMessage(state, message) {
        state.postMessage = message;
    },
    pushPost(state, post) {
        state.newsPosts.data.unshift(post);
    },
    pushLikes(state, data) {
        state.newsPosts.data[data.postKey].data.attributes.likes = data.likes;
    },
    pushComments(state, data) {
        state.newsPosts.data[data.postKey].data.attributes.comments =
            data.comments;
    },
};
const actions = {
    fetchNewsPosts(context) {
        context.commit("setPostsStatus", "loading");
        axios
            .get("/api/posts")
            .then((res) => {
                context.commit("setPosts", res.data);
                context.commit("setPostsStatus", "success");
            })
            .catch((error) => {
                console.log("unable to fetch posts");
                context.commit("setPostsStatus", "error");
            });
    },
    postMessage(context) {
        context.commit("setPostsStatus", "loading");
        axios
            .post("/api/posts", { body: context.state.postMessage })
            .then((res) => {
                context.commit("pushPost", res.data);
                context.commit("updateMessage", "");
                context.commit("setPostsStatus", "success");
            })
            .catch((err) => {
                console.log("unable to post");
                context.commit("setPostsStatus", "failure");
            });
    },
    likePost(context, data) {
        axios
            .post("/api/posts/" + data.postId + "/like")
            .then((res) => {
                console.log("in than block");

                context.commit("pushLikes", {
                    likes: res.data,
                    postKey: data.postKey,
                });
            })
            .catch((err) => {
                console.log("unable to like post");
            });
    },
    commentPost(context, data) {
        axios
            .post("/api/posts/" + data.postId + "/comment", {
                body: data.body,
            })
            .then((res) => {
                context.commit("pushComments", {
                    comments: res.data,
                    postKey: data.postKey,
                });
            })
            .catch((err) => {
                console.log("unable to comment");
            });
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
