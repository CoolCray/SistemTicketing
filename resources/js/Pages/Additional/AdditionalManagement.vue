<template>
    <div class="px-6 md:px-10 pt-10">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Additional Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari Additional" v-model="search" @keyup="searchAdditional"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <ButtonBiru @click="openModal" text="Tambah Additional" />
            </div>

        </div>


        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col sm:h-[calc(100vh-260px)] mt-4 mb-4">
            <div class="overflow-auto max-h-[calc(100vh-220px)] w-full">
                <table class="w-full text-sm text-left text-gray-600 relative min-w-max">

                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 font-bold whitespace-nowrap text-center w-16">No</th>
                            <th class="px-6 py-4 font-bold whitespace-nowrap text-center">Nama Additional</th>
                            <th class="px-6 py-4 font-bold whitespace-nowrap text-center">Harga</th>
                            <th class="px-6 py-4 font-bold whitespace-nowrap text-center">Jumlah Kursi</th>
                            <th class="px-6 py-4 font-bold whitespace-nowrap text-center w-48">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data Additional yang terdaftar.
                            </td>
                        </tr>

                        <tr v-else v-for="(item, index) in data.data" :key="item.id || index"
                            class="hover:bg-blue-50 transition duration-150 border-b border-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 text-center whitespace-nowrap">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700 text-center whitespace-nowrap">
                                {{ item.name }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700 text-center whitespace-nowrap">
                                {{ formatRupiah(item.price) }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700 text-center whitespace-nowrap">
                                {{ item.total_seats || 0 }}
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-3 whitespace-nowrap">
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

        <Pagination :currentPage="data.current_page" :totalPages="data.last_page" @update:page="fetchAdditional" />

    </div>

    <AdditionalModal v-if="showModal" @close="closeModal" @fetchAdditional="fetchAdditional" :isEdit="isEdit"
        :additionalSelected="additionalSelected" />
    <ModalDelete :showModal="showDeleteModal" @close="showDeleteModal = false" @confirm="deleteAdditionalConfirm" />
</template>

<script setup>
import Pagination from '../../Components/Pagination.vue';
import ButtonBiru from '../../Components/ButtonBiru.vue';
import AdditionalModal from './AdditionalModal.vue';
import ModalDelete from '../../Components/ModalDelete.vue';

import { ref, onMounted } from 'vue';

const showModal = ref(false);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const data = ref([]);
const isEdit = ref(false);
const additionalSelected = ref(null);

const search = ref('');


function formatRupiah(angka) {
    var reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join('.').split('').reverse().join('');
    return 'Rp ' + ribuan;
}

const searchAdditional = async (page = 1) => {
    try {
        const response = await axios.get(`api/additionals/search?query=${search.value}&page=${page}`);
        data.value = response.data;
    } catch (error) {
        console.error(error);
    }
}

const openModal = () => {
    isEdit.value = false;
    additionalSelected.value = null;
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
}


const fetchAdditional = async (page = 1) => {
    try {
        const response = await axios.get(`api/additionals?page=${page}`);
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

const deleteAdditional = (id) => {
    itemToDelete.value = id;
    showDeleteModal.value = true;
};

const deleteAdditionalConfirm = async () => {
    try {
        const response = await axios.delete(`api/additionals/${itemToDelete.value}`);
        showDeleteModal.value = false;
        itemToDelete.value = null;
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