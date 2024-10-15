<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);



session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../classes/Database.php';
require_once '../classes/RepairBooking.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'customer' && $_SESSION['user_role'] !== 'technician')) {
    $_SESSION['error'] = "You must be logged in as a customer or technician to cancel a booking.";
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['user_role'];

    // Add some logging
    error_log("Attempting to cancel booking: booking_id=$booking_id, user_id=$user_id, user_role=$user_role");

    $booking = RepairBooking::getBookingById($booking_id);

    if ($booking) {
        if ($user_role === 'customer' && $booking->getUserId() !== $user_id) {
            $_SESSION['error'] = "You don't have permission to cancel this booking.";
            error_log("Unauthorized cancellation attempt: booking_id=$booking_id, user_id=$user_id, user_role=$user_role");
        } else {
            if ($booking->updateStatus('Cancelled')) {
                $_SESSION['success'] = "Booking cancelled successfully.";
                error_log("Booking cancelled successfully: booking_id=$booking_id, user_id=$user_id, user_role=$user_role");
            } else {
                $_SESSION['error'] = "Failed to cancel booking. Please try again.";
                error_log("Failed to cancel booking: booking_id=$booking_id, user_id=$user_id, user_role=$user_role");
            }
        }
    } else {
        $_SESSION['error'] = "Booking not found.";
        error_log("Booking not found for cancellation: booking_id=$booking_id");
    }
} else {
    $_SESSION['error'] = "Invalid request. Please try again.";
    error_log("Invalid cancel booking request: " . json_encode($_POST));
}

// Redirect based on user role
if ($_SESSION['user_role'] === 'technician') {
    header("Location: ../views/technician_dashboard.php");
} else {
    header("Location: ../views/dashboard.php");
}
exit();