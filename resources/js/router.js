import { createRouter, createWebHistory } from "vue-router";
import NewsFeed from "./vues/NewsFeed.vue";
import UserShow from "./vues/User/Show.vue";
export default createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "home",
            component: NewsFeed,
            meta: {
                title: "News Feed",
            },
        },
        {
            path: "/users/:userId",
            name: "user.show",
            component: UserShow,
            meta: {
                title: "Profile",
            },
        },
    ],
});

// flex:1 => if other items has no flex property it will take all the available space.
