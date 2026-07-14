<template>
<div class="row g-4">
    <!-- LEFT: Editor -->
    <div class="col-lg-7">
        <!-- Church Information -->
        <div class="about-section-card">
            <h5 class="about-section-title"><i class="mdi mdi-church me-2"></i>Church Information</h5>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Church Image</label>
                <input type="file" class="form-control" accept="image/*" @change="onImageChange">
                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" style="max-width:200px; border-radius:10px; border:1px solid #e2e8f0;">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Church History</label>
                <textarea v-model="form.church_history" class="form-control" rows="4" placeholder="Write the church history..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Mission</label>
                <textarea v-model="form.mission" class="form-control" rows="3" placeholder="Church mission statement..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Vision</label>
                <textarea v-model="form.vision" class="form-control" rows="3" placeholder="Church vision statement..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Core Values</label>
                <textarea v-model="form.core_values" class="form-control" rows="3" placeholder="Core values (one per line)..."></textarea>
            </div>

            <button class="btn btn-success btn-sm" @click="saveInfo" :disabled="saving">
                <i class="mdi mdi-content-save-outline me-1"></i> {{ saving ? 'Saving...' : 'Save Information' }}
            </button>
        </div>

        <!-- Meet Our Leaders -->
        <div class="about-section-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="about-section-title mb-0"><i class="mdi mdi-account-group me-2"></i>Meet Our Leaders</h5>
                <button class="btn btn-outline-success btn-sm" @click="showLeaderModal = true">
                    <i class="mdi mdi-plus me-1"></i> Add Leader
                </button>
            </div>

            <div v-if="leaders.length === 0" class="text-center py-4">
                <i class="mdi mdi-account-group-outline" style="font-size:36px; color:#d0d5dc;"></i>
                <p class="text-muted mt-2 mb-0" style="font-size:13px;">No leaders selected yet.</p>
            </div>

            <div v-else class="about-leaders-list">
                <div v-for="leader in leaders" :key="leader.id" class="about-leader-item">
                    <div class="about-leader-avatar">
                        <img v-if="leader.photo" :src="'/uploads/' + leader.photo" alt="">
                        <i v-else class="mdi mdi-account"></i>
                    </div>
                    <div class="about-leader-info">
                        <strong>{{ leader.name }}</strong>
                        <small>{{ leader.position }}</small>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" @click="removeLeader(leader)" title="Remove">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: Live Preview -->
    <div class="col-lg-5">
        <div class="about-preview-card">
            <h6 class="about-preview-title"><i class="mdi mdi-eye-outline me-1"></i> Live Preview</h6>
            <div class="about-preview-content">
                <!-- Church Image -->
                <div v-if="imagePreview || form.church_image" class="about-preview-image">
                    <img :src="imagePreview || '/uploads/about/' + form.church_image" alt="Church">
                </div>
                <div v-else class="about-preview-placeholder"><i class="mdi mdi-home"></i></div>

                <!-- Church History -->
                <div v-if="form.church_history" class="about-preview-section">
                    <h6>Church History</h6>
                    <p>{{ form.church_history }}</p>
                </div>

                <!-- Mission & Vision -->
                <div v-if="form.mission" class="about-preview-section">
                    <h6>Our Mission</h6>
                    <p>{{ form.mission }}</p>
                </div>
                <div v-if="form.vision" class="about-preview-section">
                    <h6>Our Vision</h6>
                    <p>{{ form.vision }}</p>
                </div>

                <!-- Leaders -->
                <div v-if="leaders.length > 0" class="about-preview-section">
                    <h6>Meet Our Leaders</h6>
                    <div class="about-preview-leaders">
                        <div v-for="leader in leaders" :key="leader.id" class="about-preview-leader">
                            <div class="about-preview-leader-avatar">
                                <img v-if="leader.photo" :src="'/uploads/' + leader.photo" alt="">
                                <i v-else class="mdi mdi-account"></i>
                            </div>
                            <strong>{{ leader.name }}</strong>
                            <small v-if="leader.organization">{{ leader.organization }}</small>
                            <small v-if="leader.position">{{ leader.position }}</small>
                            <small v-if="leader.church">{{ leader.church }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leader Selector Modal -->
<LeaderSelectorModal v-if="showLeaderModal" :selected-ids="selectedLeaderIds" @close="showLeaderModal = false" @save="onLeadersSaved" />
</template>

<script>
import LeaderSelectorModal from './LeaderSelectorModal.vue';

export default {
    name: 'AboutManagement',
    components: { LeaderSelectorModal },
    data() {
        return {
            form: { church_image: '', church_history: '', mission: '', vision: '', core_values: '' },
            imageFile: null,
            imagePreview: null,
            leaders: [],
            saving: false,
            showLeaderModal: false,
        };
    },
    computed: {
        selectedLeaderIds() {
            return this.leaders.map(l => l.member_id);
        }
    },
    methods: {
        async fetchData() {
            try {
                const res = await fetch('/website-management/about/data');
                const data = await res.json();
                if (data.churchInfo) {
                    this.form = { ...data.churchInfo };
                }
                this.leaders = data.leaders || [];
            } catch (e) { console.error(e); }
        },
        onImageChange(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.imageFile = file;
            this.imagePreview = URL.createObjectURL(file);
        },
        async saveInfo() {
            this.saving = true;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const fd = new FormData();
            fd.append('church_history', this.form.church_history || '');
            fd.append('mission', this.form.mission || '');
            fd.append('vision', this.form.vision || '');
            fd.append('core_values', this.form.core_values || '');
            if (this.imageFile) fd.append('church_image', this.imageFile);

            try {
                const res = await fetch('/website-management/about', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (data.success) {
                    if (data.churchInfo) this.form = { ...data.churchInfo };
                    this.imageFile = null;
                    if (window.toast) window.toast.success('Saved', 'Church information updated.');
                }
            } catch (e) { console.error(e); }
            this.saving = false;
        },
        async onLeadersSaved(memberIds) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                await fetch('/website-management/about/leaders', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                    body: JSON.stringify({ member_ids: memberIds })
                });
                this.showLeaderModal = false;
                this.fetchData();
                if (window.toast) window.toast.success('Saved', 'Leaders updated.');
            } catch (e) { console.error(e); }
        },
        async removeLeader(leader) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                await fetch('/website-management/about/leaders/' + leader.id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                });
                this.leaders = this.leaders.filter(l => l.id !== leader.id);
                if (window.toast) window.toast.success('Removed', 'Leader removed.');
            } catch (e) { console.error(e); }
        }
    },
    mounted() {
        this.fetchData();
    }
};
</script>

<style scoped>
.about-section-card { background: #f8fafc; border: 1px solid #edf0f5; border-radius: 14px; padding: 24px; }
.about-section-title { font-size: 15px; font-weight: 700; color: #1a1f36; margin-bottom: 16px; }
.about-leaders-list { display: flex; flex-direction: column; gap: 8px; }
.about-leader-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; background: #fff; border: 1px solid #edf0f5; border-radius: 10px; }
.about-leader-avatar { width: 36px; height: 36px; border-radius: 50%; background: #e9fff3; border: 2px solid #28a745; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
.about-leader-avatar img { width: 100%; height: 100%; object-fit: cover; }
.about-leader-avatar i { font-size: 18px; color: #28a745; }
.about-leader-info { flex: 1; }
.about-leader-info strong { display: block; font-size: 13px; color: #1a1f36; }
.about-leader-info small { font-size: 11px; color: #8898aa; }
.about-preview-card { background: #fff; border: 1px solid #edf0f5; border-radius: 14px; padding: 20px; position: sticky; top: 100px; }
.about-preview-title { font-size: 13px; font-weight: 600; color: #8898aa; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
.about-preview-content { display: flex; flex-direction: column; gap: 16px; }
.about-preview-image { border-radius: 10px; overflow: hidden; height: 180px; }
.about-preview-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
.about-preview-placeholder { height: 120px; background: #f0f4f8; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.about-preview-placeholder i { font-size: 40px; color: #d0d5dc; }
.about-preview-section h6 { font-size: 13px; font-weight: 700; color: #1a1f36; margin-bottom: 6px; }
.about-preview-section p { font-size: 12px; color: #525f7f; line-height: 1.6; margin: 0; }
.about-preview-leaders { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px; }
.about-preview-leader { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 12px 8px; background: #f8fafc; border-radius: 10px; border: 1px solid #edf0f5; }
.about-preview-leader-avatar { width: 40px; height: 40px; border-radius: 50%; background: #e9fff3; border: 2px solid #28a745; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 6px; }
.about-preview-leader-avatar img { width: 100%; height: 100%; object-fit: cover; }
.about-preview-leader-avatar i { font-size: 20px; color: #28a745; }
.about-preview-leader strong { font-size: 11px; color: #1a1f36; }
.about-preview-leader small { font-size: 10px; color: #8898aa; }
</style>
