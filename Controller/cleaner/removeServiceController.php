<?php
require_once(__DIR__ . '/../../Entity/service.php');

class RemoveServiceController {
    public static function deleteService($serviceid) {
        return CleanerService::deleteService($serviceid);
    }
}
