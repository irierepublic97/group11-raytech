<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
require_once '../classes/Database.php';
require_once '../classes/RepairBooking.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id']) && isset($_POST['new_date'])) {
    $booking_id = $_POST['booking_id'];
    $new_date = $_POST['new_date'];
    $user_id = $_SESSION['user_id'];

    try {
        $result = RepairBooking::rescheduleBooking($booking_id, $user_id, $new_date);
        
        if ($result) {
            $_SESSION['success'] = "Booking rescheduled successfully.";
        } else {
            $_SESSION['error'] = "Failed to reschedule booking. Please try again.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred while rescheduling the booking.";
        error_log("Error in reschedule_booking.php: " . $e->getMessage());
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: ../views/dashboard.php");
exit();