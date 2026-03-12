<template>
    <div class="px-6 md:px-10 pt-10 pb-10">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Attendance Management
            </h1>
        </div>

        <div class="bg-white p-6 rounded-xl shadow border border-gray-100 max-w-3xl mx-auto mt-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4 text-center">Scan QR Code Kehadiran</h2>
            <div v-html="qr"></div>
            <div class="flex justify-center mb-6">
                <!-- Wrapper khusus untuk Html5QrcodeScanner -->
                <div id="reader" style="width: 100%; max-width: 500px;"></div>
            </div>

            <div v-if="scanResult" class="p-4 bg-green-50 border border-green-200 rounded-lg text-center mt-4">
                <p class="text-sm text-green-600 font-bold mb-1">Hasil Scan Berhasil!</p>
                <a :href="scanResult" class="text-indigo-600 hover:underline break-all">{{ scanResult }}</a>
            </div>

            <div v-if="errorMessage" class="p-4 bg-red-50 border border-red-200 rounded-lg text-center mt-4">
                <p class="text-sm text-red-600 font-bold mb-1">Gagal Akses Kamera</p>
                <p class="text-sm text-red-500">{{ errorMessage }}</p>
            </div>

            <div v-if="cameras.length > 0" class="mt-6 flex flex-col items-center gap-4">
                <div class="w-full max-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamera</label>
                    <select v-model="selectedCamera" @change="startScanner" :disabled="isScanning"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option v-for="camera in cameras" :key="camera.id" :value="camera.id">
                            {{ camera.label || 'Kamera ' + camera.id }}
                        </option>
                    </select>
                </div>

                <div class="flex gap-4">
                    <button v-if="isScanning" @click="stopScanner"
                        class="px-6 py-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-lg shadow-md transition">
                        Hentikan Scanner
                    </button>
                    <button v-else @click="startScanner"
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                        Mulai Scanner
                    </button>
                </div>
            </div>
            <div v-else-if="!errorMessage && cameras.length === 0" class="mt-6 text-center text-gray-500 text-sm">
                Mengecek ketersediaan kamera...
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Html5Qrcode } from 'html5-qrcode';
import axios from 'axios';

const scanResult = ref(null);
const isScanning = ref(false);
const cameras = ref([]);
const selectedCamera = ref('');
const errorMessage = ref('');
const qr = ref('');
let html5QrCode = null;

// fetch qr
const fetchQr = async () => {
    try {
        const response = await axios.get('/qr/generate');
        qr.value = response.data.qr; // Ambil properti 'qr' khusus dari JSON response
    } catch (error) {
        console.error(error);
    }
}


const fetchCameras = async () => {
    try {
        const devices = await Html5Qrcode.getCameras();
        if (devices && devices.length) {
            cameras.value = devices;
            // Default select the last camera (usually back camera on mobile)
            selectedCamera.value = devices[devices.length - 1].id;
        } else {
            errorMessage.value = "Kamera tidak ditemukan di perangkat ini.";
        }
    } catch (err) {
        errorMessage.value = "Izin kamera ditolak atau perangkat tidak mendukung kamera. Pastikan Anda mengakses via Localhost atau HTTPS.";
        console.error("Camera access error:", err);
    }
};

const onScanSuccess = (decodedText) => {
    scanResult.value = decodedText;
    stopScanner(); // Stop scanning once we get a result

    // Opsional: Redirect langsung
    // window.location.href = decodedText;
};

const startScanner = async () => {
    if (!selectedCamera.value) return;

    errorMessage.value = '';
    scanResult.value = null;

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
                // Ignore background scanning errors
            }
        );
        isScanning.value = true;
    } catch (err) {
        errorMessage.value = `Gagal memulai kamera: ${err}`;
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

onMounted(async () => {
    await fetchQr();
    await fetchCameras();
});

onUnmounted(() => {
    stopScanner();
});
</script>