import { reactive } from 'vue';

let toastId = 0;

const state = reactive({
    toasts: []
});

function addToast(type, title, message, duration = 7000) {
    const id = ++toastId;
    state.toasts.push({ id, type, title, message, duration });
    return id;
}

function removeToast(id) {
    const index = state.toasts.findIndex(t => t.id === id);
    if (index > -1) {
        state.toasts.splice(index, 1);
    }
}

const toast = {
    success(title, message) { return addToast('success', title, message); },
    error(title, message) { return addToast('error', title, message); },
    warning(title, message) { return addToast('warning', title, message); },
    info(title, message) { return addToast('info', title, message); },
};

export function useToast() {
    return { state, toast, removeToast };
}

// Expose globally for non-Vue contexts (Blade pages, jQuery, etc.)
window.toast = toast;
