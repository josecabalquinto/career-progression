<div class="row mb-2">
    <div class="col-auto">
        <button type="button" class="btn add-btn" data-toggle="modal" data-target="#addCollegeModal">
            <i class="fa fa-plus"></i> New
        </button>
    </div>
</div>
<div class="row">
    <div class="col-md-12">

        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0 datatable table-hover">
                <thead class="text-uppercase">
                    <tr class="bg-danger text-white">
                        <th>College Name</th>
                        <th>Code</th>
                        <th>Color</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colleges)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Sorry, no records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colleges as $college): ?>
                            <tr>
                                <td class="text-truncate" style="max-width: 450px;">
                                    <?php echo htmlspecialchars($college['name']); ?>
                                </td>
                                <td class="text-truncate" style="max-width: 40px;">
                                    <?php echo htmlspecialchars($college['code']); ?>
                                </td>
                                <td class="text-truncate" style="max-width: 450px;">
                                    <?php echo htmlspecialchars($college['color']); ?>
                                </td>
                                <td class="">
                                    <a href="?page=college&action=show&id=<?php echo $college['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-file"></i>
                                    </a>
                                    <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#editCollegeModal<?php echo $college['id']; ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $college['id']; ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="editCollegeModal<?php echo $college['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editCollegeModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCollegeModalLabel">Edit College</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $college['id']; ?>">
                                                <div class="form-group">
                                                    <label for="name">College Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($college['name']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="code">Code</label>
                                                    <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($college['code']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="color">Color</label>
                                                    <input type="input" class="form-control" id="color" name="color" value="<?php echo htmlspecialchars($college['color']); ?>" required></input>
                                                </div>
                                                <button type="submit" name="update" class="btn btn-primary">Update College</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const collegeId = this.getAttribute('data-id');

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
                    window.location.href = '?page=college&delete_id=' + collegeId;
                }
            });
        });
    });
</script>