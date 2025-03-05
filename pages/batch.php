<?php
require_once '../../services/batch.php';
$service = new BatchService();

$method_ = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = null;

switch ($action) {
    case 'show':
        # code...
        break;

    default:
        if ($method_ === 'POST') {
            if (isset($_POST['update'])) {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $graduation_date = $_POST['graduation_date'];
                $message = $service->update($id, $name, $graduation_date);
            } else {
                $name = $_POST['name'];
                $graduation_date = $_POST['graduation_date'];
                $message = $service->store($name, $graduation_date);
            }
        }

        if (isset($_GET['delete_id'])) {
            $batchId = $_GET['delete_id'];
            $message = $service->destroy($batchId);
        }

        $batches = $service->list();
        break;
}
?>

<script src="../assets/swal/sweetalert2.min.js"></script>
<link rel="stylesheet" href="../assets/swal/sweetalert2.min.css">

<div class="row mb-3">
    <div class="col-auto">
        <button class="btn add-btn" data-toggle="modal" data-target="#addBatchModal"><i class="fa fa-plus"></i> New</button>
    </div>
</div>
<?php
include(__DIR__ . '/batch/' . $action . '.php');
include(__DIR__ . '/batch/modals.php');
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