<template>
    <div class="px-6 md:px-10 pt-10 pb-10">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Manajemen Kursi
            </h1>
        </div>

        <div
            class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col h-[calc(100vh-260px)] mt-4">
            <div class="flex items-center justify-between p-6 pb-4 border-b border-gray-100 shrink-0">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 mb-1">Pengaturan Layout (360 Kursi)</h2>
                    <p class="text-sm text-gray-500">Klik kursi untuk mengubah status ketersediaan.</p>
                </div>
                <div class="flex gap-4 text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-white border border-gray-300 rounded"></div> Tersedia
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-gray-500 border border-gray-600 rounded"></div> Diblokir (Admin)
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-500 border border-red-600 rounded"></div> Terjual (Customer)
                    </div>
                </div>
            </div>

            <div class="overflow-auto flex-1 p-6">
                <div v-if="loading" class="text-center py-10 text-gray-500">
                    Memuat data layout kursi...
                </div>
                <div v-else class="min-w-max flex flex-col gap-3 mx-auto block max-w-fit">
                    <div
                        class="w-full max-w-3xl mx-auto rounded-b-3xl bg-indigo-100 border-x border-b border-indigo-200 h-10 flex justify-center items-center mb-6 shadow-inner">
                        <span class="text-xs font-bold text-indigo-700 uppercase tracking-[0.2em]">Panggung Utama</span>
                    </div>

                    <!-- Seat Map Based on API -->
                    <div v-for="row in seatLayout" :key="row.label" class="flex gap-4 items-center justify-center">
                        <div
                            class="font-bold text-white bg-indigo-500 w-6 h-6 rounded flex items-center justify-center text-xs shadow">
                            {{ row.label }}</div>

                        <!-- Left Seats (1-12) -->
                        <div class="flex gap-1">
                            <div v-for="seat in row.left" :key="seat.id" @click="toggleBlockSeat(seat)" :class="[
                                'w-6 h-6 rounded border flex items-center justify-center text-[9px] font-bold cursor-pointer transition select-none',
                                seat.status === 'booked' ? 'bg-red-500 border-red-600 text-white cursor-not-allowed opacity-80' : '',
                                seat.status === 'blocked' ? 'bg-gray-500 border-gray-600 text-white hover:bg-gray-600' : '',
                                seat.status === 'available' ? 'bg-white border-gray-300 text-gray-600 hover:bg-indigo-50 hover:border-indigo-300' : ''
                            ]" :title="seat.status === 'booked' ? 'Telah dipesan Customer' : 'Klik untuk Blokir/Buka'">
                                {{ seat.number }}
                            </div>
                        </div>

                        <!-- Aisle -->
                        <div class="w-6"></div>

                        <!-- Right Seats (13-24) -->
                        <div class="flex gap-1">
                            <div v-for="seat in row.right" :key="seat.id" @click="toggleBlockSeat(seat)" :class="[
                                'w-6 h-6 rounded border flex items-center justify-center text-[9px] font-bold cursor-pointer transition select-none',
                                seat.status === 'booked' ? 'bg-red-500 border-red-600 text-white cursor-not-allowed opacity-80' : '',
                                seat.status === 'blocked' ? 'bg-gray-500 border-gray-600 text-white hover:bg-gray-600' : '',
                                seat.status === 'available' ? 'bg-white border-gray-300 text-gray-600 hover:bg-indigo-50 hover:border-indigo-300' : ''
                            ]" :title="seat.status === 'booked' ? 'Telah dipesan Customer' : 'Klik untuk Blokir/Buka'">
                                {{ seat.number }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const seatLayout = ref([]);
const loading = ref(true);

const fetchSeats = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/api/seats');
        if (res.data.status === 'success') {
            seatLayout.value = res.data.data;
        }
    } catch (err) {
        console.error('Failed to fetch seats', err);
        alert('Gagal memuat data kursi');
    } finally {
        loading.value = false;
    }
};

const toggleBlockSeat = async (seat) => {
    if (seat.status === 'booked') {
        alert('Kursi ini telah dipesan oleh customer dan tidak bisa diblokir secara manual.');
        return;
    }

    try {
        const res = await axios.post('/api/seats/update-status', {
            seat_id: seat.db_id
        });

        if (res.data.status === 'success') {
            // Optimistically update UI
            seat.status = res.data.is_blocked ? 'blocked' : 'available';
        }
    } catch (err) {
        console.error('Failed to update seat status', err);
        if (err.response && err.response.data && err.response.data.message) {
            alert('Gagal: ' + err.response.data.message);
        } else {
            alert('Terjadi kesalahan saat mengupdate status kursi.');
        }
    }
};

onMounted(() => {
    fetchSeats();
});
</script>
