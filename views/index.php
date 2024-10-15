<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raytech Advanced Repair Services</title>
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../assets/images/logo.jpg" alt="Raytech Logo">
            <h1>Raytech</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#" id="contactLink">Contact Us</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="../views/dashboard.php">Dashboard</a></li>
                    <li><a href="../actions/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="cta-button">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2>RAYTECH ADVANCED REPAIR SERVICES</h2>
            <p>Expert repair services for all your tech needs.</p>
            <a href="dashboard.php" class="cta-button">Book Now</a>
        </section>

        <section id="about">
            <h2>About Us</h2>
            <center><p>Raytech Advanced Repair Services is your trusted partner for all tech repairs. With our expert technician and state-of-the-art equipment, we ensure your devices are in good hands.</p></center>
            </section>

        <section id="services">
            <h2>Our Services</h2>
            <div class="service-grid">
                <div class="service-item">
                    <i class="fas fa-mobile-alt"></i>
                    <h3>Phone Repair</h3>
                    <p>Fast and reliable repairs for all smartphone brands</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-laptop"></i>
                    <h3>Computer Repair</h3>
                    <p>Expert solutions for desktop and laptop issues</p>
                </div>
                <div class="service-item">
                    <i class="fas fa-tablet-alt"></i>
                    <h3>Tablet Repair</h3>
                    <p>Comprehensive repair services for all tablet devices</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Raytech Advanced Repair Services. All rights reserved.</p>
    </footer>

    <div id="contactModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-logo">
                <img src="../assets/images/logo.jpg" alt="Raytech Logo">
            </div>
            <h2>Raytech Advanced <br> Repair Services</h2>
           
            <div class="contact-info">
            <p>Contact us for more information!</p>
                <p><i class="fas fa-envelope"></i> info@raytech.com</p>
                <p><i class="fas fa-phone"></i> +639 262 352 430</p>
            </div>
        </div>
    </div>

    <script src="../assets/js/modal.js"></script>
</body>
</html>