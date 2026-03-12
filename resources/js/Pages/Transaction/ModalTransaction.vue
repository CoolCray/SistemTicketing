<template>
    <div v-if="showModal" @click.self="$emit('close')"
        class="fixed inset-0 z-50 flex items-center bg-black/20 justify-center bg-opacity-50 backdrop-blur-sm px-4">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">

            <header class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Edit Data Pemesan</h2>
                <button type="button" @click="$emit('close')"
                    class="text-gray-400 hover:text-red-500 text-2xl font-semibold leading-none">&times;</button>
            </header>

            <main class="p-6">
                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
                        <input v-model="form.name" type="text"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="form.email" type="email"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No HP / WhatsApp</label>
                        <input v-model="form.phone_number" type="text"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anak (Opsional)</label>
                            <input v-model="form.name_student" type="text"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIS (Opsional)</label>
                            <input v-model="form.nis" type="text"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>
                </form>
            </main>

            <footer class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                <button type="button" @click="$emit('close')"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="button"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-sm transition"
                    @click="submitForm">
                    Simpan Perubahan
                </button>
            </footer>

        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    showModal: Boolean,
    transactionSelected: Object
});

const emit = defineEmits(['close', 'fetchTransactions']);

const form = ref({
    name: '',
    email: '',
    phone_number: '',
    name_student: '',
    nis: ''
});

watch(() => props.showModal, (newVal) => {
    if (newVal && props.transactionSelected && props.transactionSelected.customers) {
        const c = props.transactionSelected.customers;
        form.value = {
            name: c.name || '',
            email: c.email || '',
            phone_number: c.phone_number || '',
            name_student: c.name_student || '',
            nis: c.nis || ''
        };
    }
});

async function submitForm() {
    try {
        const response = await axios.post(`/api/transactions/${props.transactionSelected.id}`, form.value);
        if (response.status === 200) {
            alert('Data pemesan berhasil diupdate!');
            emit('close');
            emit('fetchTransactions');
        }
    } catch (error) {
        console.error(error);
        if (error.response && error.response.data && error.response.data.errors) {
            const firstError = Object.values(error.response.data.errors)[0][0];
            alert('Oops: ' + firstError);
        } else {
            alert('Terjadi kesalahan saat mengupdate data.');
        }
    }
};
</script>