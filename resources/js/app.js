
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
import PastorSelectorModal from './components/PastorSelectorModal.vue';
import './composables/useToast.js';

// Mount Toast System
const toastEl = document.getElementById('toast-app');
if (toastEl) {
    const toastApp = createApp(ToastContainer);
    toastApp.mount('#toast-app');
}

// Mount Access Denied Page (if present)
import AccessRequiredModal from './components/AccessRequiredModal.vue';

const accessDeniedEl = document.getElementById('access-denied-app');
if (accessDeniedEl) {
    const adApp = createApp({
        components: { AccessRequiredModal },
        template: '<AccessRequiredModal :current-role="role" :module="module" />',
        data() { return { role: accessDeniedEl.dataset.role, module: accessDeniedEl.dataset.module }; }
    });
    adApp.mount('#access-denied-app');
}

// Mount Pastor Selector Modal (only on pages that have the mount point)
const pastorModalEl = document.getElementById('pastor-modal-app');
if (pastorModalEl) {
    const pastorApp = createApp({
        components: { PastorSelectorModal },
        template: '<PastorSelectorModal />'
    });
    pastorApp.mount('#pastor-modal-app');
}

// Mount Events & Announcements Modals (Website Management page)
import EventsModal from './components/website-management/EventsModal.vue';
import AnnouncementsModal from './components/website-management/AnnouncementMoodal.vue';
import MinistriesModal from './components/website-management/MinistriesModal.vue';

const wmModalsEl = document.getElementById('wm-modals-app');
if (wmModalsEl) {
    const wmApp = createApp({
        components: { EventsModal, AnnouncementsModal, MinistriesModal },
        template: '<EventsModal /><AnnouncementsModal /><MinistriesModal />'
    });
    wmApp.mount('#wm-modals-app');
}

// Mount Gallery Page Vue App (only on gallery management page)
import GalleryPage from './components/website-management/GalleryPage.vue';

const galleryEl = document.getElementById('gallery-vue-app');
if (galleryEl) {
    const galleryApp = createApp(GalleryPage);
    galleryApp.mount('#gallery-vue-app');
}

// Mount Album Photos Page (only on album detail page)
import AlbumPhotosPage from './components/website-management/AlbumPhotosPage.vue';

const albumPhotosEl = document.getElementById('album-photos-app');
if (albumPhotosEl) {
    const albumApp = createApp({
        components: { AlbumPhotosPage },
        template: '<AlbumPhotosPage :album-id="albumId" />',
        data() { return { albumId: albumPhotosEl.dataset.albumId }; }
    });
    albumApp.mount('#album-photos-app');
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

// Global 403 handler — show access denied when backend rejects an action
window.handleForbidden = function(response) {
    if (response && response.status === 403) {
        response.clone().json().then(function(data) {
            if (data && data.error === 'unauthorized') {
                // Store data and show access denied modal
                window.location.href = '/?access_denied=1&role=' + encodeURIComponent(data.current_role || '') + '&module=' + encodeURIComponent(data.required_module || '');
            }
        }).catch(function() {});
        return true;
    }
    return false;
};

// Override global fetch to intercept 403 responses
const originalFetch = window.fetch;
window.fetch = function() {
    return originalFetch.apply(this, arguments).then(function(response) {
        if (response.status === 403) {
            response.clone().json().then(function(data) {
                if (data && data.error === 'unauthorized') {
                    if (window.toast) {
                        window.toast.error('Access Denied', 'This action requires ' + (data.required_module === 'admin' ? 'Administrator' : data.required_module === 'certificates' ? 'Certificate Manager' : 'Website Manager') + ' access. Please switch account.');
                    }
                }
            }).catch(function() {});
        }
        return response;
    });
};