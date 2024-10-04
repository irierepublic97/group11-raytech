<?php

class Database {
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $db_name = "raytech";
    private $username = "root";
    private $password = "";

    private function __construct() {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            $this->conn->set_charset("utf8");
        } catch(Exception $e) {
            echo "Connection error: " . $e->getMessage();
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}