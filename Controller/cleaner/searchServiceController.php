<?php
require_once(__DIR__ . '/../../Entity/service.php');

class SearchServiceController {
    public static function searchServicesByTitle($cleanerId, $keyword) {
        // $serviceObj = new CleanerService(null, null, null, null, null, null);
        // return $serviceObj->searchServicesByTitle($cleanerId, $keyword);
        return CleanerService::searchServicesByTitle($cleanerId, $keyword);

    }
}
