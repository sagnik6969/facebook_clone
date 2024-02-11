import axios from "axios";

const state = () => ({
    posts: [],
    postsStatus: null,
    postMessage: "",
});
const getters = {
    posts(state) {
        return state.posts;
    },
    newsStatus(state) {
        return state.postsStatus;
    },
    postMessage(state) {
        return state.postMessage;
    },
};
const mutations = {
    setPosts(state, posts) {
        state.posts = posts;
    },
    setPostsStatus(state, status) {
        state.postsStatus = status;
    },
    updateMessage(state, message) {
        state.postMessage = message;
    },
    pushPost(state, post) {
        state.posts.data.unshift(post);
    },
    pushLikes(state, data) {
        state.posts.data[data.postKey].data.attributes.likes = data.likes;
    },
    pushComments(state, data) {
        state.posts.data[data.postKey].data.attributes.comments = data.comments;
    },
};
const actions = {
    fetchUserPost(context, userId) {
        context.commit("setPostsStatus", "loading");

        axios
            .get(`/api/users/${userId}/posts`)
            .then((res) => {
                context.commit("setPostsStatus", "success");
                context.commit("setPosts", res.data);
            })
            .catch(() => {
                context.commit("setPostsStatus", "error");
                console.log("unable to fetch posts");
            });
    },
    fetchPosts(context) {
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
