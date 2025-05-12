<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../db/Database.php';
require_once '../../Entity/platformManager/ServiceCategory.php';

class EditServiceCategoryController {
    
    public function getCategoryById($categoryId) {
        return ServiceCategory::getCategoryById($categoryId);
    }
    
    public function categoryExistsByName($name, $excludeId) {
        return ServiceCategory::categoryExistsByName($name, $excludeId);
    }
    
    public function updateCategory($categoryId, $name, $description) {
        // Ensure required columns exist
        ServiceCategory::ensureColumnsExist();
        
        return ServiceCategory::updateCategory($categoryId, $name, $description);
    }
    
    public function getAllCategories() {
        return ServiceCategory::getAllCategories();
    }
}