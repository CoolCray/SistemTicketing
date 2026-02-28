<template>
    <div class="p-6 md:p-10 ">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Food Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari Makanan" v-model="search" @keyup.enter="searchPackage"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <ButtonBiru @click="openModal" text="Tambah Makanan" />
            </div>

        </div>


        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
           <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">

                    <thead class="text-xs text-gray-700 uppercase bg-white border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-bold text-center w-16">No</th>
                            <th class="px-6 py-4 font-bold text-center">Nama Makanan</th>
                            <th class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data Makanan yang terdaftar.
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
                                <button @click="editFood(item)"
                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Edit
                                </button>
                                <button @click="deleteFood(item.id)"
                                    class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

    </div>

    <ModalFood v-if="showModal" @close="closeModal" @fetchFoods="fetchFoods" :isEdit="isEdit" :foodSelected="foodSelected" />
</template>

<script setup>
import ButtonBiru from '../../Components/ButtonBiru.vue';
import ModalFood from './ModalFood.vue';

import { ref, onMounted } from 'vue';

const showModal = ref(false);
const data = ref([]);
const isEdit = ref(false);
const foodSelected = ref(null);

const openModal = () => {
    isEdit.value = false;
    foodSelected.value = null;
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
}


const fetchFoods = async () => {
    try {
        const response = await axios.get('api/foods');
        data.value = response.data;
    } catch (error) {
        console.error(error);
    }
}

const editFood = async (item) => {
    showModal.value = true;
    isEdit.value = true;
    foodSelected.value = item;
}

const deleteFood = async (id) => {
    try {
        const response = await axios.delete(`api/foods/${id}`);
        fetchFoods();
    } catch (error) {
        console.error(error);
    }
}

onMounted(() => {
    fetchFoods();
})



</script>

<style scoped></style>