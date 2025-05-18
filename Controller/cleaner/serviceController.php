<?php
require_once(__DIR__ . '/../../Entity/service.php');

class ServiceController {
    public static function getServicesByCleaner($cleanerId) {
        return CleanerService::getServicesByCleaner($cleanerId);
    }
}