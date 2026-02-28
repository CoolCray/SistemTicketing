<template>
    <div @click.self="$emit('close')"
        class="fixed inset-0 z-50 flex items-center bg-black/20 justify-center bg-opacity-50 backdrop-blur-sm px-4">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">

            <header class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">{{ judul }}</h2>
                <button type="button" @click="$emit('close')"
                    class="text-gray-400 hover:text-red-500 text-2xl font-semibold leading-none">&times;</button>
            </header>

            <main class="p-6">
                <form @submit.prevent="submitForm" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Minuman</label>
                        <input v-model="name" type="text" placeholder="Masukkan Nama Minuman"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                </form>
            </main>

            <footer class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                <button type="button" @click="$emit('close')"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-sm transition"
                    @click="submitForm">
                    Simpan Data
                </button>
            </footer>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

import axios from 'axios';

const judul = computed(() => {
    return props.isEdit ? 'Ubah Minuman' : 'Tambah Minuman';
});


const name = ref('');

const props = defineProps({
    showModal: Boolean,
    isEdit: Boolean,
    drinkSelected: Object
});

const emit = defineEmits(['close', 'fetchDrink']);

onMounted(() => {
    if (props.isEdit) {
        name.value = props.drinkSelected.name;
    }
})

async function submitForm() {
    try {
        const response = await axios.post(props.isEdit ? `api/drinks/${props.drinkSelected.id}` : 'api/drinks', {
            name: name.value,
        });
        if (response.status === 200) {

            alert('Minuman berhasil ' + (props.isEdit ? 'diupdate' : 'ditambahkan'));
            emit('close');
            emit('fetchDrink');
        }
    } catch (error) {
        console.error(error);
    }
};
</script>