<template>
    <div class="flex flex-col h-screen w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-xl">
        <div class="flex justify-center items-center p-6 m-4 bg-white rounded-xl shadow-lg">
            <img src="../../../storage/app/public/Logo.jpg" alt="Company Logo" class="h-12 w-auto object-contain">
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <router-link :to="{ name: 'dashboard' }" v-bind:class="MenuActive('dashboard')" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </router-link>

            <router-link :to="{ name: 'customer' }" v-bind:class="MenuActive('customer')" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 15l2-2m0 0l-7-7M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Customer</span>
            </router-link>

            <router-link :to="{ name: 'packages' }" v-bind:class="MenuActive('packages')" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded-lg transition-all duration-200 group">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Packages</span>
            </router-link>

           
        </nav>

        <div class="p-4 border-t border-gray-700">
            <button @click="logout" class="flex w-full items-center px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

function MenuActive(routeName){
    if (router.currentRoute.value.name === routeName) {
        return 'bg-gray-700 text-white';
    }
    return 'text-gray-400 hover:text-white hover:bg-gray-700';
}   


function logout() {
    localStorage.removeItem('token');
    console.log('logout');
    router.push('/');
}

</script>