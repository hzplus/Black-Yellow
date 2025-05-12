<?php
require_once __DIR__ . '/../../Entity/userProfile.php';

class suspendUserProfileController {
    public function suspend(array $profileIds): void {
        foreach ($profileIds as $id) {
            userProfile::suspend((int)$id);
        }
    }

    public function getActiveProfiles(): array {
        $all = userProfile::getAll();
        return array_filter($all, fn($p) => $p->status === 'active');
    }
}
