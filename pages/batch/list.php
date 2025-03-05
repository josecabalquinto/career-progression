<div class="row staff-grid-row border p-2">
    <?php if (empty($batches)): ?>
        <div class="col-12 p-2">
            <h3>Sorry, no record found.</h3>
        </div>
    <?php else: ?>
        <?php foreach ($batches as $batch): ?>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                <div class="profile-widget">
                    <div class="profile-img">
                        <a href="profile.php" class="avatar"><img src="/career_progression/assets/img/logo/sunn.jpg" alt=""></a>
                    </div>
                    <div class="dropdown profile-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" data-toggle="modal" data-target="#editBatchModal<?= $batch['id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</button>
                            <button class="dropdown-item delete-btn" data-id="<?php echo $batch['id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</button>
                        </div>
                    </div>
                    <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="profile.php"><?= htmlspecialchars($batch['name']) ?></a></h4>
                    <div class="small text-muted"><?= htmlspecialchars($batch['graduation_date']) ?></div>
                </div>
            </div>
            <div class="modal fade" id="editBatchModal<?= $batch['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editBatchModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBatchModalLabel">Update Batch</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                <input type="hidden" name="id" value="<?php echo $batch['id']; ?>">
                                <div class="form-group">
                                    <label for="name">Batch Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($batch['name']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="graduation_date">Graduation Date</label>
                                    <input type="date" class="form-control" id="graduation_date" name="graduation_date" value="<?= htmlspecialchars($batch['graduation_date']) ?>" required>
                                </div>
                                <button type="submit" name="update" class="btn btn-primary">Update Batch</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>


<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const batchId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?page=batch&delete_id=' + batchId;
                }
            });
        });
    });
</script>