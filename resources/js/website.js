import { createApp } from 'vue';
import LoginModal from './components/LoginModal.vue';

const app = createApp({
    components: { LoginModal }
});

// Mount when DOM is ready
if (document.getElementById('website-app')) {
    app.mount('#website-app');
}
