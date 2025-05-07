<?php
require_once 'db/Database.php';

class AccountController {
    public function updateProfile($homeownerId, $newData) {
        $db = new Database();
        // Assuming updateHomeownerProfile method exists in the DB class
        $db->updateHomeownerProfile($homeownerId, $newData);
    }
}
?>