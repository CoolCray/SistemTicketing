<template>
    <div class="flex items-center justify-center space-x-2 mt-6">
        <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
            class="px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <div class="flex space-x-1">
            <template v-for="page in displayedPages" :key="page">
                <span v-if="page === '...'" class="px-3 py-2 text-gray-500">...</span>

                <button v-else @click="changePage(page)" :class="[
                    'px-4 py-2 rounded-lg font-medium transition-colors',
                    currentPage === page
                        ? 'bg-OrenDamar text-white border border-OrenDamar shadow-sm'
                        : 'bg-white text-gray-700 border border-gray-300 hover:bg-orange-50 hover:text-OrenDamar hover:border-orange-200'
                ]">
                    {{ page }}
                </button>
            </template>
        </div>

        <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
            class="px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    totalPages: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['update:page']);

const changePage = (page) => {
    if (page >= 1 && page <= props.totalPages && page !== props.currentPage) {
        emit('update:page', page);
    }
};

const displayedPages = computed(() => {
    const total = props.totalPages;
    const current = props.currentPage;
    const delta = 2;

    if (total <= 7) {
        return Array.from({ length: total }, (_, i) => i + 1);
    }

    const range = [];
    const rangeWithDots = [];
    let l;

    for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= current - delta && i <= current + delta)) {
            range.push(i);
        }
    }

    for (let i of range) {
        if (l) {
            if (i - l === 2) {
                rangeWithDots.push(l + 1);
            } else if (i - l !== 1) {
                rangeWithDots.push('...');
            }
        }
        rangeWithDots.push(i);
        l = i;
    }

    return rangeWithDots;
});
</script>
