<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/RepairBooking.php';

$user = User::getById($_SESSION['user_id']);
$active_bookings = RepairBooking::getActiveBookingsForUser($_SESSION['user_id']);


$booking_history = RepairBooking::getBookingHistoryForUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Raytech Advanced Repair Services</title>
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="../views/dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="../actions/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="welcome-message">
                <h2>Welcome, <?php echo htmlspecialchars($user->getUsername()); ?>!</h2>
            </div>
            
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

            <section class="dashboard-section">
                <h2>Book a New Repair</h2>
                <p>Need a repair? Book a new service appointment now!</p><br>
                <a href="repair_booking_form.php" class="btn btn-primary"><i class="fas fa-tools"></i> Book a Repair</a>
            </section>

            <section class="dashboard-section">
                <h2>Active Repair Bookings</h2>
                <?php if (empty($active_bookings)): ?>
                    <p>You have no active repair bookings.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Description</th>
                                    <th>Preferred Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($active_bookings as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking->getServiceName()); ?></td>
                                    <td><?php echo htmlspecialchars($booking->getDescription()); ?></td>
                                    <td><?php echo htmlspecialchars($booking->getPreferredDate()); ?></td>
                                    <td><?php echo htmlspecialchars($booking->getStatus()); ?></td>
                                    <td>
                                        <?php if ($booking->getStatus() !== 'Completed' && $booking->getStatus() !== 'Cancelled'): ?>
                                            <div class="action-buttons">
                                                <form action="../actions/reschedule_booking.php" method="post" class="reschedule-form">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking->getRepairBookingsId(); ?>">
                                                    <input type="date" name="new_date" required class="hidden-date-input">
                                                    <button type="button" class="btn btn-primary reschedule-btn" title="Reschedule">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </button>
                                                </form>
                                                <form action="../actions/cancel_booking.php" method="post" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking->getRepairBookingsId(); ?>">
                                                    <button type="submit" class="btn btn-danger" title="Cancel">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>

            <section class="dashboard-section">
                <h2>Booking History</h2>
                <?php if (empty($booking_history)): ?>
                    <p>You have no completed or cancelled repair bookings.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Description</th>
                                    <th>Preferred Date</th>
                                    <th>Status</th>
                                    <th>Completed/Cancelled Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($booking_history as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking->getServiceName()); ?></td>
                                    <td><?php echo htmlspecialchars($booking->getDescription()); ?></td>
                                    <td><?php echo htmlspecialchars($booking->getPreferredDate()); ?></td>
                                    <td><?php echo htmlspecialchars($booking->getStatus()); ?></td>
                                    <td>
                                        <?php
                                        if ($booking->getStatus() == 'Completed') {
                                            echo htmlspecialchars($booking->getCompletedDate());
                                        } elseif ($booking->getStatus() == 'Cancelled') {
                                            echo htmlspecialchars($booking->getCreatedAt());
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
            
            
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Raytech Advanced Repair Services. All rights reserved.</p>
        </div>
    </footer>

    <script src="../assets/js/modal.js"></script>

    <script src="../assets/js/dashboard.js"></script>
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const rescheduleBtns = document.querySelectorAll('.reschedule-btn');
    
    rescheduleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const dateInput = this.closest('form').querySelector('input[type="date"]');
            setupDateInput(dateInput);
            dateInput.showPicker();
        });
    });

    function setupDateInput(dateInput) {
        // Set min date to today
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
        
        // Disable Sundays and past dates
        dateInput.addEventListener('input', function(e) {
            const selectedDate = new Date(this.value);
            const dayOfWeek = selectedDate.getUTCDay();
            
            if (dayOfWeek === 0 || selectedDate < new Date(today)) {
                this.value = '';
                alert('Raytech is not available on Sundays. Please pick a different date.');
            }
        });
    }

    // Prevent form submission if the date is invalid
    const rescheduleForms = document.querySelectorAll('.reschedule-form');
    rescheduleForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const dateInput = this.querySelector('input[type="date"]');
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const dayOfWeek = selectedDate.getUTCDay();
            
            if (dayOfWeek === 0 || selectedDate < today) {
                e.preventDefault();
                alert('Please select a valid future date that is not a Sunday.');
            }
        });
    });
});
</script>
</body>
</html>