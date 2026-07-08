<template>
<Teleport to="body">
    <Transition name="access-modal">
        <div v-if="show" class="access-modal-overlay" @click.self="close">
            <div class="access-modal-card">
                <!-- Icon -->
                <div class="access-modal-icon-wrap">
                    <i class="mdi mdi-lock-alert-outline"></i>
                </div>

                <!-- Title -->
                <h4 class="access-modal-title">Access Restricted</h4>

                <!-- Message -->
                <p class="access-modal-msg">
                    You are currently signed in as <strong>{{ currentRole }}</strong>.<br>
                    This action requires an <strong>{{ requiredRole }}</strong> account.
                </p>
                <p class="access-modal-hint">
                    Please switch to an account with the required permissions to continue.
                </p>

                <!-- Button -->
                <button class="access-modal-btn" @click="close">
                    OK
                </button>
            </div>
        </div>
    </Transition>
</Teleport>
</template>

<script>
export default {
    name: 'AccessRequiredModal',
    data() {
        return {
            show: false,
            currentRole: '',
            requiredRole: '',
        };
    },
    methods: {
        open(currentRole, requiredRole) {
            this.currentRole = currentRole;
            this.requiredRole = requiredRole;
            this.show = true;
        },
        close() {
            this.show = false;
        }
    },
    mounted() {
        window.addEventListener('show-access-modal-vue', (e) => {
            const detail = e.detail || {};
            this.open(detail.currentRole || 'Guest', detail.requiredRole || 'Administrator');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.show) this.close();
        });
    }
};
</script>

<style>
/* Overlay */
.access-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    padding: 20px;
}

/* Card — Glassmorphism */
.access-modal-card {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 24px;
    padding: 40px 36px 32px;
    max-width: 380px;
    width: 100%;
    text-align: center;
    box-shadow:
        0 24px 64px rgba(0, 0, 0, 0.12),
        0 8px 24px rgba(0, 0, 0, 0.06),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
}

/* Icon */
.access-modal-icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fff0f0, #ffe8e8);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 4px 16px rgba(229, 57, 53, 0.12);
}

.access-modal-icon-wrap i {
    font-size: 34px;
    color: #e53935;
}

/* Title */
.access-modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #1a1f36;
    margin: 0 0 14px;
}

/* Message */
.access-modal-msg {
    font-size: 14px;
    color: #525f7f;
    line-height: 1.6;
    margin: 0 0 8px;
}

.access-modal-msg strong {
    color: #1a1f36;
}

.access-modal-hint {
    font-size: 13px;
    color: #8898aa;
    margin: 0 0 28px;
}

/* Button */
.access-modal-btn {
    width: 100%;
    max-width: 200px;
    height: 44px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #28a745, #43c55e);
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.access-modal-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
}

.access-modal-btn:active {
    transform: translateY(0);
}

/* Transition */
.access-modal-enter-active {
    transition: opacity 0.25s ease, transform 0.25s ease;
}
.access-modal-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.access-modal-enter-from {
    opacity: 0;
}
.access-modal-enter-from .access-modal-card {
    transform: scale(0.9) translateY(10px);
}
.access-modal-leave-to {
    opacity: 0;
}
.access-modal-leave-to .access-modal-card {
    transform: scale(0.95) translateY(-5px);
}

/* Responsive */
@media (max-width: 480px) {
    .access-modal-card {
        padding: 32px 24px 28px;
        border-radius: 20px;
    }
    .access-modal-icon-wrap {
        width: 60px;
        height: 60px;
    }
    .access-modal-icon-wrap i { font-size: 28px; }
    .access-modal-title { font-size: 18px; }
}
</style>
