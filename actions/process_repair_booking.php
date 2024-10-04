<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
require_once '../classes/RepairBooking.php';
require_once '../classes/RepairService.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'];
    $description = $_POST['description'];
    $preferred_date = $_POST['preferred_date'];

    // Fetch service name
    $services = RepairService::getAllServices();
    $service_name = '';
    foreach ($services as $service) {
        if ($service['service_id'] == $service_id) {
            $service_name = $service['service_name'];
            break;
        }
    }

    if (empty($service_name)) {
        $_SESSION['error'] = "Invalid service selected.";
        header("Location: ../views/repair_booking_form.php");
        exit();
    }

    $booking = new RepairBooking($user_id, $service_id, $service_name, $description, $preferred_date);
    if ($booking->save()) {
        $_SESSION['success'] = "Repair booking created successfully.";
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to create repair booking. Please try again.";
        header("Location: ../views/repair_booking_form.php");
        exit();
    }
} else {
    header("Location: ../views/repair_booking_form.php");
    exit();
}