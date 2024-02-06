<template>
    <div class="flex flex-col items-center py-4">
        <NewPost />

        <Post
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

onMounted(() => {
    axios
        .get("/api/posts")
        .then((res) => {
            posts.value = res.data;
            // console.log(res.data);
        })
        .catch((error) => {
            console.log("unable to fetch posts");
        });
});
</script>

<style scoped></style>
