<?php
require_once(__DIR__ . '/../../Entity/ConfirmedMatch.php');

class SearchPastJobsController {
    public static function search($cleanerId, $keyword = '') {
        $matchObj = new ConfirmedMatch(null, null, null, null, null);
        return $matchObj->searchConfirmedMatchesByCleaner($cleanerId, $keyword);
    }
}
