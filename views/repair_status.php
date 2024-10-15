<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once '../classes/RepairBooking.php';
require_once '../classes/User.php';

$user = User::getById($_SESSION['user_id']);


if (isset($_GET['id'])) {
    $booking = RepairBooking::getById($_GET['id']);
    if (!$booking || $booking->getUserId() != $_SESSION['user_id']) {
        $_SESSION['error'] = "Invalid booking or unauthorized access.";
        header("Location: ../views/dashboard.php");
        exit();
    }
} else {
   
    $bookings = RepairBooking::getActiveBookingsForUser($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Status - Repair Shop Booking System</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Repair Status</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <?php if (isset($booking)): ?>
            <h2>Booking #<?php echo htmlspecialchars($booking->getRepairBookingsId()); ?></h2>
            <p><strong>Service:</strong> <?php echo htmlspecialchars($booking->getServiceName()); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($booking->getStatus()); ?></p>
            <p><strong>Created at:</strong> <?php echo htmlspecialchars($booking->getCreatedAt()); ?></p>
            <p><strong>Last updated:</strong> <?php echo htmlspecialchars($booking->getUpdatedAt()); ?></p>
        <?php elseif (isset($bookings)): ?>
            <?php if (empty($bookings)): ?>
                <p>You have no active repair bookings.</p>
            <?php else: ?>
                <h2>Your Active Repair Bookings</h2>
                <ul>
                    <?php foreach ($bookings as $booking): ?>
                        <li>
                            Booking #<?php echo htmlspecialchars($booking->getRepairBookingsId()); ?> - 
                            <?php echo htmlspecialchars($booking->getServiceName()); ?> - 
                            Status: <?php echo htmlspecialchars($booking->getStatus()); ?>
                            <a href="?id=<?php echo $booking->getRepairBookingsId(); ?>">View Details</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>

        <p><a href="../views/dashboard.php">Back to Dashboard</a></p>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>