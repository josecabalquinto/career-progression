<?php
require_once __DIR__ . '/../../services/locationsAPI.php';
header('Content-Type: application/json');

if (!isset($_GET['city_code'])) {
    echo json_encode([]);
    exit;
}

$code = $_GET['city_code'];
$location = new LocationsAPI();
$barangays = $location->getBarangays($code);

echo json_encode($barangays);
exit;
