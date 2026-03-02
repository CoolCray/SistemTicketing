<template>
    <div class="flex flex-col min-h-screen h-full w-64 bg-white shadow-xl relative text-gray-700">
        <div class="flex justify-center items-center border-b border-gray-200 shadow-sm bg-white py-4 h-20">
            <img src="/storage/app/public/Logo.png" alt="Company Logo" class="h-10 w-auto object-contain ">
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <router-link :to="{ name: 'dashboard' }"
                :class="['flex items-center px-4 py-3 rounded-lg transition-all duration-200 group font-medium', MenuActive('dashboard') ? 'bg-OrenDamar text-white shadow-md' : 'hover:bg-orange-50 hover:text-gray-900']">
                <svg class="w-5 h-5 mr-3 transition-colors"
                    :class="MenuActive('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </router-link>

            <router-link :to="{ name: 'user' }"
                :class="['flex items-center px-4 py-3 rounded-lg transition-all duration-200 group font-medium', MenuActive('user') ? 'bg-OrenDamar text-white shadow-md' : 'hover:bg-orange-50 hover:text-gray-900']">
                <svg class="w-5 h-5 mr-3 transition-colors"
                    :class="MenuActive('user') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                User
            </router-link>


            <router-link :to="{ name: 'customer' }"
                :class="['flex items-center px-4 py-3 rounded-lg transition-all duration-200 group font-medium', MenuActive('customer') ? 'bg-OrenDamar text-white shadow-md' : 'hover:bg-orange-50 hover:text-gray-900']">
                <svg class="w-5 h-5 mr-3 transition-colors"
                    :class="MenuActive('customer') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Customer
            </router-link>

            <div class="flex flex-col space-y-1">
                <button type="button" @click="togglePackages"
                    class="flex flex-row items-center w-full px-4 py-3 rounded-lg transition-all duration-200 group font-medium hover:bg-orange-50 hover:text-gray-900 border-none outline-none ring-0 focus:outline-none"
                    :class="isChildOfPackagesActive ? 'text-OrenDamar font-semibold' : ''">
                    <svg class="w-5 h-5 mr-3 transition-colors"
                        :class="isChildOfPackagesActive ? 'text-OrenDamar' : 'text-gray-400 group-hover:text-OrenDamar'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span>Tiket</span>
                    <svg :class="['w-4 h-4 ml-auto transition-transform duration-300', isPackagesOpen ? 'rotate-180 text-OrenDamar' : 'text-gray-400 group-hover:text-OrenDamar']"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Children of Tiket -->
                <div v-show="isPackagesOpen"
                    class="flex flex-col gap-1 pl-5 mt-1 overflow-hidden transition-all duration-300">
                    <router-link :to="{ name: 'packages' }"
                        :class="['flex items-center px-4 py-2 rounded-lg transition-all duration-200 group text-sm font-medium', MenuActive('packages') ? 'bg-OrenDamar text-white shadow-sm' : 'hover:bg-orange-50 hover:text-gray-900']">
                        <svg class="w-4 h-4 mr-3 transition-colors"
                            :class="MenuActive('packages') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Packages
                    </router-link>

                    <router-link :to="{ name: 'food' }"
                        :class="['flex items-center px-4 py-2 rounded-lg transition-all duration-200 group text-sm font-medium', MenuActive('food') ? 'bg-OrenDamar text-white shadow-sm' : 'hover:bg-orange-50 hover:text-gray-900']">
                        <svg class="w-4 h-4 mr-3 transition-colors"
                            :class="MenuActive('food') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Food
                    </router-link>

                    <router-link :to="{ name: 'drink' }"
                        :class="['flex items-center px-4 py-2 rounded-lg transition-all duration-200 group text-sm font-medium', MenuActive('drink') ? 'bg-OrenDamar text-white shadow-sm' : 'hover:bg-orange-50 hover:text-gray-900']">
                        <svg class="w-4 h-4 mr-3 transition-colors"
                            :class="MenuActive('drink') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h12v9a3 3 0 01-3 3H7a3 3 0 01-3-3V6zm12 2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2" />
                        </svg>
                        Drinks
                    </router-link>

                    <router-link :to="{ name: 'additional' }"
                        :class="['flex items-center px-4 py-2 rounded-lg transition-all duration-200 group text-sm font-medium', MenuActive('additional') ? 'bg-OrenDamar text-white shadow-sm' : 'hover:bg-orange-50 hover:text-gray-900']">
                        <svg class="w-4 h-4 mr-3 transition-colors"
                            :class="MenuActive('additional') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Additional
                    </router-link>
                </div>
            </div>

            <!-- Attendance -->
            <router-link :to="{ name: 'attendance' }"
                :class="['flex items-center px-4 py-3 rounded-lg transition-all duration-200 group font-medium mt-2', MenuActive('attendance') ? 'bg-OrenDamar text-white shadow-md' : 'hover:bg-orange-50 hover:text-gray-900']">
                <svg class="w-5 h-5 mr-3 transition-colors"
                    :class="MenuActive('attendance') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Attendance
            </router-link>

            <!-- Seat -->
            <router-link :to="{ name: 'seat' }"
                :class="['flex items-center px-4 py-3 rounded-lg transition-all duration-200 group font-medium', MenuActive('seat') ? 'bg-OrenDamar text-white shadow-md' : 'hover:bg-orange-50 hover:text-gray-900']">
                <svg class="w-5 h-5 mr-3 transition-colors"
                    :class="MenuActive('seat') ? 'text-white' : 'text-gray-400 group-hover:text-OrenDamar'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 8V6a2 2 0 012-2h6a2 2 0 012 2v2 M5 10a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4z M5 16v4 M19 16v4" />
                </svg>
                Seat
            </router-link>

        </nav>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const isPackagesOpen = ref(false);

const packagesChildren = ['packages', 'food', 'drink', 'additional'];

const isChildOfPackagesActive = computed(() => {
    return packagesChildren.includes(route.name);
});

function togglePackages() {
    isPackagesOpen.value = !isPackagesOpen.value;
}



watch(() => route.name, (newName) => {
    if (packagesChildren.includes(newName)) {
        isPackagesOpen.value = true;
    }
}, { immediate: true });

function MenuActive(routeName) {
    return route.name === routeName;
}
</script>