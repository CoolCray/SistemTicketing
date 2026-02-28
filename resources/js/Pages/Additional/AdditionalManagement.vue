<template>
    <div class="p-6 md:p-10 ">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Additional Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari Additional" v-model="search" @keyup.enter="searchPackage"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <ButtonBiru @click="openModal" text="Tambah Additional" />
            </div>

        </div>


        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">

                    <thead class="text-xs text-gray-700 uppercase bg-white border-b border-gray-200">
                        <tr>
                            <th colspan="1" class="px-6 py-4 font-bold text-center w-16">No</th>
                            <th colspan="1" class="px-6 py-4 font-bold text-center">Nama Additional</th>
                            <th colspan="1" class="px-6 py-4 font-bold text-center">Harga</th>
                            <th colspan="1" class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data Additional yang terdaftar.
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
                            <td colspan="1" class="px-6 py-4 font-medium text-gray-700 text-center">
                                {{ item.price }}
                            </td>
                            <td colspan="1" class="px-6 py-4 flex justify-center gap-3">
                                <button @click="editAdditional(item)"
                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Edit
                                </button>
                                <button @click="deleteAdditional(item.id)"
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

    <AdditionalModal v-if="showModal" @close="closeModal" @fetchAdditional="fetchAdditional" :isEdit="isEdit"
        :additionalSelected="additionalSelected" />
</template>

<script setup>
import ButtonBiru from '../../Components/ButtonBiru.vue';
import AdditionalModal from './AdditionalModal.vue';

import { ref, onMounted } from 'vue';

const showModal = ref(false);
const data = ref([]);
const isEdit = ref(false);
const additionalSelected = ref(null);

const openModal = () => {
    isEdit.value = false;
    additionalSelected.value = null;
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
}


const fetchAdditional = async () => {
    try {
        const response = await axios.get('api/additionals');
        data.value = response.data;
    } catch (error) {
        console.error(error);
    }
}

const editAdditional = async (item) => {
    showModal.value = true;
    isEdit.value = true;
    additionalSelected.value = item; 
}

const deleteAdditional = async (id) => {
    try {
        const response = await axios.delete(`api/additionals/${id}`);
        fetchAdditional();
    } catch (error) {
        console.error(error);
    }
}

onMounted(() => {
    fetchAdditional();
})


</script>

<style scoped></style>