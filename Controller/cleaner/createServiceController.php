<?php
session_start();
require_once '../../Entity/service.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cleanerid = $_SESSION['userid'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $available_from = $_POST['available_from'];
    $available_to = $_POST['available_to'];
    $category = $_POST['category'];
    $image_path = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            echo "Image upload failed.";
            exit();
        }
        $image_path = 'assets/images/' . $imageName;
    }

    $result = createService($cleanerid, $title, $description, $price, $available_from, $available_to, $category, $image_path);

    if ($result) {
        header("Location: ../../Boundary/cleaner/serviceListings.php");
        exit();
    } else {
        echo "Failed to create service.";
    }
} else {
    header("Location: ../../Boundary/cleaner/createService.php");
    exit();
}
