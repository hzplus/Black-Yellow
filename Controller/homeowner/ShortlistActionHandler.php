<?php
// Controller/homeowner/ShortlistActionHandler.php
require_once(__DIR__ . '/ShortlistController.php');

// This script handles AJAX or form submissions for shortlist actions
session_start();

// Check if user is logged in as homeowner
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$controller = new ShortlistController();

// Get POST data
$cleanerId = $_POST['cleaner_id'] ?? 0;
$homeownerId = $_POST['homeowner_id'] ?? 0;
$action = $_POST['action'] ?? '';
$redirectUrl = $_POST['redirect_url'] ?? 'ViewCleanerListings.php';

// Verify data
if (!$cleanerId || !$homeownerId) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

// Check if homeowner_id matches logged in user
if ($homeownerId != $_SESSION['userid']) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Perform action
$success = false;
if ($action === 'add') {
    $success = $controller->addToShortlist($cleanerId, $homeownerId);
} else if ($action === 'remove') {
    $success = $controller->removeFromShortlist($cleanerId, $homeownerId);
}

// Handle response - if AJAX request, return JSON, otherwise redirect
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
    exit();
} else {
    // Add query parameters if they exist in the original URL
    $urlParts = parse_url($redirectUrl);
    $query = isset($urlParts['query']) ? $urlParts['query'] : '';
    $path = $urlParts['path'];
    
    if (!empty($query)) {
        header("Location: $path?$query");
    } else {
        header("Location: $path");
    }
    exit();
}
?>