<?php
require_once '../../Entity/platformManager/ServiceCategory.php';

class ViewServiceCategoryController {
    
    public function getAllCategories() {
        return ServiceCategory::getAllCategories();
    }
    
    public function getCategoryById($categoryId) {
        return ServiceCategory::getCategoryById($categoryId);
    }
}