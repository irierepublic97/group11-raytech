<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/RepairBooking.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'technician') {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repair_bookings_id'])) {
    $repair_bookings_id = $_POST['repair_bookings_id'];
    $new_status = $_POST['status'];
    $reschedule_date = $_POST['reschedule_date'];

    $booking = RepairBooking::getBookingById($repair_bookings_id);

    if ($booking) {
        $updated = false;

        if ($booking->updateStatus($new_status)) {
            $updated = true;
        }

        if (!empty($reschedule_date)) {
            if ($booking->reschedule($reschedule_date)) {
                $updated = true;
            }
        }

        if ($updated) {
            $_SESSION['success'] = "Booking updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update booking.";
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
