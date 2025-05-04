<?php
require_once '../../db/database.php';
require_once '../../Entity/userProfile.php';

class suspendUserProfileController {
    public function getActiveProfiles() {
        $conn = Database::connect();
        return userProfile::getActiveProfiles($conn);
    }

    public function suspend($ids) {
        $conn = Database::connect();
        return userProfile::suspendProfiles($conn, $ids);
    }
}