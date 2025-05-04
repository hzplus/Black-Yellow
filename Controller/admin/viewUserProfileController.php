<?php
require_once '../../db/Database.php';
require_once '../../Entity/userProfile.php';

class viewUserProfileController {
    public function getAllProfiles() {
        $conn = Database::connect();
        return userProfile::getAllProfiles($conn);
    }

    public function getProfileById($id) {
        $conn = Database::connect();
        return userProfile::getById($conn, $id);
    }
}