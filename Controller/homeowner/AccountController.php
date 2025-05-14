<?php
require_once(__DIR__ . '/../../Entity/Homeowner.php');

class AccountController {
    private $platformEntity;
    
    public function __construct() {
        $this->platformEntity = new CleaningPlatformEntity();
    }
    
    public function getHomeownerById($homeownerId) {
        return $this->platformEntity->getHomeownerById($homeownerId);
    }
    
    public function updateHomeowner($homeownerId, $name, $email, $phone, $address) {
        try {
            // We can only update name and email with the existing tables
            // Phone and address are ignored
            return $this->platformEntity->updateHomeowner($homeownerId, $name, $email);
        } catch (Exception $e) {
            error_log("Error updating homeowner: " . $e->getMessage());
            return "An error occurred: " . $e->getMessage();
        }
    }
    
    public function updateHomeownerWithPassword($homeownerId, $name, $email, $phone, $address, $currentPassword, $newPassword) {
        try {
            // Verify current password
            $isPasswordValid = $this->platformEntity->verifyPassword($homeownerId, $currentPassword);
            
            if (!$isPasswordValid) {
                return "Current password is incorrect.";
            }
            
            // Update user with new password (phone and address are ignored)
            return $this->platformEntity->updateHomeownerWithPassword($homeownerId, $name, $email, $newPassword);
        } catch (Exception $e) {
            error_log("Error updating homeowner with password: " . $e->getMessage());
            return "An error occurred: " . $e->getMessage();
        }
    }
}
?>