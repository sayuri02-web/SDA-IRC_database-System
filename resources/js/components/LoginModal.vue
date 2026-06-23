<template>
    <Transition name="modal">
        <div v-if="show" class="login-overlay" @click.self="show = false">
            <div class="login-card">

                <!-- Header -->
                <div class="login-header">
                    <div class="login-logo">
                        <i class="mdi mdi-shield-lock-outline"></i>
                    </div>
                    <h4 class="login-title">Welcome Back</h4>
                    <p class="login-subtitle">Sign in to access the management system</p>
                </div>

                <!-- Form -->
                <div class="login-body">
                    <div class="login-field">
                        <label>Email Address</label>
                        <div class="login-input-wrap">
                            <i class="mdi mdi-email-outline"></i>
                            <input
                                v-model="email"
                                type="email"
                                placeholder="admin@church.org"
                                @keyup.enter="login">
                        </div>
                    </div>

                    <div class="login-field">
                        <label>Password</label>
                        <div class="login-input-wrap">
                            <i class="mdi mdi-lock-outline"></i>
                            <input
                                v-model="password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Enter password"
                                @keyup.enter="login">
                            <button type="button" class="login-eye" @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'mdi mdi-eye-off-outline' : 'mdi mdi-eye-outline'"></i>
                            </button>
                        </div>
                    </div>

                    <p v-if="error" class="login-error">
                        <i class="mdi mdi-alert-circle-outline me-1"></i>{{ error }}
                    </p>

                    <button class="login-btn" @click="login" :disabled="loading">
                        <span v-if="loading" class="login-spinner"></span>
                        <span v-else><i class="mdi mdi-login me-1"></i>Sign In</span>
                    </button>
                </div>

                <!-- Close -->
                <button class="login-close" @click="show = false">
                    <i class="mdi mdi-close"></i>
                </button>

            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    name: 'LoginModal',
    data() {
        return {
            show: false,
            email: '',
            password: '',
            showPassword: false,
            loading: false,
            error: null,
        };
    },
    methods: {
        open() {
            this.show = true;
            this.error = null;
        },
        async login() {
            if (!this.email || !this.password) {
                this.error = 'Please fill in all fields.';
                return;
            }
            this.loading = true;
            this.error = null;

            try {
                const res = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ email: this.email, password: this.password })
                });

                if (res.ok) {
                    window.location.href = '/dashboard';
                } else {
                    const data = await res.json().catch(() => null);
                    this.error = data?.message || 'Invalid email or password.';
                }
            } catch (e) {
                this.error = 'Connection error. Please try again.';
            }

            this.loading = false;
        }
    },
    mounted() {
        // Listen for global open event
        window.addEventListener('open-login-modal', () => this.open());

        // ESC key to close
        document.addEventListener('keydown', this._handleEsc = (e) => {
            if (e.key === 'Escape' && this.show) {
                this.show = false;
            }
        });
    },
    beforeUnmount() {
        window.removeEventListener('open-login-modal', () => this.open());
        document.removeEventListener('keydown', this._handleEsc);
    }
};
</script>

<style>
/* Overlay */
.login-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 20px; }

/* Card */
.login-card { position: relative; background: #fff; border-radius: 20px; width: 100%; max-width: 400px; overflow: hidden; box-shadow: 0 24px 64px rgba(0,0,0,0.15); }

/* Header */
.login-header { text-align: center; padding: 32px 28px 20px; background: linear-gradient(135deg, #07142c, #163d5b); color: #fff; }
.login-logo { width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 14px; }
.login-title { font-size: 20px; font-weight: 700; margin: 0 0 4px; }
.login-subtitle { font-size: 13px; color: rgba(255,255,255,0.7); margin: 0; }

/* Body */
.login-body { padding: 24px 28px 28px; }
.login-field { margin-bottom: 16px; }
.login-field label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; }
.login-input-wrap { position: relative; display: flex; align-items: center; }
.login-input-wrap i { position: absolute; left: 14px; font-size: 18px; color: #a0a0a0; pointer-events: none; }
.login-input-wrap input { width: 100%; height: 46px; padding: 0 44px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 14px; background: #f8fafc; transition: .25s; }
.login-input-wrap input:focus { border-color: #28a745; background: #fff; box-shadow: 0 0 0 3px rgba(40,167,69,0.12); outline: none; }
.login-eye { position: absolute; right: 12px; background: none; border: none; font-size: 18px; color: #a0a0a0; cursor: pointer; padding: 4px; }
.login-eye:hover { color: #333; }

/* Error */
.login-error { font-size: 13px; color: #e53935; background: #fff0f0; padding: 8px 12px; border-radius: 8px; margin-bottom: 16px; }

/* Button */
.login-btn { width: 100%; height: 46px; border: none; border-radius: 10px; background: linear-gradient(135deg, #28a745, #43c55e); color: #fff; font-size: 15px; font-weight: 600; cursor: pointer; transition: .25s; display: flex; align-items: center; justify-content: center; }
.login-btn:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(40,167,69,0.3); }
.login-btn:disabled { opacity: 0.7; cursor: wait; }

/* Spinner */
.login-spinner { width: 20px; height: 20px; border: 2.5px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Close */
.login-close { position: absolute; top: 14px; right: 14px; background: rgba(255,255,255,0.15); border: none; color: rgba(255,255,255,0.8); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; transition: .2s; }
.login-close:hover { background: rgba(255,255,255,0.25); color: #fff; }

/* Transition */
.modal-enter-active, .modal-leave-active { transition: opacity .3s, transform .3s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .login-card, .modal-leave-to .login-card { transform: scale(0.9) translateY(20px); }
.modal-enter-to .login-card { transform: scale(1) translateY(0); }

@media (max-width: 480px) { .login-card { max-width: 100%; border-radius: 16px; } .login-header { padding: 24px 20px 16px; } .login-body { padding: 20px; } }
</style>
