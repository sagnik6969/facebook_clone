<template>
    <div class="flex flex-col items-center py-4">
        <NewPost />

        <p v-if="loading">Loading....</p>

        <Post
            v-else
            v-for="post in posts.data"
            :key="post?.data?.post_id"
            :post="post"
        />
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import NewPost from "../components/NewPost.vue";
import Post from "../components/Post.vue";
import axios from "axios";

const posts = ref([]);
const loading = ref(true);

onMounted(() => {
    axios
        .get("/api/posts")
        .then((res) => {
            posts.value = res.data;
            loading.value = false;
            // console.log(res.data);
        })
        .catch((error) => {
            console.log("unable to fetch posts");
            loading.value = false;
        });
});
</script>

<style scoped></style>
