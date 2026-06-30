<template>
    <div
        class="kiro-toast"
        :class="['kiro-toast-' + toast.type, { 'kiro-toast-leaving': leaving }]"
        @mouseenter="pause"
        @mouseleave="resume"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
    >
        <div class="kiro-toast-icon">
            <i :class="iconClass"></i>
        </div>
        <div class="kiro-toast-body">
            <div class="kiro-toast-title">{{ toast.title }}</div>
            <div v-if="toast.message" class="kiro-toast-message">{{ toast.message }}</div>
        </div>
        <button
            class="kiro-toast-close"
            @click="dismiss"
            aria-label="Close notification"
        >
            <i class="mdi mdi-close"></i>
        </button>
        <div class="kiro-toast-progress">
            <div
                class="kiro-toast-progress-bar"
                :style="{ width: progressWidth + '%' }"
            ></div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Toast',
    props: {
        toast: { type: Object, required: true }
    },
    emits: ['dismiss'],
    data() {
        return {
            leaving: false,
            progressWidth: 100,
            elapsed: 0,
            paused: false,
            lastTick: null,
            rafId: null,
        };
    },
    computed: {
        iconClass() {
            const icons = {
                success: 'mdi mdi-check-circle-outline',
                error: 'mdi mdi-close-circle-outline',
                warning: 'mdi mdi-alert-outline',
                info: 'mdi mdi-information-outline',
            };
            return icons[this.toast.type] || icons.info;
        }
    },
    mounted() {
        this.lastTick = performance.now();
        this.tick();
    },
    beforeUnmount() {
        if (this.rafId) cancelAnimationFrame(this.rafId);
    },
    methods: {
        tick() {
            this.rafId = requestAnimationFrame((now) => {
                if (!this.paused) {
                    const delta = now - this.lastTick;
                    this.elapsed += delta;
                    this.progressWidth = Math.max(0, 100 - (this.elapsed / this.toast.duration) * 100);

                    if (this.elapsed >= this.toast.duration) {
                        this.dismiss();
                        return;
                    }
                }
                this.lastTick = now;
                this.tick();
            });
        },
        pause() {
            this.paused = true;
        },
        resume() {
            this.paused = false;
            this.lastTick = performance.now();
        },
        dismiss() {
            if (this.leaving) return;
            this.leaving = true;
            if (this.rafId) cancelAnimationFrame(this.rafId);
            setTimeout(() => {
                this.$emit('dismiss', this.toast.id);
            }, 300);
        }
    }
};
</script>
