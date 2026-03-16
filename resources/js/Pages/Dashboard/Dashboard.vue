<template>
    <div class="px-6 py-8 mx-auto max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Damar Bali School Dashboard</h1>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Revenue Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ formatCurrency(dashboardData.total_revenue) }}</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Tickets Sold Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Tiket Terjual</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ dashboardData.total_tickets_sold }}</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
            </div>

            <!-- Check-ins Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Check-In Saat Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ dashboardData.total_checkins }}</h3>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemesan</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Kursi</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Beli</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="loading">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Memuat data...
                            </td>
                        </tr>
                        <tr v-else-if="dashboardData.recent_transactions.length === 0">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada transaksi.
                            </td>
                        </tr>
                        <tr v-for="(tx, index) in dashboardData.recent_transactions" :key="index" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ tx.customer_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ tx.customer_email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ tx.package_name }} ({{ tx.total_seats }} Kursi)</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ tx.seats }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(tx.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import moment from 'moment';
import 'moment/locale/id';

moment.locale('id');

const loading = ref(true);
const dashboardData = ref({
    total_revenue: 0,
    total_tickets_sold: 0,
    total_checkins: 0,
    recent_transactions: []
});

const fetchDashboardData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/dashboard');
        if (response.data.status === 'success') {
            dashboardData.value = response.data.data;
        }
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    } finally {
        loading.value = false;
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};

const formatDate = (dateString) => {
    return moment(dateString).format('DD MMM YYYY, HH:mm');
};

onMounted(() => {
    fetchDashboardData();
});

</script>