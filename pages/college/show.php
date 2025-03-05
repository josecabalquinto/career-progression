<div class="row">
    <div class="col-md-12">
        <h3><?= $college['name'] ?> <span class="font-italic">(<?= $college['code'] ?>)</span></h3>
        <p class="border p-2 text-muted">
            <?= $college['description'] ?>
        </p>
    </div>
    <div class="col-mg-12">
        <div class="col-auto">
            <button type="button" class="btn add-btn mb-2" data-toggle="modal" data-target="#addCourseModal">
                <i class="fa fa-plus"></i> New
            </button>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0 datatable table-hover">
                <thead class="text-uppercase">
                    <tr class="bg-danger text-white">
                        <th>Course</th>
                        <th>Code</th>
                        <th class="d-none d-sm-table-cell">Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="4" class="text-center">Sorry, no records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td class="text-truncate" style="max-width: 450px;">
                                    <?php echo htmlspecialchars($course['name']); ?>
                                </td>
                                <td class="text-truncate" style="max-width: 40px;">
                                    <?php echo htmlspecialchars($course['code']); ?>
                                </td>
                                <td class="d-none d-sm-table-cell text-truncate" style="max-width: 450px;">
                                    <?php echo htmlspecialchars($course['description']); ?>
                                </td>
                                <td class="">
                                    <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#editCourseModal<?php echo $course['id']; ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $course['id']; ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="editCourseModal<?php echo $course['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                                <div class="form-group">
                                                    <label for="name">Course</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($course['name']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="code">Code</label>
                                                    <input type="text" class="form-control" id="code" name="code" value="<?php echo htmlspecialchars($course['code']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($course['description']); ?></textarea>
                                                </div>
                                                <button type="submit" name="update" class="btn btn-primary">Update Course</button>
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
            const courseId = this.getAttribute('data-id');

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
                    window.location.href = '?page=college&action=show&id=<?= $college_id ?>&delete_id=' + courseId;
                }
            });
        });
    });
</script>