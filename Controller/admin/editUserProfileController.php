<?php
require_once '../../db/database.php';
require_once '../../Entity/userProfile.php';

class editUserProfileController {
    public function getAllProfiles() {
        $conn = Database::connect();
        return userProfile::getAllProfiles($conn);
    }

    public function getProfileById($id) {
        $conn = Database::connect();
        return userProfile::getById($conn, $id);
    }

    public function updateProfile($id, $role, $description) {
        $conn = Database::connect();
        return userProfile::update($conn, $id, $role, $description);
    }
}
