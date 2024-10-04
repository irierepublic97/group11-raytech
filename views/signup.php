<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Raytech Advanced Repair Services</title>
    <link rel="stylesheet" href="../assets/css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="signup-container">
            <center>
            <h2>Create Your Account</h2>
            </center>
            <form action="../actions/process_signup.php" method="post">
                <div class="input-group">
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="input-group">
                    <input type="email" id="email" name="email" required placeholder="Email">
                </div>
                <div class="input-group">
                    <input type="tel" id="phone" name="phone" required placeholder="Phone Number">
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" required placeholder="Password">
                    <i class="fas fa-eye-slash toggle-password"></i>
                </div>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm Password">
                    <i class="fas fa-eye-slash toggle-password"></i>
                </div>
                <button type="submit" class="sign-up-button">Sign Up</button>
            </form>
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            ?>
        </div>
        <div class="login-container">
            <h2>Already have an account?</h2>
            <a href="login.php" class="login-button">Login</a>
        </div>
    </div>
    <script>
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const password = this.previousElementSibling;
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>