<?php
// Entity/homeowner/HomeownerEntity.php

require_once __DIR__ . '/../../db/Database.php';

class HomeownerEntity {
    public function getHomeownerById($homeownerId) {
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
            $row = $result->fetch_assoc();
            $stmt->close();
            
            // Create and return a Homeowner object
            return new Homeowner($row);
        }
        
        $stmt->close();
        return null;
    }
    
    public function updateHomeowner($homeownerId, $name, $email) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE userid = ?");
        $stmt->bind_param("ssi", $name, $email, $homeownerId);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    public function updateHomeownerWithPassword($homeownerId, $name, $email, $newPassword) {
        $conn = Database::getConnection();
        
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE userid = ?");
        $stmt->bind_param("sssi", $name, $email, $hashedPassword, $homeownerId);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    public function verifyPassword($homeownerId, $password) {
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
    }
}

// Simple data structure class for a homeowner
class Homeowner {
    private $id;
    private $name;
    private $email;
    
    public function __construct($data) {
        $this->id = $data['userid'];
        $this->name = $data['username'];
        $this->email = $data['email'];
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    // These methods are added for compatibility but will return empty strings
    public function getPhone() {
        return '';
    }
    
    public function getAddress() {
        return '';
    }
}