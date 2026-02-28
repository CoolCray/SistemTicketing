<template>
    <div class="flex flex-col min-h-screen h-full w-64 gap-5 bg-white text-white shadow-xl">
        <div class="flex justify-center items-center  border-b border-orange-500 bg-white">
            <img src="../../../storage/app/public/Logo.png" alt="Company Logo" class=" w-full object-contain">
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <router-link :to="{ name: 'dashboard' }" v-bind:class="MenuActive('dashboard')" class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                <svg v-bind:class="svgActive('dashboard')" class="w-5 h-5 mr-3 group-hover:text-gray-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </router-link>

            <router-link :to="{ name: 'user' }" v-bind:class="MenuActive('user')" class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                <svg v-bind:class="svgActive('user')" class="w-5 h-5 mr-3 group-hover:text-gray-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 15l2-2m0 0l-7-7M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">User</span>
            </router-link>

            <router-link :to="{ name: 'customer' }" v-bind:class="MenuActive('customer')" class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                <svg v-bind:class="svgActive('customer')" class="w-5 h-5 mr-3 group-hover:text-gray-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 15l2-2m0 0l-7-7M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Customer</span>
            </router-link>

            <div class="flex flex-col px-4 py-3 text-gray-400 hover:text-gray-600 gap-5 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                <div @click="togglePackages" class="flex flex-row w-full h-full">
                   <svg class="w-5 h-5 mr-3 group-hover:text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Tiket</span>
                    <svg :class="{ 'rotate-180': isPackagesOpen }" @click="togglePackages" class="w-5 h-5 ml-auto transition-transform duration-500 group-hover:text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <div v-if="isPackagesOpen" class="flex flex-col gap-3 static">
                    <router-link :to="{ name: 'packages' }" v-bind:class="MenuActive('packages')"
                        class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                        <svg v-bind:class="svgActive('packages')" class="w-5 h-5 mr-3 group-hover:text-gray-600"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-medium">Packages</span>
                    </router-link>
                    <router-link :to="{ name: 'food' }" v-bind:class="MenuActive('food')"
                        class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                        <svg v-bind:class="svgActive('food')" class="w-5 h-5 mr-3 group-hover:text-gray-600"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-medium">Food</span>
                    </router-link>

                    <router-link :to="{ name: 'drink' }" v-bind:class="MenuActive('drink')"
                        class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                        <svg v-bind:class="svgActive('drink')" class="w-5 h-5 mr-3 group-hover:text-gray-600"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-medium">Drinks</span>
                    </router-link>

                    <router-link :to="{ name: 'additional' }" v-bind:class="MenuActive('additional')"
                        class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                        <svg v-bind:class="svgActive('additional')" class="w-5 h-5 mr-3 group-hover:text-gray-600"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-medium">Additional</span>
                    </router-link>
                </div>

            </div>

            <router-link :to="{ name: 'attendance' }" v-bind:class="MenuActive('attendance')" class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                <svg v-bind:class="svgActive('attendance')" class="w-5 h-5 mr-3 group-hover:text-gray-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Attendance</span>
            </router-link>

            <router-link :to="{ name: 'seat' }" v-bind:class="MenuActive('seat')" class="flex items-center px-4 py-3 text-gray-400 hover:text-gray-600 hover:bg-orange-50 rounded-lg transition-all duration-200 group">
                <svg v-bind:class="svgActive('seat')" class="w-5 h-5 mr-3 group-hover:text-gray-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Seat</span>
            </router-link>

           
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const isPackagesOpen = ref(false);

function togglePackages() {
    isPackagesOpen.value = !isPackagesOpen.value;
}

watch(() => router.currentRoute.value.name, () => {
    if (router.currentRoute.value.name === 'packages') {
        isPackagesOpen.value = true;
    }
    if (router.currentRoute.value.name === 'food') {
        isPackagesOpen.value = true;
    }
    if (router.currentRoute.value.name === 'drink') {
        isPackagesOpen.value = true;
    }
    if (router.currentRoute.value.name === 'additional') {
        isPackagesOpen.value = true;
    }
});


function MenuActive(routeName){
    if (router.currentRoute.value.name === routeName) {
        return 'bg-OrenDamar text-white';
    }
    return '';
}   

function svgActive(routeName){
    if (router.currentRoute.value.name === routeName) {
        return 'text-white';
    }
    return 'text-gray-400';
}   

</script>