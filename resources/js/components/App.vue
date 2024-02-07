<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden">
        <Nav />

        <div class="flex overflow-y-hidden flex-1">
            <Sidebar />

            <div class="overflow-x-hidden w-2/3">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, watch, watchEffect } from "vue";
import Nav from "./Nav.vue";
import Sidebar from "./Sidebar.vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const store = useStore();
const route = useRoute();

// watch effect will execute when
// 1. First time when component is loaded
// 2. when the variables used inside the function changes.
watchEffect(() => {
    store.dispatch("setPageTitle", route.meta.title);
});

onMounted(() => {
    store.dispatch("fetchAuthUser");
});
</script>

<style></style>
