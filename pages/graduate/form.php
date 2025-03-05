<?php
if ($method_ === 'POST') {
    $graduateService = new GraduatesService();

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
    $res = $graduateService->store(
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
        $course_id,
        $company,
        $position,
        $start_date,
        $end_date
    );
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-0">
            <div class="card-header">
                <h4 class="card-title my-3 text-uppercase text-center">Graduate form</h4>
            </div>
            <div class="card-body">
                <form action="?action=form&page=graduate" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-uppercase text-info">Personal Details</h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Prefix:</label>
                                        <input type="text" name="prefix" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name: *</label>
                                        <input type="text" name="first_name" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Middle Name:</label>
                                        <input type="text" name="middle_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name: *</label>
                                        <input type="text" name="last_name" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Suffix:</label>
                                        <input type="text" name="suffix" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender:</label>
                                        <select class="select" name="gender">
                                            <option>Select an option</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email: *</label>
                                        <input type="text" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone:</label>
                                        <input type="text" name="number" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>City/Municipality: *</label>
                                        <select class="form-control" name="city_code" id="city_code">
                                            <option value=null>Select city/municipality</option>
                                            <?php foreach ($cities as $city): ?>
                                                <option value="<?= $city['code']; ?>"><?= $city['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="city" id="city">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Barangay: *</label>
                                        <select class="form-control" name="barangay_code" id="barangay_code">
                                            <option>Select barangay</option>
                                        </select>
                                        <input type="hidden" name="barangay" id="barangay">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address line: *</label>
                                        <input type="text" name="address_line1" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="workExperienceContainer" class="col-md-6">
                            <div class="">
                                <h4 class="text-uppercase text-info">eDUCATION</h4>
                                <hr>
                                <div class="form-group">
                                    <label>Batch: *</label>
                                    <select class="select" name="batch_id">
                                        <?php foreach ($batches as $batch): ?>
                                            <option value="<?= $batch['id']; ?>" <?= ($batch_id == $batch['id']) ? 'selected' : ''; ?>><?= $batch['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>College: *</label>
                                    <select class="form-control" name="college_id" id="college_id">
                                        <option>Select college</option>
                                        <?php foreach ($colleges as $college): ?>
                                            <option value="<?= $college['id']; ?>" <?= ($college_id == $college['id']) ? 'selected' : ''; ?>><?= $college['code'] ?> - <?= $college['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-5">
                                    <label>Course: *</label>
                                    <select class="form-control" name="course_id" id="course_id">
                                        <option>Select course</option>
                                    </select>
                                </div>


                                <h4 class="text-uppercase text-info">Work details</h4>
                                <hr>
                                <div class="work-experience border p-3 rounded" style="background-color:rgb(237, 238, 238);">
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
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>