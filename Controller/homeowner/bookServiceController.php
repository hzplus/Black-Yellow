<?php
require_once(__DIR__ . '/../../Entity/Homeowner.php');

class BookServiceController {
    public static function handleBooking($homeownerId, $cleanerId, $serviceId, $bookingDate) {
        $service = new CleaningPlatformEntity();
        return $service->saveBooking($homeownerId, $cleanerId, $serviceId, $bookingDate);
    }
}
