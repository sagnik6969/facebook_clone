import { createStore } from "vuex";
import user from "./modules/user";
import title from "./modules/title";
import profile from "./modules/profile";
import posts from "./modules/posts";
export default createStore({
    modules: {
        user,
        title,
        profile,
        posts,
    },
});
