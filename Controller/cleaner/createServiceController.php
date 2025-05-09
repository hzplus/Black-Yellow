<?php
require_once(__DIR__ . '/../../Entity/service.php');

class CreateServiceController {
    public static function handleCreateRequest($cleanerId, $title, $description, $price, $availability, $category, $image_path) {
        $service = new CleanerService(null, $cleanerId, $title, $price, $description, $availability);
        $result = $service->createService($cleanerId, $title, $description, $price, $availability, $category, $image_path);

        if ($result) {
            header("Location: ../../Boundary/cleaner/serviceListings.php");
            exit();
        } else {
            echo "Failed to create service.";
        }
    }
}
