<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../classes/User.php';
require_once '../actions/update_profile.php';

$user = User::getById($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Raytech Advanced Repair Services</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .profile-form {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #e0e0e0;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            background-color: rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
        }
        .btn-update {
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
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
                <img src="../assets/images/logo.png" alt="Raytech Logo">
                <h1>Raytech</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php" class="active">Profile</a></li>
                    <li><a href="../actions/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>User Profile</h2>
            <?php
            if (isset($_SESSION['success'])) {
                echo "<p class='success'>{$_SESSION['success']}</p>";
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>{$_SESSION['error']}</p>";
                unset($_SESSION['error']);
            }
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<p class='error'>$error</p>";
                }
            }
            ?>
            <div class="profile-form">
                <form action="../actions/update_profile.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user->getPhone()); ?>" placeholder="09XXXXXXXXX" pattern="(09|\+639)\d{9}" required>
                    </div>
                    
                    <h3>Change Password</h3>
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                    
                    <button type="submit" class="btn-update">Update Profile</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Raytech Advanced Repair Services. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>