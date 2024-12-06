<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/RepairBooking.php';

$booking_id = $_GET['booking_id'];
$booking = RepairBooking::getBookingById($booking_id);

if (!$booking || $booking->getUserId() !== $_SESSION['user_id']) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $description = $_POST['description'];
    $preferred_date = $_POST['preferred_date'];

    // Update the booking details
    $booking->setServiceId($service_id);
    $booking->setDescription($description);
    $booking->setPreferredDate($preferred_date);

    if ($booking->updateStatus($booking->getStatus())) {
        $_SESSION['success'] = "Booking updated successfully!";
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update booking.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Raytech Advanced Repair Services</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="../assets/images/logo.jpg" alt="Raytech Logo">
                <h1>Raytech</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="../views/index.php">Home</a></li>
                    <li><a href="../views/dashboard.php">Dashboard</a></li>
                    <li><a href="../actions/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Edit Booking</h2>

            <?php
            if (isset($_SESSION['success'])) {
                echo "<p class='success'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="service_id">Service:</label>
                    <input type="text" id="service_id" name="service_id"
                        value="<?php echo htmlspecialchars($booking->getServiceId()); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"
                        required><?php echo htmlspecialchars($booking->getDescription()); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="preferred_date">Preferred Date:</label>
                    <input type="date" id="preferred_date" name="preferred_date"
                        value="<?php echo htmlspecialchars($booking->getPreferredDate()); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Booking</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Raytech Advanced Repair Services. All rights reserved.</p