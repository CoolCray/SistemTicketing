<template>
    <div class="px-6 md:px-10 pt-10 ">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                User Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari User" v-model="search" @keyup="searchUser"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>

                <ButtonBiru text="Tambah User" @click="openModal" />
            </div>

        </div>

        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col h-[calc(100vh-260px)] mt-4">
            <div class="overflow-auto flex-1">
                <table class="w-full text-sm text-left text-gray-600 relative">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold w-16">No</th>
                            <th scope="col" class="px-6 py-4 font-bold">Username</th>
                            <th scope="col" class="px-6 py-4 font-bold">Email</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data user yang terdaftar.
                            </td>
                        </tr>

                        <tr v-else v-for="(item, index) in data.data" :key="item.id || index"
                            class=" hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ item.username }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ item.email }}
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-3">
                                <button @click="updateUser(item)"
                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Edit
                                </button>
                                <button @click="deleteUser(item.id)"
                                    class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

        <Pagination :currentPage="data.current_page" :totalPages="data.last_page" @update:page="fetchUser" />

    </div>

    <ModalUser v-if="showModal" :showModal="showModal" @close="closeModal" @fetchUser="fetchUser" :isEdit="isEdit"
        :userSelected="userSelected" />
</template>

<script setup>
import Pagination from '../../Components/Pagination.vue';
import ButtonBiru from '../../Components/ButtonBiru.vue';
import ModalUser from './ModalUser.vue';
import { ref, onMounted } from 'vue';

const data = ref([]);
const search = ref('');
const showModal = ref(false);
const isEdit = ref(false);
const userSelected = ref(null);
const currentPage = ref(1);
const totalPages = ref(10);

const searchUser = async (page = 1) => {
    try {
        const response = await axios.get(`api/user/search?query=${search.value}&page=${page}`);
        data.value = response.data;
    }
    catch (error) {
        console.log(error);
    }
};

const fetchUser = async (page = 1) => {
    try {
        const response = await axios.get(`api/user?page=${page}`);
        data.value = response.data;
    }
    catch (error) {
        console.log(error);
    }
};

const deleteUser = async (id) => {
    try {
        const response = await axios.delete(`api/user/${id}`);
        fetchUser();
    }
    catch (error) {
        console.log(error);
    }

};

const updateUser = async (item) => {
    try {
        userSelected.value = item;
        isEdit.value = true;
        showModal.value = true;
    }
    catch (error) {
        console.log(error);
    }

};

const openModal = () => {
    isEdit.value = false;
    userSelected.value = null;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

onMounted(() => {
    fetchUser();
});



</script>

<style scoped></style>