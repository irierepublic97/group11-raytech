<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
require_once '../classes/Database.php';
require_once '../classes/RepairBooking.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'technician') {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repair_bookings_id']) && isset($_POST['status'])) {
    $repair_bookings_id = $_POST['repair_bookings_id'];
    $new_status = $_POST['status'];

    $booking = RepairBooking::getBookingById($repair_bookings_id);

    if ($booking) {
        if ($booking->updateStatus($new_status)) {
            $_SESSION['success'] = "Booking status updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update booking status.";
        }
    } else {
        $_SESSION['error'] = "Booking not found.";
    }

    header("Location: ../views/technician_dashboard.php");
    exit();
} else {
    header("Location: ../views/technician_dashboard.php");
    exit();
}