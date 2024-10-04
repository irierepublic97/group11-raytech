<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Raytech Advanced Repair Services</title>
    <link rel="stylesheet" href="../assets/css/signin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    
    <div class="container">
        <div class="login-container">
        <center><?php
            if (isset($_SESSION['success'])) {
                echo "<p class='success'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            ?></center>
            <div class="logo">
                <img src="../assets/images/logo.jpg" alt="Raytech Logo">
                <h1>Raytech Advanced <br> Repair Services</h1>
            </div>
            <h2>Login</h2>
            <form action="../actions/process_login.php" method="post">
                <div class="input-group">
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" required placeholder="Password">
                    <i class="fas fa-eye-slash toggle-password"></i>
                </div>
                <button type="submit" class="sign-in-button">Login</button>
            </form>
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            ?>
        </div>
        <div class="signup-container">
            <h2>New Here?</h2>
            <a href="signup.php" class="sign-up-button">Sign Up</a>
        </div>
    </div>
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const password = document.querySelector('#password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>