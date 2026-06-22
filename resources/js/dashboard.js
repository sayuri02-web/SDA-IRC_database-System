import { createApp } from 'vue';
import Dashboard from './components/Dashboard.vue';

const el = document.getElementById('vue-dashboard');
if (el) {
    createApp(Dashboard).mount('#vue-dashboard');
}
