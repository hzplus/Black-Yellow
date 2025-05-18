<?php
require_once(__DIR__ . '/../../Entity/service.php');

class CreateServiceController {
    public static function createService($cleanerId, $title, $description, $price, $availability, $category, $image_path) {
        return CleanerService::createService($cleanerId, $title, $description, $price, $availability, $category, $image_path);
    }
    public static function fetchCategories() {
        $service = new CleanerService(null, null, null, null, null, null);
        return $service->getAllCategories();
    }
    
    
}
