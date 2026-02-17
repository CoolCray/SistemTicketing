<template>
    <div class="p-10">
        <h1 class="text-2xl font-bold">Selamat Datang di Dashboard!</h1>
        <p>Anda berhasil login.</p>
        <button @click="logout" class="bg-red-500 text-white px-4 py-2 mt-4 rounded">Logout</button>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

function logout() {
    localStorage.removeItem('token');
    router.push('/');
}
</script>