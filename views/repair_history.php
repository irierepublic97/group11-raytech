<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once '../classes/RepairBooking.php';
require_once '../classes/User.php';

$user = User::getById($_SESSION['user_id']);


$bookings = RepairBooking::getAllBookingsForUser($_SESSION['user_id']);



$itemsPerPage = 10;
$totalItems = count($bookings);
$totalPages = ceil($totalItems / $itemsPerPage);

$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$pagedBookings = array_slice($bookings, $offset, $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair History - Repair Shop Booking System</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Repair History</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <?php if (empty($bookings)): ?>
            <p>You have no repair bookings in your history.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Last Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagedBookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking->getRepairBookingsId()); ?></td>
                            <td><?php echo htmlspecialchars($booking->getServiceName()); ?></td>
                            <td><?php echo htmlspecialchars($booking->getStatus()); ?></td>
                            <td><?php echo htmlspecialchars($booking->getCreatedAt()); ?></td>
                            <td><?php echo htmlspecialchars($booking->getUpdatedAt()); ?></td>
                            <td><a href="repair_status.php?id=<?php echo $booking->getRepairBookingsId(); ?>">View Details</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?>">&laquo; Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="current-page"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <p><a href="../views/dashboard.php">Back to Dashboard</a></p>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>