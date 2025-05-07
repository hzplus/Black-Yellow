<?php
class ServiceHistory {
    public $serviceId;
    public $homeownerId;
    public $cleanerId;
    public $dateTime;
    public $pricePaid;
    public $summary;

    public function __construct($serviceId, $homeownerId, $cleanerId, $dateTime, $pricePaid, $summary) {
        $this->serviceId = $serviceId;
        $this->homeownerId = $homeownerId;
        $this->cleanerId = $cleanerId;
        $this->dateTime = $dateTime;
        $this->pricePaid = $pricePaid;
        $this->summary = $summary;
    }
}
?>