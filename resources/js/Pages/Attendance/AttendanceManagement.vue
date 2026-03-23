<template>
    <div class="px-6 md:px-10 pt-10 pb-10">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800 flex items-center gap-4">
                Attendance Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari Data..." v-model="search" @keyup="searchAttendances"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <button @click="openScannerModal"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-md transition">
                    Scan Absen
                </button>
                <button @click="exportExcel"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md transition whitespace-nowrap">
                    Export Excel
                </button>
            </div>
        </div>

        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col sm:h-[calc(100vh-260px)] mt-4 mb-4">
            <div class="overflow-auto max-h-[calc(100vh-220px)] w-full">
                <table class="w-full text-sm text-left text-gray-600 relative">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 font-bold whitespace-nowrap text-center w-16">No</th>
                            <th class="px-6 py-4 font-bold">Nama Pemesan</th>
                            <th class="px-6 py-4 font-bold">Email</th>
                            <th class="px-6 py-4 font-bold text-center">Nomor Kursi</th>
                            <th class="px-6 py-4 font-bold text-center">Waktu Check In</th>
                            <th class="px-6 py-4 font-bold text-center">Waktu Check Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!data.data || data.data.length === 0">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data kehadiran.
                            </td>
                        </tr>
                        <tr v-else v-for="(item, index) in data.data" :key="item.id"
                            class="hover:bg-blue-50 transition duration-150 border-b border-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 text-center whitespace-nowrap">
                                {{ (data.current_page - 1) * data.per_page + index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">{{
                                item.transaction?.customers?.name || 'N/A' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-700">{{
                                item.transaction?.customers?.email || 'N/A' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-700 text-center">{{
                                item.transaction?.seats?.seat_number || 'N/A' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-700 text-center">{{
                                formatDateTime(item.attendance_time) }}</td>
                            <td class="px-6 py-4 font-medium text-gray-700 text-center">{{
                                formatDateTime(item.leave_time) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :currentPage="data.current_page" :totalPages="data.last_page" @update:page="fetchAttendances" />

        <!-- Scanner Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
                <button @click="closeScannerModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">Scan QR Code Kehadiran</h2>

                <div class="flex justify-center mb-6 w-full">
                    <div id="reader" style="width: 100%; max-width: 400px; min-height: 300px;"></div>
                </div>

                <div v-if="scanMessage"
                    :class="{ 'bg-green-50 border-green-200 text-green-700': scanStatus === 'success', 'bg-red-50 border-red-200 text-red-700': scanStatus === 'error' }"
                    class="p-4 border rounded-lg text-center mt-4 mb-4">
                    <p class="text-sm font-bold">{{ scanMessage }}</p>
                </div>

                <!-- Recent Scans Section -->
                <div v-if="recentScans.length > 0"
                    class="mt-2 mb-6 w-full border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700">Scan Berhasil Terbaru</h3>
                    </div>
                    <ul class="max-h-30 overflow-y-auto divide-y divide-gray-100">
                        <li v-for="(scan, idx) in recentScans" :key="idx"
                            class="px-4 py-3 bg-white hover:bg-gray-50 flex justify-between items-center transition">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800 text-sm">{{ scan.transaction?.customers?.name
                                    || 'Unknown' }}</span>
                                <span class="text-xs text-gray-500">{{ scan.transaction?.seats?.seat_number || '-' }} |
                                    {{ scan.scan_type === 'checkout' ? 'Check Out' : 'Check In' }}</span>
                            </div>
                            <div class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded font-medium">
                                Sukses
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-if="cameras.length > 0" class="mt-6 flex flex-col items-center gap-4">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamera</label>
                        <select v-model="selectedCamera" @change="startScanner" :disabled="isScanning"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option v-for="camera in cameras" :key="camera.id" :value="camera.id">
                                {{ camera.label || 'Kamera ' + camera.id }}
                            </option>
                        </select>
                    </div>

                    <div class="flex gap-4 w-full justify-center">
                        <button v-if="isScanning" @click="stopScanner"
                            class="px-6 py-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-lg shadow-md transition w-full sm:w-auto">
                            Hentikan Scanner
                        </button>
                        <button v-else @click="startScanner"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition w-full sm:w-auto">
                            Mulai Scanner
                        </button>
                    </div>
                </div>
                <div v-else class="mt-6 text-center text-gray-500 text-sm">
                    Mengecek ketersediaan kamera...
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Html5Qrcode } from 'html5-qrcode';
import axios from 'axios';
import moment from 'moment';
import Pagination from '../../Components/Pagination.vue';

const data = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    per_page: 10
});
const search = ref('');
const showModal = ref(false);
const recentScans = ref([]);

const scanMessage = ref(null);
const scanStatus = ref(null); // 'success' or 'error'
const isScanning = ref(false);
const cameras = ref([]);
const selectedCamera = ref('');
let html5QrCode = null;

const searchAttendances = async (page = 1) => {
    try {
        const response = await axios.get(`/api/attendance/search?query=${search.value}&page=${page}`);
        data.value = response.data;
    } catch (error) {
        console.error("Failed to search attendances", error);
    }
};

const formatDateTime = (dateString) => {
    if (!dateString) return '-';
    return moment(dateString).format('DD MMM YYYY, HH:mm');
};

const fetchAttendances = async (page = 1) => {
    if (search.value.length > 0) {
        return searchAttendances(page);
    }
    try {
        const response = await axios.get(`/api/attendance?page=${page}`);
        data.value = response.data;
    } catch (error) {
        console.error("Failed to fetch attendances", error);
    }
};

const exportExcel = () => {
    window.open('/api/attendance/export', '_blank');
};

const fetchCameras = async () => {
    try {
        const devices = await Html5Qrcode.getCameras();
        if (devices && devices.length) {
            cameras.value = devices;
            selectedCamera.value = devices[devices.length - 1].id;
        } else {
            scanStatus.value = 'error';
            scanMessage.value = "Kamera tidak ditemukan di perangkat ini.";
        }
    } catch (err) {
        scanStatus.value = 'error';
        scanMessage.value = "Izin kamera ditolak.";
        console.error("Camera access error:", err);
    }
};

const openScannerModal = () => {
    showModal.value = true;
    scanMessage.value = null;
    scanStatus.value = null;
    fetchCameras().then(() => {
        setTimeout(() => startScanner(), 300); // slight delay to let modal render the #reader div
    });
};

const closeScannerModal = () => {
    stopScanner();
    recentScans.value = [];
    showModal.value = false;
};

const onScanSuccess = async (decodedText) => {
    stopScanner();
    scanStatus.value = null;
    scanMessage.value = "Mengecek token...";

    try {
        const response = await axios.post('/api/attendance/scan', { token: decodedText });
        scanStatus.value = 'success';
        scanMessage.value = response.data.message;

        // Add to recent scans and save explicit scan type
        const scanData = response.data.data;
        scanData.scan_type = response.data.type;
        recentScans.value.unshift(scanData);

        // Refresh table data
        fetchAttendances();

        // Restart scanner after short delay
        setTimeout(() => {
            scanMessage.value = null;
            startScanner();
        }, 3000);

    } catch (error) {
        scanStatus.value = 'error';
        scanMessage.value = error.response?.data?.message || "Terjadi kesalahan saat check-in/out.";

        // Restart scanner after short delay
        setTimeout(() => {
            scanMessage.value = null;
            startScanner();
        }, 3000);
    }
};

const startScanner = async () => {
    if (!selectedCamera.value || !document.getElementById('reader')) return;

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    try {
        await html5QrCode.start(
            selectedCamera.value,
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            (error) => {
                // Ignore background errors
            }
        );
        isScanning.value = true;
    } catch (err) {
        scanStatus.value = 'error';
        scanMessage.value = `Gagal memulai kamera: ${err}`;
        console.error(err);
    }
};

const stopScanner = async () => {
    if (html5QrCode && isScanning.value) {
        try {
            await html5QrCode.stop();
            isScanning.value = false;
        } catch (err) {
            console.error("Failed to stop scanner", err);
        }
    }
};

onMounted(() => {
    fetchAttendances();
});

onUnmounted(() => {
    stopScanner();
});
</script>