<?php
// Entity/ShortlistEntity.php

require_once __DIR__ . '/../../db/Database.php';

class ShortlistEntity {
    public function getShortlistedCleanersCount($homeownerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT COUNT(*) as count
            FROM shortlists
            WHERE homeownerid = ?
        ");
        
        $stmt->bind_param("i", $homeownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
            $stmt->close();
            return $count;
        }
        
        $stmt->close();
        return 0;
    }
    
    public function isShortlisted($cleanerId, $homeownerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT shortlistid 
            FROM shortlists 
            WHERE homeownerid = ? AND cleanerid = ?
        ");
        
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $isShortlisted = ($result && $result->num_rows > 0);
        $stmt->close();
        
        return $isShortlisted;
    }
    
    public function addToShortlist($homeownerId, $cleanerId) {
        $conn = Database::getConnection();
        
        // Check if already shortlisted
        if ($this->isShortlisted($cleanerId, $homeownerId)) {
            return true;
        }
        
        // Add to shortlist
        $stmt = $conn->prepare("INSERT INTO shortlists (homeownerid, cleanerid) VALUES (?, ?)");
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
    
    public function removeFromShortlist($homeownerId, $cleanerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM shortlists WHERE homeownerid = ? AND cleanerid = ?");
        $stmt->bind_param("ii", $homeownerId, $cleanerId);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
    
    public function getShortlistedCleanerIds($homeownerId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT cleanerid FROM shortlists WHERE homeownerid = ?");
        $stmt->bind_param("i", $homeownerId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $shortlistedIds = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $shortlistedIds[] = $row['cleanerid'];
            }
        }
        
        $stmt->close();
        return $shortlistedIds;
    }
}