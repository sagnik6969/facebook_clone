<template>
    <div>
        <img
            :src="userImage.data.attributes.path"
            alt="user background image"
            ref="userImage"
            class="object-cover w-full"
        />
    </div>
</template>

<script setup>
import Dropzone from "dropzone";
import { computed, getCurrentInstance, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";

const store = useStore();
const route = useRoute();

const props = defineProps([
    "imageWidth",
    "imageHeight",
    "location",
    "classes",
    "alt",
    "userImage",
]);
const dropzone = ref(null);
const uploadedImage = ref(null);

const settings = computed(() => {
    return {
        paramName: "image",
        url: "/api/user-images",
        acceptedFiles: "image/*",
        params: {
            width: props.imageWidth,
            height: props.imageHeight,
            location: props.location,
        },
        headers: {
            "X-CSRF-TOKEN": document.head.querySelector("meta[name=csrf-token]")
                .content,
        },
        success: (e, res) => {
            alert("uploaded!");
            store.dispatch("fetchAuthUser");
            store.dispatch("fetchProfileUser", route.params.userId);
            store.dispatch("fetchUserPost", route.params.userId);
        },
    };
});

onMounted(() => {
    console.log(settings.value);
    dropzone.value = new Dropzone(
        getCurrentInstance().ctx.$refs.userImage,
        // above is the syntax how you access $refs in composition api
        settings.value
    );
});
</script>
