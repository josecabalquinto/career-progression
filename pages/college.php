<?php
require_once '../../services/college.php';

$service = new CollegeService();

$method_ = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = null;

switch ($action) {
    case 'show':
        $college_id = $_GET['id'];
        $college = $service->find($college_id);
        if (empty($college)) { ?>
            <script>
                window.location.href = '?page=404';
            </script>
<?php }
        if ($method_ === 'POST') {
            if (isset($_POST['update'])) {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $code = $_POST['code'];
                $description = $_POST['description'];
                $message = $service->update_course($id, $name, $code, $description);
            } else {
                $name = $_POST['name'];
                $code = $_POST['code'];
                $description = $_POST['description'];
                $message = $service->store_course($college_id, $name, $code, $description);
            }
        }

        if (isset($_GET['delete_id'])) {
            $course_id = $_GET['delete_id'];
            $message = $service->destroy_course($course_id);
        }
        $courses = $service->show_courses($college_id);
        break;

    default:
        if ($method_ === 'POST') {
            if (isset($_POST['update'])) {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $code = $_POST['code'];
                $color = $_POST['color'];
                $message = $service->update($id, $name, $code, $color);
            } else {
                $name = $_POST['name'];
                $code = $_POST['code'];
                $color = $_POST['color'];
                $message = $service->store($name, $code, $color);
            }
        }

        if (isset($_GET['delete_id'])) {
            $collegeId = $_GET['delete_id'];
            $message = $service->destroy($collegeId);
        }

        $editCollege = null;
        if (isset($_GET['edit_id'])) {
            $collegeId = $_GET['edit_id'];
            $editCollege = $service->find($collegeId);
        }

        $colleges = $service->list();
        break;
}

?>

<script src="/career_progression/assets/swal/sweetalert2.min.js"></script>
<link rel="stylesheet" href="/career_progression/assets/swal/sweetalert2.min.css">

<?php
include(__DIR__ . '/college/' . $action . '.php');
include(__DIR__ . '/college/modals.php');
?>


<?php if (isset($message)): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Successful!',
            // title: '<?php echo isset($_GET['delete_id']) ? "College Deleted!" : "College Added!"; ?>',
            text: '<?php echo htmlspecialchars($message); ?>',
            showConfirmButton: true,
            timer: 3000,
        });
    </script>
<?php endif; ?>