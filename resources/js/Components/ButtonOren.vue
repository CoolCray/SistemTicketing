<template>
    <button type="submit"
        class="bg-orange-500 font-medium transition-all duration-500 hover:bg-orange-600 text-white py-2 px-4 rounded-md">{{ text
        }}</button>
</template>

<script setup>
const props = defineProps({
    text: String
})
</script>
