<?php
require_once(__DIR__ . '/../../Entity/service.php');

class RemoveServiceController {
    public static function delete($serviceid) {
        $service = new CleanerService(null, null, null, null, null, null);
        return $service->deleteService($serviceid);
    }
}
