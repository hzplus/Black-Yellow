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
$success = RemoveServiceController::delete($serviceid);

if ($success) {
    header("Location: serviceListings.php");
    exit();
} else {
    echo "Failed to remove service.";
}
