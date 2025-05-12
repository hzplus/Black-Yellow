<?php
// Controller/homeowner/AccountController.php
require_once(__DIR__ . '/../../Entity/homeowner/HomeownerEntity.php');

class AccountController {
    private $homeownerEntity;
    
    public function __construct() {
        $this->homeownerEntity = new HomeownerEntity();
    }
    
    public function getHomeownerById($homeownerId) {
        return $this->homeownerEntity->getHomeownerById($homeownerId);
    }
    
    public function updateHomeowner($homeownerId, $name, $email, $phone, $address) {
        try {
            // We can only update name and email with the existing tables
            // Phone and address are ignored
            return $this->homeownerEntity->updateHomeowner($homeownerId, $name, $email);
        } catch (Exception $e) {
            error_log("Error updating homeowner: " . $e->getMessage());
            return "An error occurred: " . $e->getMessage();
        }
    }
    
    public function updateHomeownerWithPassword($homeownerId, $name, $email, $phone, $address, $currentPassword, $newPassword) {
        try {
            // Verify current password
            $isPasswordValid = $this->homeownerEntity->verifyPassword($homeownerId, $currentPassword);
            
            if (!$isPasswordValid) {
                return "Current password is incorrect.";
            }
            
            // Update user with new password (phone and address are ignored)
            return $this->homeownerEntity->updateHomeownerWithPassword($homeownerId, $name, $email, $newPassword);
        } catch (Exception $e) {
            error_log("Error updating homeowner with password: " . $e->getMessage());
            return "An error occurred: " . $e->getMessage();
        }
    }
}
?>