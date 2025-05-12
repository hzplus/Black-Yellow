<?php
require_once '../../db/Database.php';
require_once '../../Entity/platformManager/Report.php';

class MonthlyReportController {
    
    public function generateReport($month = null, $year = null) {
        return Report::getMonthlyReport($month, $year);
    }
}