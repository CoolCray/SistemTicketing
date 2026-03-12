<template>
    <div class="px-6 md:px-10 pt-10 pb-10">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Pemesanan Tiket - {{
                    currentStep === 1 ? 'Langkah 1: Pilih Paket' :
                        currentStep === 2 ? 'Langkah 2: Pilih Kursi' :
                            'Langkah 3: Data Customer'
                }}
            </h1>
            <div v-if="currentStep > 1">
                <button @click="currentStep--"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold rounded-lg transition-colors mr-2">
                    Kembali
                </button>
            </div>
        </div>

        <!-- Step 1: Packages -->
        <div v-if="currentStep === 1">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Pilih Paket <span class="text-red-500">*</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-if="!packages || packages.length === 0"
                    class="col-span-1 md:col-span-3 text-center text-gray-500 py-10">
                    Memuat data paket atau paket belum terdaftar...
                </div>
                <div v-else v-for="pkg in packages" :key="pkg.id"
                    class="bg-white rounded-xl shadow border border-gray-100 p-6 flex flex-col justify-between hover:shadow-lg transition cursor-pointer"
                    :class="{ 'ring-2 ring-indigo-500 border-indigo-500': selectedPackage && selectedPackage.id === pkg.id }"
                    @click="selectPackage(pkg)">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-xl text-gray-800">{{ pkg.name }}</h3>
                            <div v-if="selectedPackage && selectedPackage.id === pkg.id" class="text-indigo-600">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-indigo-600 font-bold mb-4">{{ formatRupiah(pkg.price) }}</p>
                        <ul class="text-sm text-gray-600 space-y-1 mb-4">
                            <li>🎭 Kursi: {{ pkg.total_seats }}</li>
                            <li>🍔 {{ pkg.food ? pkg.food.name : 'Tanpa Makanan' }}</li>
                            <li>🥤 {{ pkg.drink ? pkg.drink.name : 'Tanpa Minuman' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-8" v-if="additionals && additionals.length > 0">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Pilih Additional (Opsional)</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div v-for="add in additionals" :key="add.id" @click="toggleAdditional(add)"
                        class="bg-white rounded-xl shadow border p-4 flex flex-col hover:shadow-md transition cursor-pointer"
                        :class="{ 'ring-2 ring-indigo-500 border-indigo-500': isAdditionalSelected(add), 'border-gray-200': !isAdditionalSelected(add) }">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-gray-800">{{ add.name }}</h3>
                            <div v-if="isAdditionalSelected(add)" class="text-indigo-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-indigo-600 font-medium text-sm mt-auto">{{ formatRupiah(add.price) }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button @click="goToStep2" :disabled="!selectedPackage" :class="[
                    'px-6 py-2 text-white font-semibold rounded-lg transition',
                    selectedPackage ? 'bg-indigo-600 hover:bg-indigo-700 shadow-md' : 'bg-gray-400 cursor-not-allowed'
                ]">
                    Lanjut Pilih Kursi
                </button>
            </div>
        </div>

        <!-- Step 2: Seats -->
        <div v-if="currentStep === 2">
            <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Pilih {{ totalAllowedSeats }} Kursi</h2>
                <p class="text-sm text-gray-500 mb-6">Pilih kursi yang berwarna putih.</p>

                <div class="overflow-x-auto">
                    <div class="min-w-max flex flex-col gap-3 mx-auto block max-w-fit">
                        <!-- Stage Indicator -->
                        <div
                            class="w-full max-w-3xl mx-auto rounded-b-3xl bg-indigo-100 border-x border-b border-indigo-200 h-10 flex justify-center items-center mb-6 shadow-inner">
                            <span class="text-xs font-bold text-indigo-700 uppercase tracking-[0.2em]">Panggung
                                Utama</span>
                        </div>

                        <!-- Seat Map Based on API -->
                        <div v-for="row in seatLayout" :key="row.label" class="flex gap-4 items-center justify-center">
                            <div
                                class="font-bold text-white bg-indigo-500 w-6 h-6 rounded flex items-center justify-center text-xs shadow">
                                {{ row.label }}</div>

                            <!-- Left Seats -->
                            <div class="flex gap-1">
                                <div v-for="seat in row.left" :key="seat.id" @click="toggleSeat(seat)" :class="[
                                    'w-6 h-6 rounded border flex items-center justify-center text-[9px] font-bold cursor-pointer transition select-none',
                                    seat.status === 'booked' ? 'bg-red-500 border-red-600 text-white cursor-not-allowed opacity-80' : '',
                                    seat.status === 'blocked' ? 'bg-gray-500 border-gray-600 text-white cursor-not-allowed opacity-80' : '',
                                    seat.status === 'available' && !isSelected(seat) ? 'bg-white border-gray-300 text-gray-600 hover:bg-indigo-50 hover:border-indigo-300' : '',
                                    isSelected(seat) ? 'bg-indigo-600 border-indigo-700 text-white' : ''
                                ]">
                                    {{ seat.number }}
                                </div>
                            </div>

                            <!-- Aisle -->
                            <div class="w-6"></div>

                            <!-- Right Seats -->
                            <div class="flex gap-1">
                                <div v-for="seat in row.right" :key="seat.id" @click="toggleSeat(seat)" :class="[
                                    'w-6 h-6 rounded border flex items-center justify-center text-[9px] font-bold cursor-pointer transition select-none',
                                    seat.status === 'booked' ? 'bg-red-500 border-red-600 text-white cursor-not-allowed opacity-80' : '',
                                    seat.status === 'blocked' ? 'bg-gray-500 border-gray-600 text-white cursor-not-allowed opacity-80' : '',
                                    seat.status === 'available' && !isSelected(seat) ? 'bg-white border-gray-300 text-gray-600 hover:bg-indigo-50 hover:border-indigo-300' : '',
                                    isSelected(seat) ? 'bg-indigo-600 border-indigo-700 text-white' : ''
                                ]">
                                    {{ seat.number }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button @click="goToStep3" :disabled="selectedSeats.length !== totalAllowedSeats" :class="[
                        'px-6 py-2 text-white font-semibold rounded-lg transition',
                        selectedSeats.length === totalAllowedSeats ? 'bg-indigo-600 hover:bg-indigo-700 shadow-md' : 'bg-gray-400 cursor-not-allowed'
                    ]">
                        Lanjut Isi Data Customer
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 3: Customer Form -->
        <div v-if="currentStep === 3">
            <div class="bg-white rounded-xl shadow border border-gray-100 p-8 max-w-2xl mx-auto">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Customer</h2>

                <form @submit.prevent="submitBooking" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Customer <span
                                class="text-red-500">*</span></label>
                        <input type="text" v-model="form.name" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" v-model="form.email" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No HP / WhatsApp <span
                                class="text-red-500">*</span></label>
                        <input type="text" v-model="form.phone_number" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anak (Opsional)</label>
                            <input type="text" v-model="form.name_student"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIS (Opsional)</label>
                            <input type="text" v-model="form.nis"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Summary Order -->
                    <div class="bg-indigo-50 p-4 rounded-lg mt-6">
                        <h3 class="font-bold text-indigo-900 mb-2">Ringkasan Pesanan</h3>
                        <div class="text-sm text-indigo-800 space-y-1">
                            <p><strong>Paket:</strong> {{ selectedPackage.name }} ({{
                                formatRupiah(selectedPackage.price) }})</p>
                            <p v-if="selectedAdditionals.length > 0"><strong>Additional:</strong> {{
                                selectedAdditionals.map(a => a.name).join(', ')}}</p>
                            <p><strong>Kursi Dipilih:</strong> {{selectedSeats.map(s => s.id).join(', ')}}</p>
                            <p class="text-lg font-bold text-indigo-900 mt-2">Total Harga: {{
                                formatRupiah(calculateTotalPrice()) }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" :disabled="isSubmitting"
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition disabled:opacity-50">
                            {{ isSubmitting ? 'Memproses...' : 'Proses Booking' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const currentStep = ref(1);

// Step 1 State
const packages = ref([]);
const additionals = ref([]);
const selectedPackage = ref(null);
const selectedAdditionals = ref([]);

// Step 2 State
const seatLayout = ref([]);
const selectedSeats = ref([]);
const totalAllowedSeats = computed(() => {
    let seats = 0;
    if (selectedPackage.value) {
        seats += parseInt(selectedPackage.value.total_seats) || 0;
    }
    selectedAdditionals.value.forEach(add => {
        seats += parseInt(add.total_seats) || 0;
    });
    return seats;
});

// Step 3 State
const form = ref({
    name: '',
    email: '',
    phone_number: '',
    name_student: '',
    nis: ''
});
const isSubmitting = ref(false);

const fetchPackages = async () => {
    try {
        const res = await axios.get('/api/packages');
        packages.value = res.data.data ? res.data.data : res.data;
    } catch (err) {
        console.error(err);
    }
};

const fetchAdditionals = async () => {
    try {
        const res = await axios.get('/api/additionals');
        additionals.value = res.data.data ? res.data.data : res.data;
    } catch (err) {
        console.error(err);
    }
};

const selectPackage = (pkg) => {
    selectedPackage.value = pkg;
};

const toggleAdditional = (add) => {
    const idx = selectedAdditionals.value.findIndex(a => a.id === add.id);
    if (idx > -1) selectedAdditionals.value.splice(idx, 1);
    else selectedAdditionals.value.push(add);
};

const isAdditionalSelected = (add) => {
    return selectedAdditionals.value.findIndex(a => a.id === add.id) > -1;
};

const goToStep2 = () => {
    if (!selectedPackage.value) return;
    selectedSeats.value = []; // reset seating
    fetchSeats();
    currentStep.value = 2;
};

const fetchSeats = async () => {
    try {
        const res = await axios.get('/api/seats');
        if (res.data.status === 'success') {
            seatLayout.value = res.data.data;
        }
    } catch (err) {
        console.error(err);
    }
};

const isSelected = (seat) => {
    return selectedSeats.value.find(s => s.db_id === seat.db_id) !== undefined;
};

const toggleSeat = (seat) => {
    if (seat.status === 'booked' || seat.status === 'blocked') return;

    if (isSelected(seat)) {
        selectedSeats.value = selectedSeats.value.filter(s => s.db_id !== seat.db_id);
    } else {
        if (selectedSeats.value.length < totalAllowedSeats.value) {
            selectedSeats.value.push(seat);
        } else {
            alert(`Anda hanya dapat memilih ${totalAllowedSeats.value} kursi.`);
        }
    }
};

const goToStep3 = () => {
    if (selectedSeats.value.length === totalAllowedSeats.value) {
        currentStep.value = 3;
    }
};

const submitBooking = async () => {
    isSubmitting.value = true;
    try {
        const payload = {
            package_id: selectedPackage.value.id,
            seats: selectedSeats.value.map(s => s.db_id),
            additionals: selectedAdditionals.value.map(a => a.id),
            customer: form.value
        };

        const res = await axios.post('/api/transactions', payload);
        alert('Booking berhasil dibuat!');

        // Reset everything
        selectedPackage.value = null;
        selectedAdditionals.value = [];
        selectedSeats.value = [];
        form.value = {
            name: '',
            email: '',
            phone_number: '',
            name_student: '',
            nis: ''
        };
        currentStep.value = 1;
        fetchPackages();

    } catch (error) {
        console.error(error);
        if (error.response && error.response.data) {
            const data = error.response.data;
            if (data.errors) {
                // Get the first validation error message
                const firstError = Object.values(data.errors)[0][0];
                alert('Peringatan: ' + firstError);
            } else if (data.message) {
                alert('Error: ' + data.message);
            } else {
                alert('Terjadi kesalahan. Coba lagi.');
            }
        } else {
            alert('Terjadi kesalahan. Coba lagi.');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const formatRupiah = (angka) => {
    if (!angka) return 'Rp 0';
    var reverse = Math.floor(angka).toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join('.').split('').reverse().join('');
    return 'Rp ' + ribuan;
};

const calculateTotalPrice = () => {
    let price = 0;
    if (selectedPackage.value) {
        price += Number(selectedPackage.value.price) || 0;
    }
    selectedAdditionals.value.forEach(add => {
        price += Number(add.price) || 0;
    });
    return price;
};

onMounted(() => {
    fetchPackages();
    fetchAdditionals();
});
</script>
