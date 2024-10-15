<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = User::authenticate($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user->getUserId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['user_role'] = $user->getUserRole();

        if ($user->getUserRole() == 'technician') {
            header("Location: ../views/technician_dashboard.php");
        } else {
            header("Location: ../views/index.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: ../views/login.php");
        exit();
    }
} else {
    header("Location: ../views/login.php");
    exit();
}