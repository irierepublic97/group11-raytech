<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once '../classes/RepairService.php';
require_once '../classes/User.php';


$repairServices = RepairService::getAllServices();

$user = User::getById($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Repair - Raytech Advanced Repair Services</title>
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
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="../actions/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="dashboard-section">
                <h2>Book a Repair</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='error'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo "<p class='success'>{$_SESSION['success']}</p>";
                    unset($_SESSION['success']);
                }
                ?>
                <form action="../actions/process_repair_booking.php" method="post" class="booking-form">
                    <div class="form-group">
                        <label for="service_id">Repair Service:</label>
                        <select id="service_id" name="service_id" required>
                            <option value="">Select a service</option>
                            <?php foreach ($repairServices as $service): ?>
                                <option value="<?php echo htmlspecialchars($service['service_id']); ?>">
                                    <?php echo htmlspecialchars($service['service_name']); ?> - 
                                    Php<?php echo htmlspecialchars($service['price']); ?> (Estimated Charge)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Problem Description:</label>
                        <textarea id="description" name="description" rows="4" required placeholder="Please provide a detailed description of the problem here."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="preferred_date">Preferred Service Date:</label>
                        <input type="date" id="preferred_date" name="preferred_date" required>
                    </div>

                    <button type="submit" class="btn btn-submit">Submit</button>
                </form>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Raytech Advanced Repair Services. All rights reserved.</p>
        </div>
    </footer>

    <script src="../assets/js/dashboard.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const preferredDateInput = document.getElementById('preferred_date');
    
    const today = new Date().toISOString().split('T')[0];
    preferredDateInput.setAttribute('min', today);

    preferredDateInput.addEventListener('input', function(e) {
        const selectedDate = new Date(this.value);
        const dayOfWeek = selectedDate.getUTCDay();
        
        if (dayOfWeek === 0 || selectedDate < new Date(today)) {
            this.value = '';
            alert('Raytech is not available on Sundays. Please pick a different date.');
        }
    });

    document.getElementById('repairBookingForm').addEventListener('submit', function(e) {
        const selectedDate = new Date(preferredDateInput.value);
        const dayOfWeek = selectedDate.getUTCDay();
        
        if (dayOfWeek === 0 || selectedDate < new Date(today)) {
            e.preventDefault();
            alert('Please select a valid future date that is not a Sunday.');
        }
    });
});
</script>
</body>
</html>