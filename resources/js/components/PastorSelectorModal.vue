<template>
    <Transition name="pm-modal">
        <div v-if="show" class="pm-modal-overlay" @click.self="close">
            <div class="pm-modal-content">

                <!-- Header -->
                <div class="pm-modal-header">
                    <div class="pm-modal-title">
                        <i class="mdi mdi-account-search-outline"></i>
                        <span>Select Pastor / Elder / Leader</span>
                    </div>
                    <button class="pm-modal-close" @click="close">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="pm-modal-body">

                    <!-- Search -->
                    <div class="pm-modal-search-wrap">
                        <i class="mdi mdi-magnify pm-modal-search-icon"></i>
                        <input
                            type="text"
                            class="pm-modal-search"
                            placeholder="Search by name or position..."
                            v-model="search"
                            @input="fetchLeaders"
                            ref="searchInput">
                    </div>

                    <!-- Loading -->
                    <div v-if="loading" class="pm-modal-loading">
                        <div class="pm-modal-spinner"></div>
                        <span>Loading leaders...</span>
                    </div>

                    <!-- Error -->
                    <div v-else-if="error" class="pm-modal-error">
                        <i class="mdi mdi-alert-circle-outline"></i>
                        <span>{{ error }}</span>
                        <button class="pm-modal-retry" @click="fetchLeaders">Retry</button>
                    </div>

                    <!-- Table -->
                    <div v-else class="pm-modal-table-wrap">
                        <table class="pm-modal-table">
                            <thead>
                                <tr>
                                    <th style="width:70px">Photo</th>
                                    <th>Full Name</th>
                                    <th>Position</th>
                                    <th style="width:120px">Organization</th>
                                    <th style="width:100px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="leader in leaders" :key="leader.id">
                                    <td>
                                        <img
                                            v-if="leader.photo"
                                            :src="'/uploads/' + leader.photo"
                                            class="pm-modal-photo"
                                            :alt="leader.name">
                                        <div v-else class="pm-modal-avatar">
                                            {{ leader.name ? leader.name.charAt(0).toUpperCase() : '?' }}
                                        </div>
                                    </td>
                                    <td class="pm-modal-name">{{ leader.name }}</td>
                                    <td class="pm-modal-position">{{ leader.position || '—' }}</td>
                                    <td class="pm-modal-org">{{ leader.organization || '—' }}</td>
                                    <td class="text-center">
                                        <button
                                            class="pm-modal-select-btn"
                                            @click="selectPastor(leader)">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="leaders.length === 0 && !loading">
                                    <td colspan="5" class="pm-modal-empty">
                                        <i class="mdi mdi-account-off-outline"></i>
                                        <span>No leaders found. Try a different search term.</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    name: 'PastorSelectorModal',
    data() {
        return {
            show: false,
            search: '',
            leaders: [],
            loading: false,
            error: null,
            debounceTimer: null,
        };
    },
    methods: {
        open() {
            this.show = true;
            this.search = '';
            this.error = null;
            document.body.style.overflow = 'hidden';
            this.fetchLeaders();
            this.$nextTick(() => {
                if (this.$refs.searchInput) this.$refs.searchInput.focus();
            });
        },
        close() {
            this.show = false;
            document.body.style.overflow = '';
        },
        fetchLeaders() {
            // Debounce search requests
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this._doFetch();
            }, 250);
        },
        async _doFetch() {
            this.loading = true;
            this.error = null;
            try {
                const params = this.search ? '?search=' + encodeURIComponent(this.search) : '';
                const res = await fetch('/leaders-directory/search' + params);
                if (!res.ok) throw new Error('Failed to load leaders');
                this.leaders = await res.json();
            } catch (e) {
                this.error = e.message || 'Unable to load leaders. Please try again.';
                this.leaders = [];
            }
            this.loading = false;
        },
        selectPastor(leader) {
            window.dispatchEvent(new CustomEvent('pastor-selected', { detail: leader }));
            this.close();
        }
    },
    mounted() {
        this._openHandler = () => this.open();
        this._escHandler = (e) => {
            if (e.key === 'Escape' && this.show) this.close();
        };
        window.addEventListener('open-pastor-selector', this._openHandler);
        document.addEventListener('keydown', this._escHandler);
    },
    beforeUnmount() {
        window.removeEventListener('open-pastor-selector', this._openHandler);
        document.removeEventListener('keydown', this._escHandler);
        if (this.show) document.body.style.overflow = '';
    }
};
</script>

<style>
/* Modal Overlay */
.pm-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(3px);
    display: flex; align-items: center; justify-content: center;
    z-index: 9998; padding: 20px;
}

/* Modal Content */
.pm-modal-content {
    background: #fff; border-radius: 18px;
    width: 100%; max-width: 780px;
    max-height: 85vh; display: flex; flex-direction: column;
    box-shadow: 0 24px 64px rgba(0,0,0,0.15);
    animation: pmModalPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.2);
    overflow: hidden;
}

@keyframes pmModalPop {
    from { transform: scale(0.9) translateY(20px); opacity: 0; }
    to { transform: scale(1) translateY(0); opacity: 1; }
}

/* Header */
.pm-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1px solid #f0f4f8; flex-shrink: 0;
}
.pm-modal-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 16px; font-weight: 700; color: #1a1f36;
}
.pm-modal-title i { font-size: 20px; color: #2449d8; }
.pm-modal-close {
    width: 32px; height: 32px; border-radius: 8px;
    border: none; background: #f5f7fa; color: #8898aa;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; cursor: pointer; transition: all 0.2s;
}
.pm-modal-close:hover { background: #eef4ff; color: #2449d8; }

/* Body */
.pm-modal-body { padding: 20px 24px; overflow-y: auto; flex: 1; }

/* Search */
.pm-modal-search-wrap { position: relative; margin-bottom: 16px; }
.pm-modal-search-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    font-size: 18px; color: #8898aa; pointer-events: none;
}
.pm-modal-search {
    width: 100%; height: 44px; padding: 0 14px 0 42px;
    border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc;
    font-size: 14px; color: #1a1f36; transition: 0.25s;
}
.pm-modal-search:focus {
    border-color: #28a745; background: #fff;
    box-shadow: 0 0 0 3px rgba(40,167,69,0.12); outline: none;
}

/* Loading */
.pm-modal-loading {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    padding: 40px 0; color: #8898aa; font-size: 14px;
}
.pm-modal-spinner {
    width: 20px; height: 20px; border: 2.5px solid #e2e8f0;
    border-top-color: #28a745; border-radius: 50%;
    animation: pmSpin 0.6s linear infinite;
}
@keyframes pmSpin { to { transform: rotate(360deg); } }

/* Error */
.pm-modal-error {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    padding: 30px 0; color: #e53935; font-size: 14px; text-align: center;
}
.pm-modal-error i { font-size: 28px; }
.pm-modal-retry {
    margin-top: 8px; padding: 6px 16px; border-radius: 8px;
    border: 1px solid #e2e8f0; background: #fff; color: #525f7f;
    font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s;
}
.pm-modal-retry:hover { border-color: #28a745; color: #28a745; }

/* Table */
.pm-modal-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #edf0f5; }
.pm-modal-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.pm-modal-table thead { background: #f8fafc; }
.pm-modal-table thead th {
    padding: 12px 14px; font-size: 12px; font-weight: 700;
    color: #8898aa; text-transform: uppercase; letter-spacing: 0.4px;
    border-bottom: 1px solid #edf0f5;
}
.pm-modal-table tbody tr { border-bottom: 1px solid #f5f7fa; transition: background 0.15s; }
.pm-modal-table tbody tr:last-child { border-bottom: none; }
.pm-modal-table tbody tr:hover { background: #fafcff; }
.pm-modal-table tbody td { padding: 10px 14px; vertical-align: middle; }

/* Photo */
.pm-modal-photo {
    width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
    border: 2px solid #edf0f5;
}

/* Avatar fallback */
.pm-modal-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #2449d8, #5c7cfa);
    color: #fff; font-size: 16px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}

.pm-modal-name { font-weight: 600; color: #1a1f36; }
.pm-modal-position { color: #525f7f; font-size: 13px; }
.pm-modal-org { color: #8898aa; font-size: 12px; }

/* Empty */
.pm-modal-empty {
    text-align: center; padding: 30px 0 !important; color: #8898aa;
}
.pm-modal-empty i { font-size: 28px; display: block; margin-bottom: 6px; color: #c4c9d4; }
.pm-modal-empty span { font-size: 13px; }

/* Select Button */
.pm-modal-select-btn {
    padding: 6px 14px; border-radius: 8px; border: none;
    background: #28a745; color: #fff;
    font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.pm-modal-select-btn:hover {
    background: #1e7e34; transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40,167,69,0.25);
}

/* Transition */
.pm-modal-enter-active, .pm-modal-leave-active { transition: opacity 0.25s; }
.pm-modal-enter-from, .pm-modal-leave-to { opacity: 0; }

/* Responsive */
@media (max-width: 768px) {
    .pm-modal-content { max-width: 100%; border-radius: 14px; }
    .pm-modal-body { padding: 16px; }
    .pm-modal-table { font-size: 13px; }
}
</style>
