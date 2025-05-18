<?php
// Fix the path to Database.php - go up two levels from Entity/platformManager/
require_once __DIR__ . '/../../db/Database.php';

class ServiceCategory {
    public $categoryId;
    public $name;
    public $description;
    public $createdAt;
    public $updatedAt;

    public function __construct($categoryId = null, $name = null, $description = null, $createdAt = null, $updatedAt = null) {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Get all categories
    public static function getAllCategories() {
        $conn = Database::getConnection();
        
        // First check if description column exists
        $checkQuery = "SHOW COLUMNS FROM service_categories LIKE 'description'";
        $checkResult = $conn->query($checkQuery);
        
        if ($checkResult && $checkResult->num_rows == 0) {
            // If description column doesn't exist, only select name and id
            $stmt = $conn->prepare("SELECT categoryid, name FROM service_categories ORDER BY name ASC");
        } else {
            // If description column exists, select all fields
            $stmt = $conn->prepare("SELECT categoryid, name, description, created_at, updated_at FROM service_categories ORDER BY name ASC");
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $categories;
    }

    // Get category by ID
    public static function getCategoryById($categoryId) {
        $conn = Database::getConnection();
        
        // First check if description column exists
        $checkQuery = "SHOW COLUMNS FROM service_categories LIKE 'description'";
        $checkResult = $conn->query($checkQuery);
        
        if ($checkResult && $checkResult->num_rows == 0) {
            // If description column doesn't exist, only select name and id
            $stmt = $conn->prepare("SELECT categoryid, name FROM service_categories WHERE categoryid = ?");
        } else {
            // If description column exists, select all fields
            $stmt = $conn->prepare("SELECT categoryid, name, description, created_at, updated_at FROM service_categories WHERE categoryid = ?");
        }
        
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();
        $stmt->close();
        return $category;
    }

    // Create new category
    public static function createCategory($name, $description = null) {
        $conn = Database::getConnection();
        
        // First check if description column exists
        $checkQuery = "SHOW COLUMNS FROM service_categories LIKE 'description'";
        $checkResult = $conn->query($checkQuery);
        
        $currentDateTime = date('Y-m-d H:i:s');
        
        if ($checkResult && $checkResult->num_rows == 0) {
            // If description column doesn't exist, only insert name
            $stmt = $conn->prepare("INSERT INTO service_categories (name) VALUES (?)");
            $stmt->bind_param("s", $name);
        } else {
            // Check if created_at and updated_at columns exist
            $timeColumnsQuery = "SHOW COLUMNS FROM service_categories LIKE 'created_at'";
            $timeColumnsResult = $conn->query($timeColumnsQuery);
            
            if ($timeColumnsResult && $timeColumnsResult->num_rows == 0) {
                // If time columns don't exist, only insert name and description
                $stmt = $conn->prepare("INSERT INTO service_categories (name, description) VALUES (?, ?)");
                $stmt->bind_param("ss", $name, $description);
            } else {
                // If all columns exist, insert all data
                $stmt = $conn->prepare("INSERT INTO service_categories (name, description, created_at, updated_at) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $description, $currentDateTime, $currentDateTime);
            }
        }
        
        $success = $stmt->execute();
        $categoryId = $success ? $conn->insert_id : null;
        $stmt->close();
        
        return $categoryId;
    }

    // Update category
    public static function updateCategory($categoryId, $name, $description = null) {
        $conn = Database::getConnection();
        
        // First check if description column exists
        $checkQuery = "SHOW COLUMNS FROM service_categories LIKE 'description'";
        $checkResult = $conn->query($checkQuery);
        
        $currentDateTime = date('Y-m-d H:i:s');
        
        if ($checkResult && $checkResult->num_rows == 0) {
            // If description column doesn't exist, only update name
            $stmt = $conn->prepare("UPDATE service_categories SET name = ? WHERE categoryid = ?");
            $stmt->bind_param("si", $name, $categoryId);
        } else {
            // Check if updated_at column exists
            $timeColumnQuery = "SHOW COLUMNS FROM service_categories LIKE 'updated_at'";
            $timeColumnResult = $conn->query($timeColumnQuery);
            
            if ($timeColumnResult && $timeColumnResult->num_rows == 0) {
                // If updated_at column doesn't exist, update name and description
                $stmt = $conn->prepare("UPDATE service_categories SET name = ?, description = ? WHERE categoryid = ?");
                $stmt->bind_param("ssi", $name, $description, $categoryId);
            } else {
                // If all columns exist, update all fields
                $stmt = $conn->prepare("UPDATE service_categories SET name = ?, description = ?, updated_at = ? WHERE categoryid = ?");
                $stmt->bind_param("sssi", $name, $description, $currentDateTime, $categoryId);
            }
        }
        
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Delete category
    public static function deleteCategory($categoryId) {
        $conn = Database::getConnection();
        
        // First check if any services are using this category
        $checkServicesQuery = "SELECT COUNT(*) as count FROM services WHERE category = (SELECT name FROM service_categories WHERE categoryid = ?)";
        $checkStmt = $conn->prepare($checkServicesQuery);
        $checkStmt->bind_param("i", $categoryId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkRow = $checkResult->fetch_assoc();
        $checkStmt->close();
        
        // If services are using this category, don't delete
        if ($checkRow['count'] > 0) {
            return false;
        }
        
        // Delete the category
        $stmt = $conn->prepare("DELETE FROM service_categories WHERE categoryid = ?");
        $stmt->bind_param("i", $categoryId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Search categories
    public static function searchCategories($keyword) {
        $conn = Database::getConnection();
        $keyword = "%" . $keyword . "%";
        
        // First check if description column exists
        $checkQuery = "SHOW COLUMNS FROM service_categories LIKE 'description'";
        $checkResult = $conn->query($checkQuery);
        
        if ($checkResult && $checkResult->num_rows == 0) {
            // If description column doesn't exist, only search by name
            $stmt = $conn->prepare("SELECT categoryid, name FROM service_categories WHERE name LIKE ? ORDER BY name ASC");
            $stmt->bind_param("s", $keyword);
        } else {
            // If description column exists, search by name and description
            $stmt = $conn->prepare("SELECT categoryid, name, description, created_at, updated_at FROM service_categories WHERE name LIKE ? OR description LIKE ? ORDER BY name ASC");
            $stmt->bind_param("ss", $keyword, $keyword);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $categories;
    }

    // Check if category exists by name
    public static function categoryExistsByName($name, $excludeId = null) {
        $conn = Database::getConnection();
        
        if ($excludeId) {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM service_categories WHERE name = ? AND categoryid != ?");
            $stmt->bind_param("si", $name, $excludeId);
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM service_categories WHERE name = ?");
            $stmt->bind_param("s", $name);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] > 0;
    }
    
    // Add missing columns if needed
    public static function ensureColumnsExist() {
        $conn = Database::getConnection();
        
        // Check if table exists
        $tableExistsQuery = "SHOW TABLES LIKE 'service_categories'";
        $tableExists = $conn->query($tableExistsQuery)->num_rows > 0;
        
        // If table doesn't exist, create it
        if (!$tableExists) {
            $createTableQuery = "
                CREATE TABLE `service_categories` (
                    `categoryid` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(100) NOT NULL,
                    `description` text DEFAULT NULL,
                    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`categoryid`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            ";
            
            $conn->query($createTableQuery);
            return true;
        }
        
        // Check for description column
        $checkDescQuery = "SHOW COLUMNS FROM service_categories LIKE 'description'";
        $descExists = $conn->query($checkDescQuery)->num_rows > 0;
        
        // Check for created_at column
        $checkCreatedQuery = "SHOW COLUMNS FROM service_categories LIKE 'created_at'";
        $createdExists = $conn->query($checkCreatedQuery)->num_rows > 0;
        
        // Check for updated_at column
        $checkUpdatedQuery = "SHOW COLUMNS FROM service_categories LIKE 'updated_at'";
        $updatedExists = $conn->query($checkUpdatedQuery)->num_rows > 0;
        
        // Add missing columns as needed
        if (!$descExists) {
            $conn->query("ALTER TABLE service_categories ADD COLUMN description TEXT NULL AFTER name");
        }
        
        if (!$createdExists) {
            $conn->query("ALTER TABLE service_categories ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
        }
        
        if (!$updatedExists) {
            $conn->query("ALTER TABLE service_categories ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        }
        
        return true;
    }
}