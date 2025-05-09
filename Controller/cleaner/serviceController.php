<?php
require_once(__DIR__ . '/../../Entity/service.php');

class ServiceController {
    public static function getCleanerServices($cleanerId) {
        $service = new CleanerService(null, null, null, null, null, null);
        return $service->getServicesByCleaner($cleanerId);
    }
}
