<template>
    <div @click.self="$emit('close')"
        class="fixed inset-0 z-50 flex items-center bg-black/20 justify-center bg-opacity-50 backdrop-blur-sm px-4">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">

            <header class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">{{ judul }}</h2>
                <button type="button" @click="$emit('close')"
                    class="text-gray-400 hover:text-red-500 text-2xl font-semibold leading-none">&times;</button>
            </header>

            <main class="p-6">
                <form @submit.prevent="submitForm" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Package</label>
                        <input v-model="form.name" type="text" placeholder="Masukkan Nama Package" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input v-model="form.price" type="number" placeholder="Masukkan Harga Package" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Makanan</label>
                            <select v-model="form.food_id" required
                                class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="" disabled selected>Pilih Makanan</option>
                                <option v-for="item in foods" :key="item.id" :value="item.id">{{ item.name }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Minuman</label>
                            <select v-model="form.drink_id" required
                                class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="" disabled selected>Pilih Minuman</option>
                                <option v-for="item in drinks" :key="item.id" :value="item.id">{{ item.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Kuota / Kursi Maksimal</label>
                        <input v-model="form.total_seats" type="number" placeholder="Batas kapasitas" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                </form>
            </main>

            <footer class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                <button type="button" @click="$emit('close')"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-sm transition"
                    @click="submitForm">
                    Simpan Data
                </button>
            </footer>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

import axios from 'axios';

const judul = computed(() => {
    return props.isEdit ? 'Ubah Package' : 'Tambah Package';
});


const props = defineProps({
    showModal: Boolean,
    isEdit: Boolean,
    PackageSelected: Object
});

const emit = defineEmits(['close', 'fetchPackage']);

const form = ref({
    name: '',
    price: '',
    food_id: '',
    drink_id: '',
    total_seats: ''
});

const foods = ref([]);
const drinks = ref([]);

const fetchSelectionData = async () => {
    try {
        // Fetch all foods and drinks without pagination for the dropdowns
        const resFoods = await axios.get('api/foods?all=true');
        const resDrinks = await axios.get('api/drinks?all=true');
        // If the backend doesn't support ?all=true we might only get page 1.
        // Assuming backend maps these based on standard behaviour, or we just extract the nested data array.
        foods.value = resFoods.data.data ? resFoods.data.data : resFoods.data;
        drinks.value = resDrinks.data.data ? resDrinks.data.data : resDrinks.data;
    } catch (e) {
        console.error(e);
    }
};

onMounted(() => {
    fetchSelectionData();
    if (props.isEdit && props.PackageSelected) {
        form.value.name = props.PackageSelected.name;
        form.value.price = props.PackageSelected.price;
        form.value.food_id = props.PackageSelected.food_id;
        form.value.drink_id = props.PackageSelected.drink_id;
        form.value.total_seats = props.PackageSelected.total_seats;
    }
});

async function submitForm() {
    try {
        const response = await axios.post(props.isEdit ? `api/packages/${props.PackageSelected.id}` : 'api/packages', {
            name: form.value.name,
            price: form.value.price,
            food_id: form.value.food_id,
            drink_id: form.value.drink_id,
            total_seats: form.value.total_seats
        });
        if (response.status === 200) {
            alert('Package berhasil ' + (props.isEdit ? 'diupdate' : 'ditambahkan'));
            emit('close');
            emit('fetchPackage');
        }
    } catch (error) {
        console.error(error);
    }
};
</script>