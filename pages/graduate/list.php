<div class="row mb-3">
    <div class="col-auto">
        <a href="?action=form&page=graduate" class="btn add-btn"><i class="fa fa-plus"></i> New</a>
    </div>
</div>

<!-- HTML Form with Filters -->
<form method="POST" class="shadow p-3 border">
    <div class="row filter-row mb-2">
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus select-focus">
                <select class="form-control floating" name="batch_id">
                    <option value="*">All</option>
                    <?php foreach ($batches as $batch): ?>
                        <option value="<?= $batch['id']; ?>" <?= ($batch_id == $batch['id']) ? 'selected' : ''; ?>><?= $batch['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="focus-label">Batch</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">
                <select class="form-control floating" name="college" id="college-select">
                    <option value="*">All</option>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?= $college['id']; ?>" <?= ($college_id == $college['id']) ? 'selected' : ''; ?>><?= $college['code'] ?> - <?= $college['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="focus-label">College</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">
                <select class="form-control floating" name="course" id="course-select">
                    <option value="*">All</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['id']; ?>" <?= ($course_id == $course['id']) ? 'selected' : ''; ?>><?= $course['code'] ?> - <?= $course['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="focus-label">Course</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">
                <input type="text" class="form-control floating" name="keyword" placeholder="ex: John Doe or johndoe@gmail.com" value="<?= $keyword ?>">
                <label class="focus-label">Search</label>
            </div>
        </div>
    </div>
    <button class="btn btn-secondary" type="submit">Apply Filter</button>
</form>

<div class="row staff-grid-row mt-3 border shadow rounded-lg p-3">
    <?php if ($graduates): ?>
        <?php foreach ($graduates as $graduate):
            $nameParts = array_filter([
                $graduate['prefix'] ?? '',
                $graduate['first_name'] ?? '',
                $graduate['middle_name'] ?? '',
                $graduate['last_name'] ?? '',
                $graduate['suffix'] ?? ''
            ]);

            $name = trim(implode(' ', $nameParts));
        ?>

            <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                <div class="profile-widget">
                    <div class="profile-img">
                        <a href="?page=graduate&action=show&id=<?= $graduate['id'] ?>" class="avatar">
                            <?php
                            $path = $graduate['profile_path'];
                            if ($path == null) {
                            ?>
                                <img alt="" src="/career_progression/assets/img/user.jpg">
                            <?php
                            } else {
                            ?>
                                <img alt="" src="/career_progression/assets/uploads/profiles/<?= $path ?>">
                            <?php
                            }
                            ?>
                        </a>
                    </div>
                    <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="?page=graduate&action=show&id=<?= $graduate['id'] ?>"><?= htmlspecialchars($name); ?></a></h4>
                    <div class="small text-info"><?= htmlspecialchars($graduate['course_name']); ?></div>
                    <div class="small text-muted font-italic">(<?= htmlspecialchars($graduate['batch_name']); ?>)</div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted text-center">No graduates found for the selected filters.</p>
    <?php endif; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const collegeSelect = document.getElementById("college-select");
        const courseSelect = document.getElementById("course-select");

        collegeSelect.addEventListener("change", function() {
            const collegeId = this.value;
            const url = "../../services/json/course.php?college_id=" + collegeId;
            courseSelect.innerHTML = '<option value="*">All</option>';

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Fetched courses:", data); // Debugging output
                    data.forEach(course => {
                        let option = document.createElement("option");
                        option.value = course.id;
                        option.textContent = `${course.code} - ${course.name}`;
                        courseSelect.appendChild(option);
                    });
                })
                .catch(error => console.error("Error fetching courses:", error));
        });
    });
</script>