import { createApp } from 'vue';
import AboutManagement from './components/website-management/AboutManagement.vue';

const el = document.getElementById('about-management-app');
if (el) {
    createApp(AboutManagement).mount('#about-management-app');
}
