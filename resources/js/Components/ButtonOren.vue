<template>
    <button type="submit"
        class="bg-OrenDamar font-medium transition-all duration-300 hover:bg-OrenDamar/80 text-white py-2 px-4 rounded-md">{{ text
        }}</button>
</template>

<script setup>
const props = defineProps({
    text: String
})
</script>
