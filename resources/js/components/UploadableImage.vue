<template>
    <div>
        <img
            src="https://cdn.pixabay.com/photo/2017/03/26/12/13/countryside-2175353_960_720.jpg"
            alt="user background image"
            ref="userImage"
            class="object-cover w-full"
        />
    </div>
</template>

<script setup>
import Dropzone from "dropzone";
import { computed, getCurrentInstance, onMounted, ref } from "vue";

const props = defineProps(["imageWidth", "imageHeight", "location"]);
const dropzone = ref(null);

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

// export default {
//     name: "UploadableImage",

//     props: ["imageWidth", "imageHeight", "location"],

//     data: () => {
//         return {
//             dropzone: null,
//         };
//     },

//     mounted() {
//         this.dropzone = new Dropzone(this.$refs.userImage, this.settings);
//     },

//     computed: {
//         settings() {
//             return {
//                 paramName: "image",
//                 url: "/api/user-images",
//                 acceptedFiles: "image/*",
//                 params: {
//                     width: this.imageWidth,
//                     height: this.imageHeight,
//                     location: this.location,
//                 },
//                 headers: {
//                     "X-CSRF-TOKEN": document.head.querySelector(
//                         "meta[name=csrf-token]"
//                     ).content,
//                 },
//                 success: (e, res) => {
//                     alert("uploaded!");
//                 },
//             };
//         },
//     },
// };
</script>
