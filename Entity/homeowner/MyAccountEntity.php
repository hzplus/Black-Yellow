<?php
// Entity/MyAccountEntity.php
require_once __DIR__ . '/../../db/Database.php';

class MyAccountEntity {
    /**
     * Get homeowner information by ID
     * 
     * @param int $homeownerId Homeowner ID
     * @return array|null Homeowner data or null if not found
     */
    public function getHomeownerById(int $homeownerId): ?array {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("
                SELECT userid, username, email
                FROM users
                WHERE userid = ? AND role = 'Homeowner' AND status = 'active'
            ");
            
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $homeowner = $result->fetch_assoc();
                $stmt->close();
                return $homeowner;
            }
            
            $stmt->close();
            return null;
        } catch (\Exception $e) {
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
     * @return bool Success status
     */
    public function updateHomeowner(int $homeownerId, string $name, string $email): bool {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE userid = ?");
            $stmt->bind_param("ssi", $name, $email, $homeownerId);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (\Exception $e) {
            error_log("Error updating homeowner: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify a homeowner's password
     * 
     * @param int $homeownerId Homeowner ID
     * @param string $password Password to verify
     * @return bool Whether password is correct
     */
    public function verifyPassword(int $homeownerId, string $password): bool {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT password FROM users WHERE userid = ?");
            $stmt->bind_param("i", $homeownerId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stmt->close();
                
                // Verify the password
                return password_verify($password, $row['password']);
            }
            
            $stmt->close();
            return false;
        } catch (\Exception $e) {
            error_log("Error verifying password: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update homeowner's password
     * 
     * @param int $homeownerId Homeowner ID
     * @param string $newPassword New password (will be hashed)
     * @return bool Success status
     */
    public function updatePassword(int $homeownerId, string $newPassword): bool {
        try {
            $conn = Database::getConnection();
            
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE userid = ?");
            $stmt->bind_param("si", $hashedPassword, $homeownerId);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (\Exception $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if email is already in use by another user
     * 
     * @param string $email Email to check
     * @param int $excludeUserId User ID to exclude from the check
     * @return bool Whether email is in use
     */
    public function isEmailInUse(string $email, int $excludeUserId): bool {
        try {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("
                SELECT userid FROM users 
                WHERE email = ? AND userid != ?
            ");
            
            $stmt->bind_param("si", $email, $excludeUserId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $isInUse = ($result && $result->num_rows > 0);
            $stmt->close();
            
            return $isInUse;
        } catch (\Exception $e) {
            error_log("Error checking email usage: " . $e->getMessage());
            return false;
        }
    }
}