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
                        <label class="block text-sm font-medium text-gray-700 mb-1">nama</label>
                        <input v-model="form.name" type="text" placeholder="Masukkan nama"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="form.email" type="email" placeholder="nama@email.com"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No Telelpon</label>
                        <input v-model="form.phone_number" type="text"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anak</label>
                        <input v-model="form.name_student" type="text"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                        <input v-model="form.nis" type="text"
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
    return props.isEdit ? 'Ubah User' : 'Tambah User';
});


const form = ref({
    name: '',
    email: '',
    phone_number: '',
    name_student: '',
    nis: ''
})

const props = defineProps({
    showModal: Boolean,
    isEdit: Boolean,
    customerSelected: Object
});

const emit = defineEmits(['close', 'fetchUser']);

onMounted(() => {
    if (props.isEdit) {
        form.value.name = props.customerSelected.name;
        form.value.email = props.customerSelected.email;
        form.value.phone_number = props.customerSelected.phone_number;
        form.value.name_student = props.customerSelected.name_student;
        form.value.nis = props.customerSelected.nis;
    }
})

async function submitForm() {
    try {
        const response = await axios.post(props.isEdit ? `api/customer/${props.customerSelected.id}` : 'api/customer', {
           name: form.value.name,
           email: form.value.email,
           phone_number:form.value.phone_number,
           name_student:form.value.name_student,
           nis:form.value.nis
        });
        if (response.status === 200) {

            alert('Customer berhasil ' + (props.isEdit ? 'diupdate' : 'ditambahkan'));
            emit('close');
            emit('fetchCustomer');
        }
    } catch (error) {
        console.error(error);
    }
};
</script>

