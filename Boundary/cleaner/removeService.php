<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Cleaner') {
    header("Location: ../../login.php");
    exit();
}

require_once(__DIR__ . '/../../Controller/cleaner/removeServiceController.php');

if (!isset($_GET['serviceid'])) {
    echo "No service ID provided.";
    exit();
}

$serviceid = $_GET['serviceid'];

// Optional: show confirmation or log action
$success = RemoveServiceController::deleteService($serviceid);

if ($success) {
    // Redirect with success message
    header("Location: serviceListings.php?deleted=1");
    exit();
} else {
    // Redirect with failure message
    header("Location: serviceListings.php?deleted=0");
    exit();
}
