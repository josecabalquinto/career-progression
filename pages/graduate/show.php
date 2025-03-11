<?php
$id = $_GET['id'];
$_SESSION['url'] = "?page=graduate&action=show&id=$id";
$graduateService = new GraduatesService();
if (!isset($_GET['id'])) {
    echo '<script>window.location.href = "?page=404";</script>';
    exit();
}

$graduate = $graduateService->find($id);

if (!$graduate) {
    echo '<script>window.location.href = "?page=404";</script>';
    exit();
}

if ($method_ === 'POST') {

    if (isset($_POST['profileForm'])) {
        $graduateService->upload_profile($id, $_FILES['profile_image']);
    } elseif (isset($_POST['updateForm'])) {
        $prefix = $_POST['prefix'] ?? null;
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'] ?? null;
        $last_name = $_POST['last_name'];
        $suffix = $_POST['suffix'] ?? null;
        $gender = $_POST['gender'] != 'Select an option' ? $_POST['gender'] : null;
        $email = $_POST['email'];
        $number = $_POST['number'];
        $city_code = $_POST['city_code'];
        $city = $_POST['city'];
        $barangay_code = $_POST['barangay_code'];
        $barangay = $_POST['barangay'];
        $address_line1 = $_POST['address_line1'];
        $batch_id = $_POST['batch_id'];
        $college_id = $_POST['college_id'];
        $course_id = $_POST['course_id'];
        $company = $_POST['company'] ?? null;
        $position = $_POST['position'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;

        $address = "$address_line1, $barangay, $city";
        $graduateService->update(
            $graduate['id'],
            $graduate['address_id'],
            $batch_id,
            $first_name,
            $last_name,
            $middle_name,
            $email,
            $prefix,
            $suffix,
            $gender,
            $number,
            $address,
            $city_code,
            $city,
            $barangay_code,
            $barangay,
            $address_line1,
            $college_id,
            $course_id
        );
    } elseif (isset($_POST['workForm'])) {
        $company = $_POST['company'];
        $position = $_POST['position'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'] ?? null;
        $id_copy = $_FILES['id_copy'] ?? null;
        $coe_copy = $_FILES['coe_copy'] ?? null;
        $graduateService->add_work_experience($id, $company, $position, $start_date, $end_date, $id_copy, $coe_copy);
    } elseif (isset($_POST['workUpdateForm'])) {
        $company = $_POST['company'];
        $position = $_POST['position'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'] ?? null;
        $work_id = $_POST['work_id'];
        $id_copy = $_FILES['id_copy'] ?? null;
        $coe_copy = $_FILES['coe_copy'] ?? null;
        $graduateService->update_work_experience($work_id, $company, $position, $start_date, $end_date, $id_copy, $coe_copy);
    }
} else {
    if (isset($_GET['delete']) && $_GET['delete'] == "work") {
        $graduateService->delete_work_experience($_GET["delete_id"]);
    }
}

$nameParts = array_filter([
    $graduate['prefix'] ?? '',
    $graduate['first_name'] ?? '',
    $graduate['middle_name'] ?? '',
    $graduate['last_name'] ?? '',
    $graduate['suffix'] ?? ''
]);

$name = trim(implode(' ', $nameParts));

?>

<div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-uppercase" id="updateProfileModalLabel">Update Profile Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="?page=graduate&action=show&id=<?= $id ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-uppercase text-info">Personal Details</h4>
                            <hr>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Prefix:</label>
                                    <input type="text" name="prefix" value="<?= $graduate['prefix'] ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>First Name: *</label>
                                    <input type="text" name="first_name" value="<?= $graduate['first_name'] ?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Middle Name:</label>
                                    <input type="text" name="middle_name" value="<?= $graduate['middle_name'] ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Last Name: *</label>
                                    <input type="text" name="last_name" value="<?= $graduate['last_name'] ?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Suffix:</label>
                                    <input type="text" name="suffix" value="<?= $graduate['suffix'] ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Gender:</label>
                                    <select class="select" name="gender">
                                        <option>Select an option</option>
                                        <option value="male" <?= $graduate['gender'] == "male" ? "selected" : "" ?>>Male</option>
                                        <option value="female" <?= $graduate['gender'] == "female" ? "selected" : "" ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Email: *</label>
                                    <input type="text" name="email" value="<?= $graduate['email'] ?>" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div id="workExperienceContainer" class="col-md-6">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Phone:</label>
                                    <input type="text" name="number" value="<?= $graduate['number'] ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>City/Municipality: *</label>
                                    <select class="form-control" name="city_code" id="city_code">
                                        <option value=null>Select city/municipality</option>
                                        <?php foreach ($cities as $city): ?>
                                            <option value="<?= $city['code']; ?>" <?= $graduate['city_code'] == $city['code'] ? "selected" : "" ?>><?= $city['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="city" id="city" value="<?= $graduate['city'] ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Barangay: *</label>
                                    <select class="form-control" name="barangay_code" id="barangay_code">
                                        <option>Select barangay</option>
                                    </select>
                                    <input type="hidden" name="barangay" id="barangay" value="<?= $graduate['barangay'] ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address line: *</label>
                                    <input type="text" name="address_line1" value="<?= $graduate['address_line1'] ?>" class="form-control">
                                </div>
                            </div>
                            <div class="">
                                <h4 class="text-uppercase text-info">eDUCATION</h4>
                                <hr>
                                <div class="form-group">
                                    <label>Batch: *</label>
                                    <select class="select" name="batch_id">
                                        <?php foreach ($batches as $batch): ?>
                                            <option value="<?= $batch['id']; ?>" <?= ($graduate['batch_id'] == $batch['id']) ? 'selected' : ''; ?>><?= $batch['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>College: *</label>
                                    <select class="form-control" name="college_id" id="college_id">
                                        <option>Select college</option>
                                        <?php foreach ($colleges as $college): ?>
                                            <option value="<?= $college['id']; ?>" <?= ($graduate['college_id'] == $college['id']) ? 'selected' : ''; ?>><?= $college['code'] ?> - <?= $college['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-5">
                                    <label>Course: *</label>
                                    <select class="form-control" name="course_id" id="course_id">
                                        <option>Select course</option>
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= $course['id']; ?>" <?= ($graduate['course_id'] == $course['id']) ? 'selected' : ''; ?>><?= $course['code'] ?> - <?= $course['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <button type="submit" name="updateForm" class="btn btn-primary btn-block">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
                <form method="POST" action="?page=graduate&action=show&id=<?= $id ?>" id="uploadProfileForm" enctype="multipart/form-data">
                    <div class="form-group text-center">
                        <label for="profile_image" class="d-block">Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*" required>
                        <img id="imagePreview" src="" alt="Profile Preview" class="img-thumbnail mt-2 d-none" width="150">
                    </div>
                    <button type="submit" name="profileForm" class="btn btn-primary btn-block">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="workModal" tabindex="-1" role="dialog" aria-labelledby="workModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workModalLabel">Work Experience</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="?page=graduate&action=show&id=<?= $id ?>" id="uploadProfileForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Company:</label>
                        <input type="text" name="company" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Position:</label>
                        <input type="text" name="position" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start:</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>End:</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Company ID:</label>
                        <input type="file" name="id_copy" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Certificate of Employment:</label>
                        <input type="file" name="coe_copy" class="form-control">
                    </div>
                    <button type="submit" name="workForm" class="btn btn-primary btn-block">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("profile_image").addEventListener("change", function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let preview = document.getElementById("imagePreview");
            preview.src = reader.result;
            preview.classList.remove("d-none");
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <?php
                            $path = $graduate['profile_path'];
                            if ($path == null) {
                            ?>
                                <a href="#"><img alt="" src="/career_progression/assets/img/user.jpg"></a>
                            <?php
                            } else {
                            ?>
                                <a href="#"><img alt="" src="/career_progression/assets/uploads/profiles/<?= $path ?>"></a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0"><?= $name ?></h3>
                                    <p class="text-info mb-0"><?= $graduate['course_name'] ?></p>
                                    <h5 class="text-muted"><?= $graduate['batch_name'] ?></h5>
                                    <div class="staff-msg">
                                        <button class="btn btn-custom" data-toggle="modal" data-target="#uploadProfileModal" onclick="setGraduateId(123)">
                                            Change Profile
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text"><a href="#"><?= $graduate['number'] ?? '-' ?></a></div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text"><a href=""><?= $graduate['email'] ?></a></div>
                                    </li>
                                    <li>
                                        <div class="title">Address:</div>
                                        <div class="text text-capitalize"><?= $graduate['address'] ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Gender:</div>
                                        <div class="text text-capitalize"><?= $graduate['gender'] ?></div>
                                    </li>
                                    <!-- <li>
                                        <div class="title">Verified at:</div>
                                        <div class="text"></div>
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-edit"><a data-target="#updateProfileModal" data-toggle="modal" class="edit-icon text-white bg-danger" href="#"><i class="fa fa-pencil"></i></a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card profile-box flex-fill shadow">
    <div class="card-body">
        <h3 class="card-title text-uppercase ml-2">Work Experience
            <button data-toggle="modal" data-target="#workModal" class="btn btn-sm text-white bg-danger float-right px-2 rounded border-0">
                <i class="fa fa-plus"></i>
            </button>
        </h3>
        <div class="experience-box">
            <ul class="experience-list">
                <?php if (!empty($graduate['work_experiences'])): ?>
                    <?php foreach ($graduate['work_experiences'] as $experience): ?>
                        <li>
                            <div class="experience-user">
                                <div class="before-circle"></div>
                            </div>
                            <div class="experience-content">
                                <div class="timeline-content">
                                    <p class="name mb-0 text-success">
                                        <strong><?= htmlspecialchars($experience['position']) ?></strong> at
                                        <em><?= htmlspecialchars($experience['company']) ?></em>
                                    </p>
                                    <p>
                                        <?= date('F j, Y', strtotime($experience['start_date'])) ?> -
                                        <?= $experience['end_date'] ? date('F j, Y', strtotime($experience['end_date'])) : 'Present' ?>
                                    </p>

                                    <div class="files d-flex">
                                        <?php if (!empty($experience['id_copy'])): ?>
                                            <p class="mb-1">
                                                <strong>ID Copy:</strong><br>
                                                <a href="/career_progression/assets/uploads/ids/<?= htmlspecialchars($experience['id_copy']) ?>"
                                                    download="<?= htmlspecialchars($experience['id_copy']) ?>"
                                                    title="Download ID Copy">
                                                    <img src="/career_progression/assets/uploads/ids/<?= htmlspecialchars($experience['id_copy']) ?>"
                                                        alt="ID Copy" class="img-thumbnail" style="max-height: 150px;">
                                                </a>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (!empty($experience['coe_copy'])): ?>
                                            <p class="mb-1 ml-2">
                                                <strong>COE Copy:</strong><br>
                                                <a href="/career_progression/assets/uploads/coe/<?= htmlspecialchars($experience['coe_copy']) ?>"
                                                    download="<?= htmlspecialchars($experience['coe_copy']) ?>"
                                                    title="Download COE Copy">
                                                    <img src="/career_progression/assets/uploads/coe/<?= htmlspecialchars($experience['coe_copy']) ?>"
                                                        alt="COE Copy" class="img-thumbnail" style="max-height: 150px;">
                                                </a>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <small class="d-flex">
                                        <a data-target="#work-<?= $experience['id'] ?>-Modal" data-toggle="modal" class="text-info mr-2">EDIT</a> |
                                        <button data-id="<?= $experience['id'] ?>" class="text-danger ml-2 delete-btn border-0">DELETE</button>
                                    </small>
                                </div>
                            </div>
                        </li>



                        <div class="modal fade" id="work-<?= $experience['id'] ?>-Modal" tabindex="-1" role="dialog" aria-labelledby="work-<?= $experience['id'] ?>-ModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="work-<?= $experience['id'] ?>-ModalLabel">Work Experience</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="?page=graduate&action=show&id=<?= $id ?>" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Company:</label>
                                                <input type="text" name="company" value="<?= $experience['company'] ?>" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Position:</label>
                                                <input type="text" name="position" value="<?= $experience['position'] ?>" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Start:</label>
                                                        <input type="date" name="start_date" value="<?= $experience['start_date'] ?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>End:</label>
                                                        <input type="date" name="end_date" value="<?= $experience['end_date'] ?>" class="form-control">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="work_id" value="<?= $experience['id'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Company ID:</label>
                                                <input type="file" name="id_copy" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Certificate of Employment:</label>
                                                <input type="file" name="coe_copy" class="form-control">
                                            </div>
                                            <button type="submit" name="workUpdateForm" class="btn btn-primary btn-block">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No work experience found.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
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
                    window.location.href = "?page=graduate&action=show&id=<?= $id ?>&delete=work&delete_id=" + id;
                }
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const city_code = document.getElementById("city_code");
        const barangay_code = document.getElementById("barangay_code");
        const barangay = document.getElementById("barangay");

        const code = city_code.value
        // const name = city_code.options[this.selectedIndex].text;
        const url = `../../services/json/locations.php?city_code=${code}`;
        barangay_code.innerHTML = '<option value="null">Select barangay</option>';
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                console.log("Fetched barangays:", data); // Debugging output
                data.forEach(data => {
                    let option = document.createElement("option");
                    option.value = data.code;
                    option.textContent = `${data.name}`;
                    if (data.code === "<?= $graduate['barangay_code'] ?>") {
                        option.selected = true
                    }
                    barangay_code.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching barangays:", error));

    });
</script>