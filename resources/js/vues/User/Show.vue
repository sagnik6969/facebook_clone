<template>
    <div v-if="$store.getters.profileUser" class="flex flex-col items-center">
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
            <div
                class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20"
            >
                <button
                    v-if="
                        $store.getters.friendButtonText &&
                        $store.getters.friendButtonText != 'Accept'
                    "
                    class="py-1 px-3 bg-gray-400 rounded"
                    @click="
                        $store.dispatch(
                            `sendFriendsRequest`,
                            $route.params.userId
                        )
                    "
                >
                    {{ $store.getters.friendButtonText }}
                </button>
                <button
                    class="mr-2 py-1 px-3 bg-blue-500 rounded"
                    v-if="
                        $store.getters.friendButtonText &&
                        $store.getters.friendButtonText == 'Accept'
                    "
                    @click="
                        $store.dispatch(
                            'acceptFriendRequest',
                            $route.params.userId
                        )
                    "
                >
                    Accept
                </button>
                <button
                    class="py-1 px-3 bg-gray-400 rounded"
                    v-if="
                        $store.getters.friendButtonText &&
                        $store.getters.friendButtonText == 'Accept'
                    "
                    @click="
                        $store.dispatch(
                            'ignoreFriendRequest',
                            $route.params.userId
                        )
                    "
                >
                    Ignore
                </button>
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
import { computed, onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";

const route = useRoute();
const store = useStore();

const loading = ref(true);
const user = computed(() => store.getters.profileUser);

const posts = ref([]);
const postLoading = ref(true);

onMounted(() => {
    store.dispatch("fetchProfileUser", route.params.userId);
    // axios
    //     .get(`/api/users/${route.params.userId}`)
    //     .then((res) => {
    //         user.value = res.data;
    //     })
    //     .catch(() => {
    //         console.log("unable to fetch users");
    //     })
    //     .finally(() => {
    //         loading.value = false;
    //     });

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
