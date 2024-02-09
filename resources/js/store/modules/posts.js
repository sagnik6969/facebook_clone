const state = () => ({
    newsPosts: null,
    newsPostsStatus: null,
});
const getters = {
    newsPosts(state) {
        return state.newsPosts;
    },
    newsStatus(state) {
        return state.newsPostsStatus;
    },
};
const mutations = {
    setPosts(state, newsPosts) {
        state.newsPosts = newsPosts;
    },
    setPostsStatus(state, status) {
        state.newsPostsStatus = status;
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
};

export default {
    state,
    getters,
    mutations,
    actions,
};
