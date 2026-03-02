<template>
    <div class="px-6 md:px-10 pt-10">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Drinks Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari Minuman" v-model="search" @keyup="searchDrink"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <ButtonBiru @click="openModal" text="Tambah Minuman" />
            </div>

        </div>


        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col h-[calc(100vh-260px)] mt-4">
            <div class="overflow-auto flex-1">
                <table class="w-full text-sm text-left text-gray-600 relative">

                    <thead
                        class="text-xs text-gray-700 uppercase bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 font-bold text-center w-16">No</th>
                            <th class="px-6 py-4 font-bold text-center">Nama Minuman</th>
                            <th class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data Minuman yang terdaftar.
                            </td>
                        </tr>

                        <tr v-else v-for="(item, index) in data.data" :key="item.id || index"
                            class="hover:bg-blue-50 transition duration-150">
                            <td colspan="1" class="px-6 py-4 font-medium text-gray-900 text-center">
                                {{ index + 1 }}
                            </td>
                            <td colspan="1" class="px-6 py-4 font-medium text-gray-700 text-center">
                                {{ item.name }}
                            </td>
                            <td colspan="1" class="px-6 py-4 flex justify-center gap-3">
                                <button @click="editDrink(item)"
                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Edit
                                </button>
                                <button @click="deleteDrink(item.id)"
                                    class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

        <Pagination :currentPage="data.current_page" :totalPages="data.last_page" @update:page="fetchDrink" />

    </div>

    <ModalDrink v-if="showModal" @close="closeModal" @fetchDrink="fetchDrink" :isEdit="isEdit"
        :drinkSelected="drinkSelected" />
</template>

<script setup>
import Pagination from '../../Components/Pagination.vue';
import ButtonBiru from '../../Components/ButtonBiru.vue';
import ModalDrink from './ModalDrink.vue';

import { ref, onMounted } from 'vue';

const showModal = ref(false);
const data = ref([]);
const isEdit = ref(false);
const drinkSelected = ref(null);
const search = ref('');


const searchDrink = async (page = 1) => {
    try {
        const response = await axios.get(`api/drinks/search?query=${search.value}&page=${page}`);
        data.value = response.data;
    } catch (error) {
        console.error(error);
    }
}

const openModal = () => {
    isEdit.value = false;
    drinkSelected.value = null;
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
}


const fetchDrink = async (page = 1) => {
    try {
        const response = await axios.get(`api/drinks?page=${page}`);
        data.value = response.data;
    } catch (error) {
        console.error(error);
    }
}

const editDrink = async (item) => {
    showModal.value = true;
    isEdit.value = true;
    drinkSelected.value = item;
}

const deleteDrink = async (id) => {
    try {
        const response = await axios.delete(`api/drinks/${id}`);
        fetchDrink();
    } catch (error) {
        console.error(error);
    }
}

onMounted(() => {
    fetchDrink();
})



</script>

<style scoped></style>