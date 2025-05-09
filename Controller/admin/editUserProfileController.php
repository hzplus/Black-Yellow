<?php
require_once __DIR__ . '/../../Entity/userProfile.php';

class editUserProfileController {

    public function getAllProfiles(): array {
        return userProfile::getAll();
    }

    public function getProfileById(int $id): ?userProfile {
        return userProfile::getById($id);
    }

    public function updateProfile(int $id, string $role, string $description, string $status): bool {
        return userProfile::update($id, $role, $description, $status);
    }
}