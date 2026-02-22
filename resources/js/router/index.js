
import { createRouter, createWebHistory } from 'vue-router';

import Login from '../Pages/Auth/Login.vue'; 
import Dashboard from '../Pages/Dashboard/Dashboard.vue'; 
import CustomerManagement from '../Pages/Customer/CustomerManagement.vue';
import PackagesManagement from '../Pages/Packages/PackagesManagement.vue';
import UserManagement from '../Pages/User/UserManagement.vue';
import SeatManagement from '../Pages/Seat/SeatManagement.vue';
import AttendanceManagement from '../Pages/Attendance/AttendanceManagement.vue';

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
        path: '/user',
        name: 'user',
        component: UserManagement
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
    },
    {
        path: '/seat',
        name: 'seat',
        component:SeatManagement
    },
    {
        path: '/attendance',
        name: 'attendance',
        component:AttendanceManagement
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;