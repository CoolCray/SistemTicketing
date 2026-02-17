
import { createRouter, createWebHistory } from 'vue-router';

import Login from '../Pages/Auth/Login.vue'; 
import Dashboard from '../Pages/Dashboard/Dashboard.vue'; 
import CustomerManagement from '../Pages/Customer/CustomerManagement.vue';
import PackagesManagement from '../Pages/Packages/PackagesManagement.vue';

const routes = [
    {
        path: '/',
        name: 'login',
        component: Login
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/customer',
        name: 'customer',
        component: CustomerManagement
    },
    {
        path: '/packages',
        name: 'packages',
        component:PackagesManagement
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;