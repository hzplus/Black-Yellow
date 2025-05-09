<?php
require_once(__DIR__ . '/../../Entity/service.php');

class ViewServiceController {
    public static function getServiceDetails($serviceid) {
        $serviceObj = new CleanerService(null, null, null, null, null, null);
        return $serviceObj->getServiceById($serviceid);
    }
}
