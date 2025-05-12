<?php
// Controller/admin/createUserProfileController.php

require_once __DIR__ . '/../../Entity/userProfile.php';

class createUserProfileController
{
    /**
     * @param string $role
     * @param string $description
     * @return bool  True on success, false on failure
     */
    public function createProfile($role, $description) {
        if (userProfile::exists($role)) {
            return false; // or throw Exception
        }
        return userProfile::create($role, $description);
    }
}