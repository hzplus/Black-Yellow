<?php
session_start();
// Check if user is logged in and is a homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'Homeowner') {
    header("Location: ../../login.php");
    exit();
}

// Check if cleaner ID is provided
if (!isset($_POST['cleaner_id'])) {
    header("Location: ../../Boundary/homeowner/searchCleaners.php");
    exit();
}

$cleanerId = $_POST['cleaner_id'];
$homeownerId = $_SESSION['userid'];

// Include controller
require_once '../../Controller/HomeownerController.php';
$homeownerController = new HomeownerController();

// Add cleaner to shortlist
$result = $homeownerController->addToShortlist($homeownerId, $cleanerId);

// Redirect back to the referring page
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../Boundary/homeowner/homeownerDashboard.php';
header("Location: $referer");
exit();
?>