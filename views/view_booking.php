<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'technician') {
    header("Location: login.php");
    exit();
}

require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/RepairBooking.php';


if (!isset($_GET['id'])) {
    header("Location: technician_dashboard.php");
    exit();
}

$booking_id = $_GET['id'];
$booking = RepairBooking::getBookingById($booking_id);

if (!$booking) {
    $_SESSION['error'] = "Booking not found.";
    header("Location: technician_dashboard.php");
    exit();
}

$customer = User::getById($booking->getUserId());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booking - Raytech Advanced Repair Services</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .booking-details {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .booking-details h3 {
            margin-top: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .booking-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            color: #fff;
        }
        .booking-table th,
        .booking-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .booking-table th {
            background-color: rgba(255, 255, 255, 0.05);
            font-weight: bold;
        }
        .booking-table tr:last-child td {
            border-bottom: none;
        }
        .booking-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .action-buttons {
            margin-top: 20px;
        }
        .action-buttons .btn {
            margin-right: 10px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #2c3e50;
            color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
        }

        .modal-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group input[type="date"] {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #fff;
            background-color: #34495e;
            color: #fff;
        }

        .form-group input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        .btn-update {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-update:hover {
            background-color: #2980b9;
        }
    </style>
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
                    <li><a href="technician_dashboard.php">Dashboard</a></li>
                    <li><a href="technician_schedule.php">View All Bookings</a></li>
                    <li><a href="../actions/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Booking Details</h2>
            <div class="booking-details">
                <h3>Repair Booking #<?php echo htmlspecialchars($booking->getRepairBookingsId()); ?></h3>
                <table class="booking-table">
                    <tr>
                        <th>Customer</th>
                        <td><?php echo htmlspecialchars($customer->getUsername()); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($customer->getEmail()); ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo htmlspecialchars($customer->getPhone()); ?></td>
                    </tr>
                    <tr>
                        <th>Service</th>
                        <td><?php echo htmlspecialchars($booking->getServiceName()); ?></td>
                    </tr>
                    <tr>
                        <th>Preferred Date</th>
                        <td><?php echo htmlspecialchars($booking->getPreferredDate()); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo htmlspecialchars($booking->getStatus()); ?></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td><?php echo htmlspecialchars($booking->getCreatedAt()); ?></td>
                    </tr>
                    <?php if ($booking->getStatus() === 'Completed'): ?>
                    <tr>
                        <th>Completed Date</th>
                        <td><?php echo htmlspecialchars($booking->getCompletedDate()); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Description</th>
                        <td><?php echo htmlspecialchars($booking->getDescription()); ?></td>
                    </tr>
                </table>
                <div class="action-buttons">
                    <button onclick="openModal(<?php echo $booking->getRepairBookingsId(); ?>)" class="btn btn-primary"><i class="fas fa-edit"></i> Update Status</button>
                    <?php if ($booking->getStatus() !== 'Completed' && $booking->getStatus() !== 'Cancelled'): ?>
                    <form action="../actions/cancel_booking.php" method="POST" style="display: inline;">
                        <input type="hidden" name="booking_id" value="<?php echo $booking->getRepairBookingsId(); ?>">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?');"><i class="fas fa-times"></i> Cancel Booking</button>
                    </form>
                    <?php endif; ?>
                    <a href="technician_dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Raytech Advanced Repair Services. All rights reserved.</p>
        </div>
    </footer>

    <!-- Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Update Booking</h2>
            </div>
            <form id="updateForm" action="../actions/update_booking.php" method="POST">
                <input type="hidden" id="repair_bookings_id" name="repair_bookings_id" value="<?php echo $booking->getRepairBookingsId(); ?>">
                <div class="form-group">
                    <label for="status">New Status:</label>
                    <select id="status" name="status" required>
                        <option value="Pending" <?php echo $booking->getStatus() === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo $booking->getStatus() === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo $booking->getStatus() === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reschedule_date">Reschedule Date:</label>
                    <input type="date" id="reschedule_date" name="reschedule_date">
                </div>
                <button type="submit" class="btn-update">Update Booking</button>
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("updateModal");

        // Get the button that opens the modal
        var btn = document.querySelector(".btn-primary");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Set minimum date for rescheduling
        var today = new Date().toISOString().split('T')[0];
        document.getElementById("reschedule_date").setAttribute('min', today);

        // Prevent selection of Sundays
        document.getElementById("reschedule_date").addEventListener("input", function(e) {
            var day = new Date(this.value).getUTCDay();
            if (day == 0) {
                alert('Sundays are not allowed');
                this.value = '';
            }
        });
    </script>
</body>
</html>