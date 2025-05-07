<?php
require_once(__DIR__ . '/../Entity/Cleaner.php');
require_once(__DIR__ . '/../db/Database.php');

class HomeownerController {
    public function getCleaners() {
        $db = new Database();
        $cleanersData = $db->fetchCleaners(); // Fetch cleaners from DB
        $cleanersArray = [];
        
        foreach ($cleanersData as $cleanerData) {
            $cleaner = new Cleaner(
                $cleanerData['id'],
                $cleanerData['name'],
                $cleanerData['rating'],
                $cleanerData['price'],
                $cleanerData['availability'],
                $cleanerData['services']
            );
            $cleanersArray[] = $cleaner;
        }

        return $cleanersArray;
    }

    public function getCleanerById($id) {
        require_once __DIR__ . '/../Entity/Cleaner.php';
        $result = Database::fetchCleanerById($id);
        if ($result) {
            return new Cleaner(
                $result['id'],
                $result['name'],
                $result['rating'],
                $result['price'],
                $result['availability'],
                $result['services'],
                $result['profile_picture']
            );
        }
        return null;
    }

    public function shortlistCleaner($cleanerId) {
        $db = new Database();
        $db->addCleanerToShortlist($cleanerId);
    }

    public function getServiceHistory($homeownerId) {
        $db = new Database();
        return $db->fetchServiceHistory($homeownerId); // Fetch service history from DB
    }
}
?>