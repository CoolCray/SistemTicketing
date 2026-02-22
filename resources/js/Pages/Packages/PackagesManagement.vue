<template>
    <div class="p-6 md:p-10 ">

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="font-bold text-2xl text-gray-800">
                Paket Management
            </h1>

            <div class="flex gap-2">
                <div>
                    <input type="text" placeholder="Cari Paket" v-model="search" @keyup.enter="searchPackage"
                        class="bg-white w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>

                <button>tambah Makanan</button>
                <button>tambah Minuman</button>
                <ButtonBiru text="Tambah Paket" />
            </div>

        </div>
        

        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
            <table class="w-full">
                <tr class="">
                    <th>No</th>
                    <th>Nama Paket</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
                
                <tr v-if="data.length === 0">
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        Belum ada data paket yang terdaftar.
                    </td>
                </tr>
            </table>
        </div>

    </div>


</template>

<script setup>
import ButtonBiru from '../../Components/ButtonBiru.vue';

import { ref, onMounted } from 'vue';

const data = ref([]);


</script>

<style scoped></style>