<!-- Universal Duplicate Modal -->
<div class="modal fade" id="duplicateModal" tabindex="-1" aria-labelledby="duplicateLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="duplicateForm" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="duplicateLabel">Duplicate Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Original Name</label>
                        <input type="text" id="duplicateOriginalName" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Name</label>
                        <input type="text" name="new_name" id="duplicateNewName" class="form-control"
                            placeholder="Enter new name" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Duplicate</button>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to delete <span class="fw-bold" id="deleteItemName"> ?</p>

                <p class="text-muted mb-0">
                    This action can be reverted later.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    Delete
                </button>
            </div>

        </div>
    </div>
</div>

<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
            <div class="text-body">
                ©
                <script>
                    document.write(new Date().getFullYear());
                </script>
                , made with ❤️ by <a href="https://webiknows.com/" target="_blank" class="footer-link">Webiknows IT Solutions Pvt Ltd.</a>
            </div>
        </div>
    </div>
</footer>
