<?php
require_once(__DIR__ . '/../../Entity/ConfirmedMatch.php');

class SearchPastJobsController {
    public static function searchConfirmedMatchesByCleaner($cleanerId, $keyword = '') {
        // $matchObj = new ConfirmedMatch(null, null, null, null, null);
        // return $matchObj->searchConfirmedMatchesByCleaner($cleanerId, $keyword);
        return ConfirmedMatch::searchConfirmedMatchesByCleaner($cleanerId, $keyword);

    }
}
