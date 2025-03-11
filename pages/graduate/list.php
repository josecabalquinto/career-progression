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
    <button class="btn btn-sm btn-secondary" type="submit">Apply Filter</button>
    <button class="btn btn-sm btn-danger" id="print" type="button">Download PDF</button>
</form>

<div class="card d-none" id="print_div">
    <div class="card-header bg-primary text-white">
        <h4 class="text-center text-uppercase mb-0 py-2">List of Graduates</h4>
        <h6 class="text-center text-uppercase mb-0 font-monospace">
            <span class="mr-1">Batch: <?= $batch_name ?></span> |
            <span class="ml-1 mr-1">College: <?= $college_name ?></span> |
            <span class="ml-1">Course: <?= $course_name ?></span>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Year Graduated</th>
                        <th>Current Employment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($graduates):
                        foreach ($graduates as $graduate):
                            $nameParts = array_filter([
                                $graduate['prefix'] ?? '',
                                $graduate['first_name'] ?? '',
                                $graduate['middle_name'] ?? '',
                                $graduate['last_name'] ?? '',
                                $graduate['suffix'] ?? ''
                            ]);

                            $name = trim(implode(' ', $nameParts));
                            $company = $graduate['company'];
                            $position = $graduate['position'];
                            $employment = $graduate['position']  ? "<span class='text-muted font-italic'>$position</span> at $company" : "NA";
                    ?>
                            <tr>
                                <td><?= $name ?></td>
                                <td class="text-center"><?= $graduate['batch_name'] ?></td>
                                <td><?= $employment ?></td>
                            </tr>
                        <?php
                        endforeach;
                    else: ?>
                        <tr class="text-center">
                            <td colspan="3" class="text-center text-muted font-italic">No graduates found for the selected filters.</td>
                        </tr>
                    <?php
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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

        document.querySelector("#print").addEventListener("click", function(event) {
            event.preventDefault();

            let pdfContent = document.getElementById("print_div");

            // Remove the 'd-none' class to make it visible
            pdfContent.classList.remove("d-none");

            // Generate the filename with the current date and time
            let now = new Date();
            let formattedDate = now.getFullYear() + "-" +
                String(now.getMonth() + 1).padStart(2, '0') + "-" +
                String(now.getDate()).padStart(2, '0') + "_" +
                String(now.getHours()).padStart(2, '0') + "-" +
                String(now.getMinutes()).padStart(2, '0') + "-" +
                String(now.getSeconds()).padStart(2, '0');

            let filename = `graduates_${formattedDate}.pdf`;

            setTimeout(() => {
                var opt = {
                    margin: 1,
                    filename: filename, // Use dynamically generated filename
                    image: {
                        type: 'jpeg',
                        quality: 1
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'a4',
                        orientation: 'landscape'
                    }
                };

                html2pdf().from(pdfContent).set(opt).save().then(() => {
                    pdfContent.classList.add("d-none");
                });
            }, 100); // Small delay to allow rendering
        });


    });
</script>