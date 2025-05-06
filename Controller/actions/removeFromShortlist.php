<?php
session_start();
// Check if user is logged in and is a homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'Homeowner') {
    header("Location: ../../login.php");
    exit();
}

// Check if cleaner ID is provided
if (!isset($_POST['cleaner_id'])) {
    header("Location: ../../Boundary/homeowner/shortlist.php");
    exit();
}

$cleanerId = $_POST['cleaner_id'];
$homeownerId = $_SESSION['userid'];

// Include controller
require_once '../../Controller/HomeownerController.php';
$homeownerController = new HomeownerController();

// Remove cleaner from shortlist
$result = $homeownerController->removeFromShortlist($homeownerId, $cleanerId);

// Redirect back to the referring page
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../Boundary/homeowner/shortlist.php';
header("Location: $referer");
exit();
?>