<?php
require_once '../../Entity/service.php';

if (!isset($_GET['serviceid'])) {
    die("Service ID missing.");
}

$serviceid = intval($_GET['serviceid']);
$service = getServiceById($serviceid);

if (!$service) {
    die("Service not found.");
}

session_start();
$_SESSION['view_service'] = $service;

header("Location: ../../Boundary/cleaner/viewService.php");
exit();
