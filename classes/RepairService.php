<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);



require_once 'Database.php';

class RepairService {
    public static function getAllServices() {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT service_id, service_name, price FROM repair_services ORDER BY service_name");
        $stmt->execute();
        $result = $stmt->get_result();

        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }

        return $services;
    }
}