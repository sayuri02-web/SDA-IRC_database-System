
import './member/index';
import './member/create';

// CHURCH JS
import './church/index';

// BAPTISM FIELD
import './certificates/baptism';
import './certificates/dedication';

// GLOBAL TOAST NOTIFICATION SYSTEM
import { createApp } from 'vue';
import ToastContainer from './components/ui/toastcontainer.vue';
import './composables/useToast.js';

const toastEl = document.getElementById('toast-app');
if (toastEl) {
    const toastApp = createApp(ToastContainer);
    toastApp.mount('#toast-app');
}

// Read Laravel session flash messages and display as toasts
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Vue to mount the toast container
    setTimeout(function() {
        if (!window.toast) return;

        const flashEl = document.getElementById('laravel-flash-data');
        if (!flashEl) return;

        const success = flashEl.dataset.success;
        const error = flashEl.dataset.error;
        const warning = flashEl.dataset.warning;
        const info = flashEl.dataset.info;
        const flashMessage = flashEl.dataset.flashMessage;

        if (success) window.toast.success('Success', success);
        if (error) window.toast.error('Error', error);
        if (warning) window.toast.warning('Warning', warning);
        if (info) window.toast.info('Info', info);
        if (flashMessage) window.toast.success('Success', flashMessage);
    }, 100);

    // Certificate forms open in new tab — show toast on the original page
    setTimeout(function() {
        if (!window.toast) return;
        document.querySelectorAll('form[target="_blank"]').forEach(function(form) {
            form.addEventListener('submit', function() {
                setTimeout(function() {
                    window.toast.success('Certificate Generated', 'The certificate has been sent to print preview.');
                }, 500);
            });
        });
    }, 200);
});