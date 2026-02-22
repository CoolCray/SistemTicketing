<template>
    <header class="h-20 w-full bg-white border-b border-gray-100 px-8">
        <div class="flex justify-between items-center h-full">
            <div>
                <h2 class="text-sm font-medium text-gray-400">Selamat Datang kembali,</h2>
                <p class="text-xs text-gray-400">{{ currentDate }}</p>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 border-r border-gray-200 pr-6">
                    <div class="text-right hidden sm:block">
                        <h1 class="text-sm font-bold text-gray-800 leading-none mb-1">{{ user }}</h1>
                    </div>

                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold shadow-sm ring-2 ring-white">
                        {{ user.charAt(0).toUpperCase() }}
                    </div>
                </div>

                <button @click="logout"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-50 rounded-lg transition-colors duration-200 group">
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7" />
                    </svg>
                    Logout
                </button>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const user = ref(localStorage.getItem('user'));

// Tambahan: Menampilkan tanggal hari ini
const currentDate = new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
});

function logout() {
    localStorage.removeItem('token');
    router.push({ name: 'login' });
}
</script>

<style scoped>
/* Pastikan font terlihat tajam */
header {
    -webkit-font-smoothing: antialiased;
}
</style>