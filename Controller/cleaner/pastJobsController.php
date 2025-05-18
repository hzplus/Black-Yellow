<?php
require_once(__DIR__ . '/../../Entity/ConfirmedMatch.php');

class PastJobsController {
    public static function fetchJobs($cleanerId, $category = null, $startDate = null, $endDate = null) {
        $matchObj = new ConfirmedMatch(null, null, null, null, null);
        return $matchObj->getConfirmedMatchesByCleaner($cleanerId, $category, $startDate, $endDate);
    }
}
