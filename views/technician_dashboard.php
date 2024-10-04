<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'technician') {
    header("Location: login.php");
    exit();
}

require_once '../classes/Database.php';
require_once '../classes/User.php';

$db = Database::getInstance();
$conn = $db->getConnection();


$technician = User::getById($_SESSION['user_id']);


$query = "SELECT rb.*, rs.service_name, u.username as customer_name
          FROM repair_bookings rb
          JOIN repair_services rs ON rb.service_id = rs.service_id
          JOIN users u ON rb.user_id = u.user_id
          ORDER BY rb.preferred_date ASC, rb.repair_bookings_id DESC
          LIMIT 5";
$result = $conn->query($query);

if ($result === false) {
    error_log("Database query failed: " . $conn->error);
    $recent_bookings = array();
} else {
    $recent_bookings = $result->fetch_all(MYSQLI_ASSOC);
}


$status_query = "SELECT status, COUNT(*) as count FROM repair_bookings GROUP BY status";
$status_result = $conn->query($status_query);

if ($status_result === false) {
    error_log("Database query failed: " . $conn->error);
    $status_counts = array();
} else {
    $status_counts = array();
    while ($row = $status_result->fetch_assoc()) {
        $status_counts[$row['status']] = $row['count'];
    }
}

// Fetch pending and in-progress bookings
$pending_query = "SELECT rb.*, rs.service_name, u.username as customer_name
                  FROM repair_bookings rb
                  JOIN repair_services rs ON rb.service_id = rs.service_id
                  JOIN users u ON rb.user_id = u.user_id
                  WHERE rb.status IN ('Pending', 'In Progress')
                  ORDER BY rb.preferred_date ASC, rb.repair_bookings_id DESC";
$pending_result = $conn->query($pending_query);

if ($pending_result === false) {
    error_log("Database query failed: " . $conn->error);
    $pending_bookings = array();
} else {
    $pending_bookings = $pending_result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Dashboard - Raytech Advanced Repair Services</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
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

        /* Dropdown menu styles */
        .form-group select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #34495e;
            color: #fff;
            border: 1px solid #fff;
        }

        .form-group select option {
            background-color: #34495e;
            color: #fff;
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

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
                    <li><a href="technician_schedule.php">View All Bookings</a></li>
                    <li><a href="../actions/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="welcome-message">
                <h2>Welcome, <?php echo htmlspecialchars($technician->getUsername()); ?>!</h2>
            </div>

            <section class="dashboard-section">
                <h2>Repair Bookings Overview</h2>
                <div class="status-overview">
                    <div class="status-card">
                        <h3>Pending</h3>
                        <p><?php echo isset($status_counts['Pending']) ? $status_counts['Pending'] : 0; ?></p>
                    </div>
                    <div class="status-card">
                        <h3>In Progress</h3>
                        <p><?php echo isset($status_counts['In Progress']) ? $status_counts['In Progress'] : 0; ?></p>
                    </div>
                    <div class="status-card">
                        <h3>Completed</h3>
                        <p><?php echo isset($status_counts['Completed']) ? $status_counts['Completed'] : 0; ?></p>
                    </div>
                </div>
            </section>



            <section class="dashboard-section">
                <h2>Pending and In-Progress Bookings</h2>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Preferred Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_bookings as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['repair_bookings_id']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['preferred_date']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['status']); ?></td>
                                    <td>
                                        <a href="view_booking.php?id=<?php echo $booking['repair_bookings_id']; ?>"
                                            class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                        <button onclick="openModal(<?php echo $booking['repair_bookings_id']; ?>)"
                                            class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> Update</button>
                                        <form action="../actions/cancel_booking.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="booking_id"
                                                value="<?php echo $booking['repair_bookings_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to cancel this booking?');"><i
                                                    class="fas fa-times"></i> Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <p class="text-center mt-3">
                    <a href="technician_schedule.php" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> View
                        All Bookings</a>
                </p>
            </section>


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
                <input type="hidden" id="repair_bookings_id" name="repair_bookings_id" value="">
                <div class="form-group">
                    <label for="status">New Status:</label>
                    <select id="status" name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
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

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Function to open the modal and set the booking ID
        function openModal(bookingId) {
            document.getElementById("repair_bookings_id").value = bookingId;
            modal.style.display = "block";

            // Set minimum date for rescheduling
            var today = new Date().toISOString().split('T')[0];
            document.getElementById("reschedule_date").setAttribute('min', today);
        }

        // Prevent selection of Sundays
        document.getElementById("reschedule_date").addEventListener("input", function (e) {
            var day = new Date(this.value).getUTCDay();
            if (day == 0) {
                alert('Raytech does not operate on Sundays');
                this.value = '';
            }
        });
    </script>
</body>

</html>