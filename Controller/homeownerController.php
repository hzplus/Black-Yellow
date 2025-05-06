<?php
require_once 'Entity/User.php';
require_once 'Entity/Cleaner.php';
require_once 'Entity/Shortlist.php';
require_once 'Entity/ServiceHistory.php';
require_once 'db/UserDAO.php';
require_once 'db/CleanerDAO.php';
require_once 'db/ShortlistDAO.php';
require_once 'db/HistoryDAO.php';

class HomeownerController {
    private $userDAO;
    private $cleanerDAO;
    private $shortlistDAO;
    private $historyDAO;
    
    public function __construct() {
        $this->userDAO = new UserDAO();
        $this->cleanerDAO = new CleanerDAO();
        $this->shortlistDAO = new ShortlistDAO();
        $this->historyDAO = new HistoryDAO();
    }
    
    /**
     * Get homeowner information
     * 
     * @param int $userId The homeowner's user ID
     * @return array Homeowner information
     */
    public function getHomeownerInfo($userId) {
        return $this->userDAO->getUserById($userId);
    }
    
    /**
     * Update homeowner information
     * 
     * @param int $userId The homeowner's user ID
     * @param string $name The homeowner's name
     * @param string $email The homeowner's email
     * @param string $phone The homeowner's phone number
     * @param string $address The homeowner's address
     * @return bool True if update was successful, false otherwise
     */
    public function updateHomeownerInfo($userId, $name, $email, $phone, $address) {
        return $this->userDAO->updateUserInfo($userId, $name, $email, $phone, $address);
    }
    
    /**
     * Update homeowner password
     * 
     * @param int $userId The homeowner's user ID
     * @param string $currentPassword The homeowner's current password
     * @param string $newPassword The homeowner's new password
     * @return bool|string True if update was successful, error message otherwise
     */
    public function updatePassword($userId, $currentPassword, $newPassword) {
        // Verify current password
        $user = $this->userDAO->getUserById($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            return "Current password is incorrect.";
        }
        
        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->userDAO->updatePassword($userId, $hashedPassword);
    }
    
    /**
     * Get available services for search form
     * 
     * @return array List of available services
     */
    public function getAvailableServices() {
        return $this->cleanerDAO->getAvailableServices();
    }
    
    /**
     * Search for cleaners based on criteria
     * 
     * @param array $services Array of service IDs
     * @param string $day Day of the week
     * @param int $rating Minimum rating
     * @param string $query Search query
     * @return array List of cleaners matching criteria
     */
    public function searchCleaners($services, $day, $rating, $query) {
        return $this->cleanerDAO->searchCleaners($services, $day, $rating, $query);
    }
    
    /**
     * Get cleaner profile information
     * 
     * @param int $cleanerId The cleaner's user ID
     * @return array|bool Cleaner profile information or false if not found
     */
    public function getCleanerProfile($cleanerId) {
        $cleaner = $this->cleanerDAO->getCleanerById($cleanerId);
        if (!$cleaner) {
            return false;
        }
        
        // Get cleaner services
        $cleaner['services'] = $this->cleanerDAO->getCleanerServices($cleanerId);
        
        // Get cleaner reviews
        $cleaner['reviews'] = $this->cleanerDAO->getCleanerReviews($cleanerId);
        
        return $cleaner;
    }
    
    /**
     * Check if a cleaner is shortlisted by the homeowner
     * 
     * @param int $homeownerId The homeowner's user ID
     * @param int $cleanerId The cleaner's user ID
     * @return bool True if shortlisted, false otherwise
     */
    public function isCleanerShortlisted($homeownerId, $cleanerId) {
        return $this->shortlistDAO->isShortlisted($homeownerId, $cleanerId);
    }
    
    /**
     * Add cleaner to homeowner's shortlist
     * 
     * @param int $homeownerId The homeowner's user ID
     * @param int $cleanerId The cleaner's user ID
     * @return bool True if added successfully, false otherwise
     */
    public function addToShortlist($homeownerId, $cleanerId) {
        $shortlist = new Shortlist($homeownerId, $cleanerId);
        return $this->shortlistDAO->addToShortlist($shortlist);
    }
    
    /**
     * Remove cleaner from homeowner's shortlist
     * 
     * @param int $homeownerId The homeowner's user ID
     * @param int $cleanerId The cleaner's user ID
     * @return bool True if removed successfully, false otherwise
     */
    public function removeFromShortlist($homeownerId, $cleanerId) {
        return $this->shortlistDAO->removeFromShortlist($homeownerId, $cleanerId);
    }
    
    /**
     * Get homeowner's shortlisted cleaners
     * 
     * @param int $homeownerId The homeowner's user ID
     * @return array List of shortlisted cleaners
     */
    public function getShortlistedCleaners($homeownerId) {
        return $this->shortlistDAO->getShortlistedCleaners($homeownerId);
    }
    
    /**
     * Search within shortlisted cleaners
     * 
     * @param int $homeownerId The homeowner's user ID
     * @param string $query Search query
     * @param string $searchType Search by name or service
     * @return array Filtered list of shortlisted cleaners
     */
    public function searchShortlistedCleaners($homeownerId, $query, $searchType) {
        return $this->shortlistDAO->searchShortlistedCleaners($homeownerId, $query, $searchType);
    }
    
    /**
     * Get homeowner's service history
     * 
     * @param int $homeownerId The homeowner's user ID
     * @param string $fromDate Start date for filtering (optional)
     * @param string $toDate End date for filtering (optional)
     * @return array List of service history items
     */
    public function getServiceHistory($homeownerId, $fromDate = '', $toDate = '') {
        return $this->historyDAO->getServiceHistory($homeownerId, $fromDate, $toDate);
    }
}
?>