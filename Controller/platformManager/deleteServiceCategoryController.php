<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../Entity/platformManager/ServiceCategory.php';

class DeleteServiceCategoryController {
    
    public function getCategoryById($categoryId) {
        return ServiceCategory::getCategoryById($categoryId);
    }
    
    public function deleteCategory($categoryId) {
        return ServiceCategory::deleteCategory($categoryId);
    }
    
    public function getAllCategories() {
        return ServiceCategory::getAllCategories();
    }
}