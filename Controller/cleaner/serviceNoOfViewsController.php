<?php
require_once(__DIR__ . '/../../Entity/service.php');

class ServiceNoOfViewsController {
    public static function getServiceViews($serviceId) {
        return CleanerService::getServiceViews($serviceId);
    }
}
?>
