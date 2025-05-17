<?php
// Controller/homeowner/MyAccountController.php
require_once __DIR__ . '/../../Entity/MyAccountEntity.php';

class MyAccountController {
    private $entity;
    
    public function __construct() {
        $this->entity = new MyAccountEntity();
    }
    
    /**
     * Get homeowner information by ID
     * 
     * @param int $homeownerId Homeowner ID
     * @return array|null Homeowner data or null if not found
     */
    public function getHomeownerById(int $homeownerId): ?array {
        try {
            return $this->entity->getHomeownerById($homeownerId);
        } catch (Exception $e) {
            error_log("Error getting homeowner: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update homeowner information
     * 
     * @param int $homeownerId Homeowner ID
     * @param string $name New username
     * @param string $email New email
     * @return bool|string Success status or error message
     */
    public function updateHomeowner(int $homeownerId, string $name, string $email) {
        try {
            // Validate inputs
            if (empty($name) || strlen($name) < 3) {
                return "Username must be at least 3 characters.";
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return "Please enter a valid email address.";
            }
            
            // Check if email is already in use
            if ($this->entity->isEmailInUse($email, $homeownerId)) {
                return "Email address is already in use by another account.";
            }
            
            // Update homeowner information
            $result = $this->entity->updateHomeowner($homeownerId, $name, $email);
            
            return $result ? true : "Failed to update profile information.";
        } catch (Exception $e) {
            error_log("Error updating homeowner: " . $e->getMessage());
            return "An error occurred: " . $e->getMessage();
        }
    }
    
    /**
     * Update homeowner password
     * 
     * @param int $homeownerId Homeowner ID
     * @param string $currentPassword Current password for verification
     * @param string $newPassword New password
     * @param string $confirmPassword Confirmation of new password
     * @return bool|string Success status or error message
     */
    public function updatePassword(
        int $homeownerId, 
        string $currentPassword, 
        string $newPassword,
        string $confirmPassword
    ) {
        try {
            // Validate inputs
            if (empty($currentPassword)) {
                return "Current password is required.";
            }
            
            if (empty($newPassword) || strlen($newPassword) < 6) {
                return "New password must be at least 6 characters.";
            }
            
            if ($newPassword !== $confirmPassword) {
                return "New password and confirmation do not match.";
            }
            
            // Verify current password
            if (!$this->entity->verifyPassword($homeownerId, $currentPassword)) {
                return "Current password is incorrect.";
            }
            
            // Update password
            $result = $this->entity->updatePassword($homeownerId, $newPassword);
            
            return $result ? true : "Failed to update password.";
        } catch (Exception $e) {
            error_log("Error updating password: " . $e->getMessage());
            return "An error occurred: " . $e->getMessage();
        }
    }
}