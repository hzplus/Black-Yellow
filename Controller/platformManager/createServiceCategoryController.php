<?php
require_once '../../db/Database.php';
require_once '../../Entity/platformManager/ServiceCategory.php';

class CreateServiceCategoryController {
    
    public function categoryExists($name) {
        return ServiceCategory::categoryExistsByName($name);
    }
    
    public function createCategory($name, $description) {
        // Ensure required columns exist
        ServiceCategory::ensureColumnsExist();
        
        return ServiceCategory::createCategory($name, $description);
    }
}