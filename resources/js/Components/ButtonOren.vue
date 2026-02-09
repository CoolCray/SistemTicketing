<template>
    <button class="bg-OrenDamar text-white py-2 px-4 rounded-md">{{ text }}</button>
</template>

<script setup>
const props = defineProps({
    text: String
})
</script>

