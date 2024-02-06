import { createRouter, createWebHistory } from "vue-router";
import NewsFeed from "./vues/NewsFeed.vue";
export default createRouter({
    history: createWebHistory(),
    routes: [{ path: "/", component: NewsFeed }],
});

// flex:1 => if other items has no flex property it will take all the available space.