<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
require_once '../classes/User.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = User::getById($_SESSION['user_id']);
    
    if (!$user) {
        $_SESSION['error'] = "User not found.";
        header("Location: ../views/profile.php");
        exit();
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    // Validate input
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($phone) || !preg_match("/^(09|\+639)\d{9}$/", $phone)) {
        $errors[] = "Valid phone number is required (format: 09XXXXXXXXX or +639XXXXXXXXX).";
    }

    // If changing password
    if (!empty($new_password)) {
        if (empty($current_password)) {
            $errors[] = "Current password is required to set a new password.";
        } elseif (!$user->verifyPassword($current_password)) {
            $errors[] = "Current password is incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "New password and confirmation do not match.";
        }
    }

    if (empty($errors)) {
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPhone($phone);
        
        if (!empty($new_password)) {
            $user->setPassword($new_password);
        }

        if ($user->save()) {
            $_SESSION['success'] = "Profile updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update profile. Please try again.";
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    
    header("Location: ../views/profile.php");
    exit();
}

// If not a POST request, redirect to profile page
header("Location: ../views/profile.php");
exit();
