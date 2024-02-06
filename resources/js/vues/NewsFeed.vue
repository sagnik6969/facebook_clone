<template>
    <div class="flex flex-col items-center py-4">
        <NewPost />

        <Post />
    </div>
</template>

<script>
import NewPost from "../components/NewPost.vue";
import Post from "../components/Post.vue";

export default {
    name: "NewsFeed",

    components: {
        NewPost,
        Post,
    },
};
</script>

<style scoped></style>
