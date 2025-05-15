<?php
require_once(__DIR__ . '/../../Entity/service.php');


class ViewServiceController {
    public static function getServiceById($serviceid) {
        return CleanerService::getServiceById($serviceid);
    }
}
