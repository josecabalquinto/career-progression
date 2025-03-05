<?php
require_once '../../services/dashboard.php';
require_once '../../services/batch.php';
$batchService = new BatchService();
$batch_id = $_POST['batch_id'] ?? null;
$batches = $batchService->list();

$dashboardService = new DashboardService();
$graduates = $dashboardService->countGraduates($batch_id);
$employed = $dashboardService->countEmployed($batch_id);
$unemployed = $dashboardService->countUnemployed();
$collegesStats = $dashboardService->getCollegesStatistics($batch_id);


?>
<div class="row">
    <div class="col-12 col-md-4 col-lg-3">
        <form action="" method="post">
            <div class="form-group">
                <label>Filter by Batch:</label>
                <div class="d-flex">
                    <select class="select form-control" name="batch_id" id="batchSelect">
                        <option value="*">All Batches</option>
                        <?php foreach ($batches as $batch): ?>
                            <option value="<?= $batch['id']; ?>" <?= ($batch_id == $batch['id']) ? 'selected' : ''; ?>>
                                <?= $batch['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-sm btn-primary ml-2 d-flex align-items-center">
                        <i class="fa fa-filter mr-1"></i>
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-12">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                <div class="dash-widget-info">
                    <h3><?= $graduates ?></h3>
                    <span>Graduates</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                <div class="dash-widget-info">
                    <h3><?= $employed ?></h3>
                    <span>Employed</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa fa-user-times"></i></span>
                <div class="dash-widget-info">
                    <h3><?= $unemployed ?></h3>
                    <span>Unemployed</span>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-uppercase">Graduates per College</h3>
                        <div id="bar-charts"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/career_progression/assets/plugins/morris/morris.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const collegeData = <?= json_encode($collegesStats) ?>;

        // Generate colors dynamically
        const barColors = {};
        collegeData.forEach(college => {
            barColors[college.code] = college.color || "#000000"; // Default to black if no color
        });

        Morris.Bar({
            element: 'bar-charts',
            data: collegeData.map(college => ({
                y: college.code,
                total: college.total_graduates,
                employed: college.employed,
                unemployed: college.unemployed
            })),
            xkey: 'y',
            ykeys: ['total'],
            labels: ['Total Graduates', 'Employed', 'Unemployed'],
            barColors: function(row) {
                return barColors[row.label] || "#000000"; // Use college-specific color
            },
            resize: true,
            redraw: true
        });
    });
</script>

<!-- <div class="row">
    <div class="col-12 col-md-6 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <h4 class="card-title">College of Information and Communication Technology Engineering</h4>
                <div class="statistics">
                    <div class="row">
                        <div class="col-md-6 col-6 text-center">
                            <div class="stats-box mb-4 shadow">
                                <p>Employed</p>
                                <h3>385</h3>
                            </div>
                        </div>
                        <div class="col-md-6 col-6 text-center">
                            <div class="stats-box mb-4 shadow">
                                <p>Unemployed</p>
                                <h3>19</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p>Bachelor of Science in Information Technology <span class="float-right">166</span></p>
                    <hr>
                    <p>Bachelor of Science in Information System <span class="float-right">115</span></p>
                </div>
            </div>
        </div>
    </div>


</div> -->