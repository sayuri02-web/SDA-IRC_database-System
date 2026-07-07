<template>
<div class="access-denied-card">
    <div class="access-denied-icon">
        <i class="mdi mdi-shield-lock-outline"></i>
    </div>
    <h4 class="access-denied-title">Access Restricted</h4>
    <p class="access-denied-desc">
        You are currently signed in as <strong>{{ currentRole }}</strong>.<br>
        This action requires an <strong>{{ requiredRole }}</strong> account.<br><br>
        To continue, please switch to an account with the required permissions.
    </p>

    <div class="access-denied-info">
        <div class="access-denied-row">
            <span class="access-denied-label">Current Account:</span>
            <span class="access-denied-value">{{ currentRole }}</span>
        </div>
        <div class="access-denied-row">
            <span class="access-denied-label">Required Access:</span>
            <span class="access-denied-value access-denied-required">{{ requiredRole }}</span>
        </div>
    </div>

    <div class="access-denied-actions">
        <a href="/dashboard" class="btn btn-outline-secondary">Cancel</a>
        <form method="POST" action="/logout" style="display:inline;">
            <input type="hidden" name="_token" :value="csrfToken">
            <button type="submit" class="btn btn-success">
                <i class="mdi mdi-account-switch-outline me-1"></i> Switch Account
            </button>
        </form>
    </div>
</div>
</template>

<script>
export default {
    name: 'AccessRequiredModal',
    props: {
        currentRole: { type: String, default: '' },
        module: { type: String, default: '' },
    },
    computed: {
        csrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content || '';
        },
        requiredRole() {
            const map = {
                'certificates': 'Certificate Manager or Administrator',
                'website-management': 'Website Manager or Administrator',
                'admin': 'Administrator',
            };
            return map[this.module] || 'Administrator';
        }
    }
};
</script>

<style>
.access-denied-card { text-align: center; max-width: 460px; padding: 44px 36px; background: #fff; border-radius: 20px; border: 1px solid #edf0f5; box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
.access-denied-icon { width: 68px; height: 68px; border-radius: 50%; background: #fff0f0; color: #e53935; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 18px; }
.access-denied-title { font-size: 20px; font-weight: 700; color: #1a1f36; margin-bottom: 12px; }
.access-denied-desc { font-size: 14px; color: #525f7f; margin-bottom: 24px; line-height: 1.6; }
.access-denied-info { background: #f8fafc; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; text-align: left; }
.access-denied-row { display: flex; justify-content: space-between; padding: 10px 0; }
.access-denied-row:not(:last-child) { border-bottom: 1px solid #edf0f5; }
.access-denied-label { font-size: 13px; color: #8898aa; font-weight: 500; }
.access-denied-value { font-size: 13px; font-weight: 600; color: #1a1f36; }
.access-denied-required { color: #e53935; }
.access-denied-actions { display: flex; gap: 12px; justify-content: center; }
</style>
