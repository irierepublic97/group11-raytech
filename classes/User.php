<?php

require_once 'Database.php';

class User {
    private $user_id;
    private $username;
    private $email;
    private $phone;
    private $password_hash;
    private $user_role;

    public function __construct($username, $email, $phone, $password = '', $user_role = 'customer') {
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        if ($password !== '') {
            $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
        }
        $this->user_role = $user_role;
    }

    public function save() {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        if ($this->user_id) {
            // Update existing user
            $query = "UPDATE users SET username = ?, email = ?, phone = ?, password_hash = ?, user_role = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssi", $this->username, $this->email, $this->phone, $this->password_hash, $this->user_role, $this->user_id);
        } else {
            // Insert new user
            $query = "INSERT INTO users (username, email, phone, password_hash, user_role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $this->username, $this->email, $this->phone, $this->password_hash, $this->user_role);
        }
        
        return $stmt->execute();
    }

    public static function getByUsername($username) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user_data = $result->fetch_assoc();
            $user = new self($user_data['username'], $user_data['email'], $user_data['phone'], '', $user_data['user_role']);
            $user->user_id = $user_data['user_id'];
            $user->password_hash = $user_data['password_hash'];
            return $user;
        }
        
        return null;
    }

    public static function getById($user_id) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user_data = $result->fetch_assoc();
            $user = new self($user_data['username'], $user_data['email'], $user_data['phone'], '', $user_data['user_role']);
            $user->user_id = $user_data['user_id'];
            $user->password_hash = $user_data['password_hash'];
            return $user;
        }
        
        return null;
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password_hash);
    }

    public static function authenticate($username, $password) {
        $user = self::getByUsername($username);
        if ($user && $user->verifyPassword($password)) {
            return $user;
        }
        return null;
    }

    // Getter methods
    public function getUserId() {
        return $this->user_id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getUserRole() {
        return $this->user_role;
    }

    // Setter methods
    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setPassword($password) {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }
}