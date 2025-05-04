<?php
require_once '../../db/database.php';
require_once '../../Entity/userProfile.php';

class searchUserProfileController {
    public function search($keyword) {
        $conn = Database::connect();
        return userProfile::search($conn, $keyword);
    }
}
?>