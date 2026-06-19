{{-- Global Delete Confirmation Modal (matches Leaders Directory standard) --}}
<div class="modal fade" id="globalDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #e53935, #ff6b6b);"></div>
            <div class="modal-body p-4 text-center">
                <i class="mdi mdi-alert-circle-outline" style="font-size:48px; color:#e53935;"></i>
                <h5 class="fw-bold mt-3 mb-2" id="globalDeleteTitle">Delete Record</h5>
                <p class="text-muted mb-4" style="font-size:14px;" id="globalDeleteMsg">Are you sure? This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger btn-sm px-4" id="globalDeleteConfirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
