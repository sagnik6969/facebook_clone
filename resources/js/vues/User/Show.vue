<template>
    <div class="w-full h-64 overflow-hidden">
        <img
            src="https://cdn.pixabay.com/photo/2017/03/26/12/13/countryside-2175353_960_720.jpg"
            alt="user background image"
            class="object-cover w-full"
        />
    </div>
</template>
<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const loading = ref(true);
const user = ref(null);

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
});
</script>
