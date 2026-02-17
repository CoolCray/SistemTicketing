<template>
    <div class="flex flex-col justify-center items-center h-screen">
        <div class="p-10 rounded-lg bg-white xl:w-96 lg:w-80 md:w-72 w-64 shadow-lg flex flex-col gap-8">
            <header class="flex flex-col items-center justify-center text-center">
                <img class="h-15" src="../../../../storage/app/public/Logo.jpg" alt="">
                <h1 class="font-semibold text-2xl text-center">Damar Bali School</h1>
                <h2 class="font-light text-sm text-center">Sistem Ticketing</h2>
            </header>

            <form class="flex flex-col gap-5" @submit.prevent="handlelogin">
                <div class="justify-center flex flex-col ">
                    <span>Username</span>
                    <input v-model="username" placeholder="Masukkan Username" type="text" class="border-1 border-gray-200 rounded-md text-black py-2 px-4 w-full">
                </div>
                <div class="flex flex-col">
                    <span>Password</span>
                    <input v-model="password" placeholder="Masukkan Password" type="password" class="border-1 border-gray-200 rounded-md text-black py-2 px-4">
                </div>
                <ButtonOren text="Login" />
            </form>
        </div>
    </div>

</template>

<script setup>
import ButtonOren from '../../Components/ButtonOren.vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
const token = localStorage.getItem('token');
const username = ref('');
const password = ref('');

const router = useRouter();

async function handlelogin(){
    try{
        const response = await axios.post('api/login', {
            username: username.value,
            password: password.value
        });
        
        if(response.status === 200){
            const token = response.data.data.token;
            localStorage.setItem('token', token);

            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            router.push('/dashboard');
        } 
    }

    catch(error){
        alert('Username dan Password salah!');
    }
}


if(token){
    router.push('/dashboard');
}




</script>

<style scoped></style>