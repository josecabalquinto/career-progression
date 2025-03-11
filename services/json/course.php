<?php
require_once '../college.php';


header('Content-Type: application/json');

if (!isset($_GET['college_id'])) {
    echo json_encode([]);
    exit;
}

$college_id = $_GET['college_id'] != '*' ? $_GET['college_id'] : null;
$collegeService = new CollegeService();
$courses = $collegeService->show_courses($college_id);

echo json_encode($courses);
exit;
