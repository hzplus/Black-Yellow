<?php
require_once __DIR__ . '/../../Entity/userProfile.php';

class searchUserProfileController {
    public function search(string $keyword): array {
        return userProfile::searchByRole($keyword);
    }
}