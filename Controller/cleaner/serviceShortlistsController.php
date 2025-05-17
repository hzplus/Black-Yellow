<?php
require_once(__DIR__ . '/../../Entity/service.php');

class ServiceShortlistsController {
    public static function getServiceShortlists($serviceid) {
        $serviceObj = new CleanerService(null, null, null, null, null, null);
        return $serviceObj->getServiceShortlists($serviceid);
    }
}
