<?php
require_once '../../db/database.php';
require_once '../../Entity/userProfile.php';

class createUserProfileController {
    public function createProfile($name, $description) {
        $conn = Database::connect();
        return userProfile::create($conn, $name, $description);
    }
}
