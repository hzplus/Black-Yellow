<?php
require_once __DIR__ . '/../../Entity/userProfile.php';

class viewUserProfileController {

    /** Get all user profiles */
    public function getAllProfiles(): array {
        return userProfile::getAll();
    }

    /** Get a single profile by ID */
    public function getProfileById(int $id): userProfile {
        return userProfile::getById($id);
    }
}