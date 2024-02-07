import { createStore } from "vuex";
import user from "./modules/user";
import title from "./modules/title";
export default createStore({
    modules: {
        user,
        title,
    },
});
