<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../views/signup.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../views/signup.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: ../views/signup.php");
        exit();
    }

    // Check if username already exists
    $existing_user = User::getByUsername($username);
    if ($existing_user) {
        $_SESSION['error'] = "Username already exists.";
        header("Location: ../views/signup.php");
        exit();
    }

    // Create new user
    $user = new User($username, $email, $phone, $password);
    if ($user->save()) {
        $_SESSION['success'] = "Account created successfully. Please log in.";
        header("Location: ../views/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Error creating account. Please try again.";
        header("Location: ../views/signup.php");
        exit();
    }
} else {
    header("Location: ../views/signup.php");
    exit();
}