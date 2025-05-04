<?php
require_once '../../Entity/service.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $available_from = $_POST['available_from'];
    $available_to = $_POST['available_to'];
    $category = $_POST['category'];
    $cleanerid = $_SESSION['userid'];

    $success = createService($cleanerid, $title, $description, $price, $available_from, $available_to, $category);

    if ($success) {
        header("Location: ../../Boundary/cleaner/serviceListings.php");
        exit();
    } else {
        echo "Failed to create service.";
    }
}
