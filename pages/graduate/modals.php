<div class="modal fade" id="uploadProfileModal" tabindex="-1" role="dialog" aria-labelledby="uploadProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadProfileModalLabel">Upload Profile Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="?page=graduate&action=show&id=1" id="uploadProfileForm" enctype="multipart/form-data">
                    <div class="form-group text-center">
                        <label for="profile_image" class="d-block">Profile Image</label>
                        <input type="file" class="form-control-file" id="profile_image" name="profile_image" accept="image/*" required>
                        <img id="imagePreview" src="" alt="Profile Preview" class="img-thumbnail mt-2 d-none" width="150">
                    </div>
                    <input type="hidden" name="graduate_id" id="graduate_id">
                    <button type="submit" class="btn btn-primary btn-block">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>