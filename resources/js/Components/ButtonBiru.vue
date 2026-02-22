<template>
    <button type="submit"
        class="bg-indigo-500 hover:bg-indigo-600 font-medium transition-all duration-300 hover:bg-indigo-700 text-white py-2 px-4 rounded-md">
        {{ text }}
    </button>
</template>

<script setup>
const props = defineProps({
    text: String
})
</script>
