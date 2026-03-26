import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import router from './router';
import 'sweetalert2/dist/sweetalert2.min.css';
import Swal from 'sweetalert2';

window.alert = (message) => {
    let icon = 'info';
    let msgLower = typeof message === 'string' ? message.toLowerCase() : String(message).toLowerCase();
    
    if (msgLower.includes('berhasil') || msgLower.includes('sukses')) {
        icon = 'success';
    } else if (msgLower.includes('gagal') || msgLower.includes('salah') || msgLower.includes('error') || msgLower.includes('peringatan') || msgLower.includes('oops')) {
        icon = 'error';
    }
    
    Swal.fire({
        icon: icon,
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#fff',
        color: '#1f2937',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
};

createApp(App).use(router).mount("#app");
