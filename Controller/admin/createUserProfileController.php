<?php
// Controller/admin/createUserProfileController.php

require_once __DIR__ . '/../../Entity/userProfile.php';

class createUserProfileController
{
    public function createProfile($role, $description): bool {
        return userProfile::create($role, $description);
    }
}