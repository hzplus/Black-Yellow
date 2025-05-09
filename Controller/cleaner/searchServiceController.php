<?php
require_once(__DIR__ . '/../../Entity/service.php');

class SearchServiceController {
    public static function search($cleanerId, $keyword) {
        $serviceObj = new CleanerService(null, null, null, null, null, null);
        return $serviceObj->searchServicesByTitle($cleanerId, $keyword);
    }
}
