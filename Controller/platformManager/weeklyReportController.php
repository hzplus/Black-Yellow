<?php
require_once '../../db/Database.php';
require_once '../../Entity/platformManager/Report.php';

class WeeklyReportController {
    
    public function generateReport($date = null) {
        return Report::getWeeklyReport($date);
    }
}