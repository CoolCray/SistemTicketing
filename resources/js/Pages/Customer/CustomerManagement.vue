<template>
    <div class="px-6 md:px-10 pt-10 ">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Customer Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari Customer" v-model="search" @keyup="searchCustomer"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>

                <ButtonBiru text="Tambah Customer" @click="openModal" />
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
                            <th scope="col" class="px-6 py-4 font-bold">Nama</th>
                            <th scope="col" class="px-6 py-4 font-bold">Email</th>
                            <th scope="col" class="px-6 py-4 font-bold">No HP</th>
                            <th scope="col" class="px-6 py-4 font-bold">Nama Anak</th>
                            <th scope="col" class="px-6 py-4 font-bold">NIS</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data Customer yang terdaftar.
                            </td>
                        </tr>

                        <tr v-else v-for="(item, index) in data.data" :key="item.id || index"
                            class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ item.name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ item.email }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ item.phone_number }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ item.name_student }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ item.nis }}
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-3">
                                <button @click="editCustomer(item)"
                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Edit
                                </button>
                                <button @click="deleteCustomer(item.id)"
                                    class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

        <Pagination :currentPage="data.current_page" :totalPages="data.last_page" @update:page="fetchCustomer" />
    </div>

    <ModalCustomer v-if="showModal" @close="closeModal" @fetchCustomer="fetchCustomer" :isEdit="isEdit"
        :customerSelected="customerSelected" />
</template>

<script setup>
import Pagination from '../../Components/Pagination.vue';
import ButtonBiru from '../../Components/ButtonBiru.vue';
import ModalCustomer from './ModalCustomer.vue';
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
const data = ref([]);
const search = ref('');
const showModal = ref(false);
const isEdit = ref(false);
const customerSelected = ref(null);


const searchCustomer = async (page = 1) => {
    try {
        const response = await axios.get(`api/customer/search?query=${search.value}&page=${page}`);
        data.value = response.data;
    }
    catch (error) {
        console.log(error);
    }
}

const fetchCustomer = async (page = 1) => {
    try {
        const response = await axios.get(`api/customer?page=${page}`);
        data.value = response.data;
    }
    catch (error) {
        console.log(error);
    }
};

const deleteCustomer = async (id) => {
    try {
        const response = await axios.delete(`api/customer/${id}`);
        fetchCustomer();
    }
    catch (error) {
        console.log(error);
    }
}

const editCustomer = async (item) => {
    showModal.value = true;
    isEdit.value = true;
    customerSelected.value = item;
}


const openModal = () => {
    showModal.value = true;
    isEdit.value = false;
    customerSelected.value = null;
}

const closeModal = () => {
    showModal.value = false;
    isEdit.value = false;
    customerSelected.value = null;
}

onMounted(() => {
    fetchCustomer();
});

</script>