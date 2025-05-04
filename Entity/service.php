<?php
require_once __DIR__ . '/../db/Database.php';

function getServicesByCleaner($cleanerId) {
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM services WHERE cleanerid = ?");
    $stmt->bind_param("i", $cleanerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $services = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $services;
}

function createService($cleanerId, $title, $description, $price, $available_from, $available_to, $category, $image_path) {
    $conn = Database::getConnection();
    $stmt = $conn->prepare("INSERT INTO services (cleanerid, title, description, price, available_from, available_to, category, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdssss", $cleanerId, $title, $description, $price, $available_from, $available_to, $category, $image_path);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function searchServicesByTitle($cleanerid, $keyword) {
    $conn = Database::getConnection();
    $keyword = "%" . $keyword . "%";
    $stmt = $conn->prepare("SELECT * FROM services WHERE cleanerid = ? AND (title LIKE ? OR category LIKE ?)");
    $stmt->bind_param("iss", $cleanerid, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    $services = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $services;
}

function getServiceById($serviceid) {
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM services WHERE serviceid = ?");
    $stmt->bind_param("i", $serviceid);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();
    return $service;
}

?>
