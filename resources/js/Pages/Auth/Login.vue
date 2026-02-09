<template>
    <div class="flex flex-col justify-center items-center h-screen">
        <div class="p-10 rounded-lg bg-white xl:w-96 lg:w-80 md:w-72 w-64 shadow-lg flex flex-col gap-8">
            <header class="flex flex-col items-center justify-center text-center">
                <img class="h-15" src="../../../../storage/app/public/Logo.jpg" alt="">
                <h1 class="font-semibold text-2xl text-center">Damar Bali School</h1>
                <h2 class="font-light text-sm text-center">Sistem Ticketing</h2>
            </header>

            <form class="flex flex-col gap-5" action="handleLogin">
                <div class=" justify-center flex flex-col ">
                    <span>Username</span>
                    <input placeholder="Masukkan Username" type="text" class="border-1 border-gray-200 rounded-md text-black py-2 px-4 w-full">
                </div>
                <div class="flex flex-col">
                    <span>Password</span>
                    <input placeholder="Masukkan Password" type="password" class="border-1 border-gray-200 rounded-md text-black py-2 px-4">
                </div>
                <ButtonOren text="Login" />
            </form>
        </div>
    </div>

</template>

<script setup>
import ButtonOren from '../../Components/ButtonOren.vue';

function handleLogin() {
    console.log('Login');
}
</script>

<style scoped></style>