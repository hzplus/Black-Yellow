<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Homeowner') {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serviceId = $_POST['serviceid'];
    $cleanerId = $_POST['cleanerid'];
    $homeownerId = $_SESSION['userid'];
    $bookingDate = $_POST['booking_date'];

    require_once(__DIR__ . '/../../Controller/homeowner/bookServiceController.php');
    
    $success = BookServiceController::handleBooking($homeownerId, $cleanerId, $serviceId, $bookingDate);

    if ($success) {
        header("Location: ViewServiceDetails.php?id=$serviceId&booked=1");
        exit();
    } else {
        echo "<script>alert('Booking failed. Please try again.'); window.history.back();</script>";
    }
    
}
?>
