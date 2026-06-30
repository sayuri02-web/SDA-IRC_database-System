<template>
    <div class="kiro-toast-container" aria-label="Notifications" role="region">
        <TransitionGroup name="kiro-toast-list">
            <Toast
                v-for="t in state.toasts"
                :key="t.id"
                :toast="t"
                @dismiss="removeToast"
            />
        </TransitionGroup>
    </div>
</template>

<script>
import Toast from './toast.vue';
import { useToast } from '../../composables/useToast.js';

export default {
    name: 'ToastContainer',
    components: { Toast },
    setup() {
        const { state, removeToast } = useToast();
        return { state, removeToast };
    }
};
</script>
