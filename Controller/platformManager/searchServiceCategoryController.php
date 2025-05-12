<?php
require_once '../../db/Database.php';
require_once '../../Entity/platformManager/ServiceCategory.php';

class SearchServiceCategoryController {
    
    public function search($keyword) {
        return ServiceCategory::searchCategories($keyword);
    }
}