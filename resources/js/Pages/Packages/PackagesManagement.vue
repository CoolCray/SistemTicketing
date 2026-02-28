<template>
    <div class="p-6 md:p-10 ">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Package Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari Package" v-model="search" @keyup.enter="searchUser"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>

                <ButtonBiru text="Tambah Package" @click="openModal" />
            </div>

        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">

                    <thead class="text-xs text-gray-700 uppercase bg-white border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold w-16">No</th>
                            <th scope="col" class="px-6 py-4 font-bold">Nama Package</th>
                            <th scope="col" class="px-6 py-4 font-bold">Harga</th>
                            <th scope="col" class="px-6 py-4 font-bold">Total Kursi</th>
                            <th scope="col" class="px-6 py-4 font-bold">Makanan</th>
                            <th scope="col" class="px-6 py-4 font-bold">Minuman</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center w-48">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data Package yang terdaftar.
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
                                {{ item.price }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ item.total_seats }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ item.foods }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ item.drinks }}
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-3">
                                <button @click="editPackage(item)"
                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Edit
                                </button>
                                <button @click="deletePackage(item.id)"
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

    <ModalPackage v-if="showModal" @close="closeModal" @fetchPackage="fetchPackage" :isEdit="isEdit"
        :PackageSelected="PackageSelected" />
</template>

<script setup>
import ButtonBiru from '../../Components/ButtonBiru.vue';
import ModalPackage from './ModalPackage.vue';
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
const data = ref([]);

const showModal = ref(false);
const isEdit = ref(false);
const PackageSelected = ref(null);

const fetchPackage = async () => {
    try {
        const response = await axios.get('api/Packages');
        data.value = response.data;
    }
    catch (error) {
        console.log(error);
    }

};

const deletePackage = async (id) => {
    try {
        const response = await axios.delete(`api/Packages/${id}`);
        fetchPackage();
    }
    catch (error) {
        console.log(error);
    }
}

const editPackage = async (item) => {
    showModal.value = true;
    isEdit.value = true;
    PackageSelected.value = item;
}


const openModal = () => {
    showModal.value = true;
    isEdit.value = false;
    PackageSelected.value = null;
}

const closeModal = () => {
    showModal.value = false;
    isEdit.value = false;
    PackageSelected.value = null;
}

onMounted(() => {
    fetchPackage();
});

</script>