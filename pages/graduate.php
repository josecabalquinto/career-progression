<?php
require_once '../../services/graduate.php';
require_once '../../services/batch.php';
require_once '../../services/college.php';
require_once '../../services/locationsAPI.php';
require_once '../../services/address.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$graduatesService = new GraduatesService();
$method_ = $_SERVER['REQUEST_METHOD'];
$batchService = new BatchService();
$collegeService = new CollegeService();
$locationsAPIService = new LocationsAPI();
$addressService = new AddressService();

$batch_id = $_POST['batch_id'] ?? null;
$college_id = $_POST['college'] ?? null;
$course_id = $_POST['course'] ?? null;
$keyword = $_POST['keyword'] ?? null;

$graduates = $graduatesService->list($batch_id, $college_id, $course_id, $keyword);
$batches = $batchService->list();
$colleges = $collegeService->list();
$courses = $collegeService->show_courses($college_id);
$cities = $locationsAPIService->getCities('064500000');

$batch_name = "All";
if ($batch_id) {
    $filteredBatch = array_filter($batches, fn($batch) => $batch['id'] == $batch_id);
    $batch_name = !empty($filteredBatch) ? reset($filteredBatch)['name'] : "All";
}
$college_name = "All";
if ($batch_id) {
    $filteredCollege = array_filter($colleges, fn($college) => $college['id'] == $college_id);
    $college_name = !empty($filteredCollege) ? reset($filteredCollege)['name'] : "All";
}
$course_name = "All";
if ($batch_id) {
    $filteredCourse = array_filter($courses, fn($course) => $course['id'] == $course_id);
    $course_name = !empty($filteredCourse) ? reset($filteredCourse)['name'] : "All";
}

$is_uploading = false;
$title = 'Info';
$icon = 'info';
$message = 'No action.';

include(__DIR__ . '/graduate/' . $action . '.php');
include(__DIR__ . '/graduate/modals.php');


?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const college = document.getElementById("college_id");
        const course = document.getElementById("course_id");
        college.addEventListener("change", function() {
            const collegeId = this.value;
            const url = `../../services/json/course.php?college_id=${collegeId}`;

            course.innerHTML = '<option>Select course</option>';

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Fetched courses:", data); // Debugging output
                    data.forEach(courseData => {
                        let option = document.createElement("option");
                        option.value = courseData.id;
                        option.textContent = `${courseData.code} - ${courseData.name}`;
                        course.appendChild(option);
                    });
                })
                .catch(error => console.error("Error fetching courses:", error));
        });

        const city_code = document.getElementById("city_code");
        const barangay_code = document.getElementById("barangay_code");
        const city = document.getElementById("city");
        const barangay = document.getElementById("barangay");
        city_code.addEventListener("change", function() {
            const code = this.value
            const name = this.options[this.selectedIndex].text;
            if (code != 'null') {
                city.value = name
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
                            barangay_code.appendChild(option);
                        });
                    })
                    .catch(error => console.error("Error fetching barangays:", error));
            } else {
                barangay_code.innerHTML = '<option>Select barangay</option>';
            }
        });

        barangay_code.addEventListener("change", function() {
            const code = this.value
            const name = this.options[this.selectedIndex].text;
            if (code != 'null') {
                barangay.value = name
            } else {
                alert('Barangay is required!')
            }
        });
    });
</script>