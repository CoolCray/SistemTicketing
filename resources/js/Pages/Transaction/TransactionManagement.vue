<template>
    <div class="px-6 md:px-10 pt-10">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Data Transaksi
            </h1>

            <div class="flex gap-2">
                <input type="text" placeholder="Cari Transaksi" v-model="search" @keyup="searchTransaction"
                    class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                <button @click="exportExcel"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md transition whitespace-nowrap hidden sm:block">
                    Export Excel
                </button>
            </div>
            <button @click="exportExcel"
                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md transition sm:hidden">
                    Export Excel
            </button>
        </div>

        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col h-[calc(100vh-260px)] mt-4">
            <div class="overflow-auto max-h-[calc(100vh-220px)]">
                <table class="w-full text-sm text-left text-gray-600 relative">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold w-16">No</th>
                            <th scope="col" class="px-6 py-4 font-bold">Waktu Booking</th>
                            <th scope="col" class="px-6 py-4 font-bold">Customer</th>
                            <th scope="col" class="px-6 py-4 font-bold">Paket</th>
                            <th scope="col" class="px-6 py-4 font-bold">Kursi</th>
                            <th scope="col" class="px-6 py-4 font-bold">Additional</th>
                            <th scope="col" class="px-6 py-4 font-bold">Harga</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data Transaksi yang terdaftar.
                            </td>
                        </tr>

                        <tr v-else v-for="(item, index) in data.data" :key="item.id"
                            class="hover:bg-blue-50 transition duration-150 border-b border-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ (data.current_page - 1) * data.per_page + index + 1 }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ formatDate(item.created_at) }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ item.customers ? item.customers.name : '-' }}
                                <div class="text-xs text-gray-400 font-normal">{{ item.customers ? item.customers.email
                                    : '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ item.packages ? item.packages.name : '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-bold text-indigo-600">
                                {{ item.grouped_seats || (item.seats ? item.seats.seat_number : '-') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                <span v-if="item.transaction_additionals && item.transaction_additionals.length > 0">
                                    {{item.transaction_additionals.map(a => `${a.name} (${a.pivot.jumlah})`).join(', ')}}
                                </span>
                                <span v-else>-</span>
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-semibold whitespace-nowrap">
                                {{ formatCurrency(item.total_price) }}
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-3">
                                <button @click="editTransaction(item)"
                                    class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Edit
                                </button>
                                <button @click="deleteTransaction(item.id)"
                                    class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-md shadow transition duration-200">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="data.data && data.data.length > 0" class="mt-4">
            <Pagination :currentPage="data.current_page" :totalPages="data.last_page"
                @update:page="fetchTransactions" />
        </div>

        <ModalTransaction :showModal="showModal" :transactionSelected="transactionSelected" @close="showModal = false"
            @fetchTransactions="fetchTransactions" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Pagination from '../../Components/Pagination.vue';
import ModalTransaction from './ModalTransaction.vue';

const showModal = ref(false);
const isEdit = ref(false);
const transactionSelected = ref(null);
const search = ref('');

const data = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    per_page: 10
});

const editTransaction = async (item) => {
    showModal.value = true;
    isEdit.value = true;
    transactionSelected.value = item;
}

const deleteTransaction = async (id) => {
    try {
        const response = await axios.delete(`/api/transactions/${id}`);
        fetchTransactions();
    } catch (error) {
        console.error(error);
    }
}

const exportExcel = () => {
    window.open('/api/transactions/export', '_blank');
};

const fetchTransactions = async (page = 1) => {
    try {
        const response = await axios.get(`/api/transactions?page=${page}`);
        data.value = response.data;
    } catch (error) {
        console.error("Gagal mengambil data transaksi", error);
    }
};

const formatCurrency = (value) => {
    if (!value) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};

const searchTransaction = async () => {
    if (search.value.length === 0) {
        fetchTransactions();
        return;
    }
    
    // Fall back to original search API logic if configured
    try {
        const response = await axios.get(`/api/transactions/search?query=${search.value}`);
        // Handling the fact that search might not return pagination structure natively
        if(response.data.data) {
             data.value = response.data;
        } else {
             data.value = {
                 data: response.data,
                 current_page: 1,
                 last_page: 1,
                 per_page: 100
             };
        }
    } catch (error) {
        console.error(error);
    }
}

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    }).format(date);
};

onMounted(() => {
    fetchTransactions();
});
</script>
