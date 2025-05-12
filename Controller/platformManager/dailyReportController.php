<?php
require_once '../../db/Database.php';
require_once '../../Entity/platformManager/Report.php';

class DailyReportController {
    
    public function generateReport($date = null) {
        return Report::getDailyReport($date);
    }
}