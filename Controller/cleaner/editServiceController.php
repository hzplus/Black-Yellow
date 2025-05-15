<?php
require_once(__DIR__ . '/../../Entity/service.php');

class EditServiceController {
    public static function getServiceById($serviceid) {
        $service = new CleanerService(null, null, null, null, null, null);
        return $service->getServiceById($serviceid);
    }

    public static function updateService($serviceid, $title, $description, $price, $availability, $category, $image_path) {
        $service = new CleanerService(null, null, null, null, null, null);
        return $service->updateService($serviceid, $title, $description, $price, $availability, $category, $image_path);
    }
}

