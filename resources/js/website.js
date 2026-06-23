import { createApp } from 'vue';
import LoginModal from './components/LoginModal.vue';

const app = createApp({
    components: { LoginModal }
});

app.mount('#website-app');
