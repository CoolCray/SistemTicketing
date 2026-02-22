<template>
    <div class="main-container">
        <div class="flex w-full" v-if="router.currentRoute.value.name !== 'login'">
            <sidebar />
            <div class="w-full">
                <Header />
                <router-view></router-view>
            </div>
        </div>
        <div class="w-full" v-else>
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup>
import sidebar from './Layouts/Sidebar.vue';
import Header from './Layouts/Header.vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const token = localStorage.getItem('token');
const routerName = router.currentRoute.value.name;

if (!token) {
    router.push('/');
}

if(routerName == 'login' && token){
    router.push(routerName);
}

</script>

<style scoped>
</style>