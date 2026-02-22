<template>
    <div v-if="showModal" @click.self="$emit('close')"
        class="fixed inset-0 z-50 flex items-center bg-black/20 justify-center bg-opacity-50 backdrop-blur-sm px-4">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">

            <header class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Tambah User Baru</h2>
                <button type="button" @click="$emit('close')"
                    class="text-gray-400 hover:text-red-500 text-2xl font-semibold leading-none">&times;</button>
            </header>

            <main class="p-6">
               <button type="button" @click="$emit('close')"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-sm transition"
                    @click="submitForm">
                    Simpan Data
                </button>
            </main>

        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';


const username = ref('');
const email = ref('');
const password = ref('');
defineProps({
    showModal: Boolean
});

defineEmits(['close']);

const submitForm = () => {
    try {
        console.log(username.value, email.value, password.value);
        const response = axios.post('api/user', {
            username: username.value,
            email: email.value,
            password: password.value
        });
        console.log(response.data);
    } catch (error) {
        console.error(error);
    }
};
</script>