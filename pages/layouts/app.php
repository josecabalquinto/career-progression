<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<?php include('./head.php'); ?>

<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Navbar -->
        <?php include('./navbar.php'); ?>
        <!-- /Navbar -->

        <!-- Sidebar -->
        <?php include('./sidebar.php'); ?>
        <!-- /Sidebar -->

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Page Content -->
            <div class="content container-fluid">

                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                $page = $page === 'dashboard' ? $page : str_replace(['..', '/'], '', $page);
                $file = "../$page.php";
                ?>

                <!-- Page Header -->
                <?php if (!isset($_GET['action'])): ?>
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="page-title text-capitalize"><?php echo ($page) ?></h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                    <li class="breadcrumb-item active text-capitalize"><?php echo ($page) ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- /Page Header -->

                <!-- Main Page Content -->
                <?php
                if (file_exists($file)) {
                    include($file);
                } else {
                    include('./404.php');
                }
                ?>
                <!-- Main Page Content -->

            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->


    <?php
    include('./scripts.php');
    if (isset($_SESSION['alert']) && $_SESSION['alert']) {
        $icon = $_SESSION['icon'];
        $title = $_SESSION['title'];
        $message = $_SESSION['message'];
        $url = $_SESSION['url'];
        unset($_SESSION['alert']);
        unset($_SESSION['icon']);
        unset($_SESSION['title']);
        unset($_SESSION['message']);
        unset($_SESSION['url']);
    ?>

        <script>
            Swal.fire({
                icon: '<?= $icon ?>',
                title: '<?= $title ?>!',
                text: '<?php echo htmlspecialchars($message); ?>',
                showConfirmButton: true,
                timer: 3000,
            }).then(() => {
                window.location.href = "<?= $url ?>";
            });
        </script>
    <?php
    }
    ?>
</body>

</html>