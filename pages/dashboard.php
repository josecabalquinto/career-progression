<?php
require_once '../../services/dashboard.php';
require_once '../../services/batch.php';
$batchService = new BatchService();
$batch_id = null;
if (isset($_POST['batch_id'])) {
    $batch_id = $_POST['batch_id'] == "*" ? null : $_POST['batch_id'];
}
$batches = $batchService->list();

$dashboardService = new DashboardService();
$graduates = $dashboardService->countGraduates($batch_id);
$employed = $dashboardService->countEmployed($batch_id);
$unemployed = $dashboardService->countUnemployed();
$collegesStats = $dashboardService->getCollegesStatistics($batch_id);
$employedByCollege = $dashboardService->getEmployedByCollege($batch_id);
$unemployedByCollege = $dashboardService->getUnemployedByCollege($batch_id);
$byCourses = $dashboardService->getUnemployedByCollegeByCourse($batch_id);
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

                    <!-- <button class="btn btn-sm btn-secondary ml-2 d-flex align-items-center" id="print">
                        <i class="fa fa-print mr-1"></i>
                        Print
                    </button> -->
                </div>
            </div>
        </form>
    </div>
</div>
<div class="display_cont" id="display_cont">
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="row">
                <div class="col-12 mb-3">
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
                <div class="col-12 mb-3">
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
                <div class="col-12 mb-3">
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
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-uppercase">Graduates per College</h3>
                    <div id="bar-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-uppercase">Total Employed Per College</h3>
                    <div id="tepc-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-uppercase">Total Unemployed Per College</h3>
                    <div id="tupc-charts"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php foreach ($byCourses as $college): ?>
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header" style="background-color: <?= $college['color'] ?>;">
                        <h5 class="text-uppercase mb-0 text-center"><?= htmlspecialchars($college['name']) ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th class="text-center">Employed</th>
                                        <th class="text-center">Unemployed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($college['courses'] as $course): ?>
                                        <tr>
                                            <td class="text-truncate" style="max-width: 400px;"><?= htmlspecialchars($course['name']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($course['total_employed']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($course['total_unemployed']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>
<script src="/career_progression/assets/plugins/morris/morris.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const collegeData = <?= json_encode($collegesStats) ?>;
        const employedByCollege = <?= json_encode($employedByCollege) ?>;
        const unemployedByCollege = <?= json_encode($unemployedByCollege) ?>;


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

        Morris.Bar({
            element: 'tepc-charts',
            data: employedByCollege.map(college => ({
                y: college.code,
                total: college.total
            })),
            xkey: 'y',
            ykeys: ['total'],
            labels: ['Total'],
            barColors: function(row) {
                return barColors[row.label] || "#000000"; // Use college-specific color
            },
            resize: true,
            redraw: true
        });

        Morris.Bar({
            element: 'tupc-charts',
            data: unemployedByCollege.map(college => ({
                y: college.code,
                total: college.total
            })),
            xkey: 'y',
            ykeys: ['total'],
            labels: ['Total'],
            barColors: function(row) {
                return barColors[row.label] || "#000000"; // Use college-specific color
            },
            resize: true,
            redraw: true
        });

        document.querySelector("#print").addEventListener("click", function(event) {
            event.preventDefault();

            let pdfContent = document.getElementById("display_cont");
            let elementsToModify = pdfContent.querySelectorAll(".col-md-6");

            let options = {
                margin: 5,
                filename: 'dashboard_report.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4, // Higher scale for better clarity
                    useCORS: true,
                    allowTaint: true,
                    logging: false,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: pdfContent.scrollWidth + 50, // Force full width
                    windowHeight: pdfContent.scrollHeight + 50 // Force full height
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a3', // Use A3 for larger content
                    orientation: 'portrait' // Change to "landscape" if needed
                }
            };

            setTimeout(() => {
                html2pdf().set(options).from(pdfContent).save();
            }, 2000);
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