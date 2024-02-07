<template>
    <div class="flex flex-col items-center">
        <div class="relative mb-8">
            <div class="w-100 h-64 overflow-hidden z-10">
                <img
                    src="https://cdn.pixabay.com/photo/2017/03/26/12/13/countryside-2175353_960_720.jpg"
                    alt="user background image"
                    class="object-cover w-full"
                />
            </div>

            <div
                class="absolute flex items-center bottom-0 left-0 -mb-8 ml-12 z-20"
            >
                <div class="w-32">
                    <img
                        src="https://cdn.pixabay.com/photo/2014/07/09/10/04/man-388104_960_720.jpg"
                        alt="user profile image"
                        class="object-cover w-32 h-32 border-4 border-gray-200 rounded-full shadow-lg"
                    />
                </div>

                <p class="text-2xl text-gray-100 ml-4">
                    {{ user?.data?.attributes?.name }}
                </p>
            </div>
        </div>

        <p v-if="postLoading">Loading posts...</p>

        <Post
            v-else
            v-for="post in posts.data"
            :key="post.data.post_id"
            :post="post"
        />

        <p v-if="!postLoading && posts.data.length < 1">
            No posts found. Get started...
        </p>
    </div>
</template>
<script setup>
import Post from "../../components/Post.vue";
import axios from "axios";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

const loading = ref(true);
const user = ref(null);

const posts = ref([]);
const postLoading = ref(true);

onMounted(() => {
    axios
        .get(`/api/users/${route.params.userId}`)
        .then((res) => {
            user.value = res.data;
        })
        .catch(() => {
            console.log("unable to fetch users");
        })
        .finally(() => {
            loading.value = false;
        });

    axios
        .get(`/api/users/${route.params.userId}/posts`)
        .then((res) => {
            posts.value = res.data;
        })
        .catch(() => {
            console.log("unable to fetch posts");
        })
        .finally(() => {
            postLoading.value = false;
        });
});
</script>
