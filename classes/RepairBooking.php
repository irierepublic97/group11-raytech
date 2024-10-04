<?php

require_once 'Database.php';

class RepairBooking
{
    private $repair_bookings_id;
    private $user_id;
    private $service_id;
    private $service_name;
    private $description;
    private $preferred_date;
    private $status;
    private $created_at;
    private $completed_date;

    public function __construct($user_id, $service_id, $service_name, $description, $preferred_date, $status = 'Pending')
    {
        $this->user_id = $user_id;
        $this->service_id = $service_id;
        $this->service_name = $service_name;
        $this->description = $description;
        $this->preferred_date = $preferred_date;
        $this->status = $status;
    }

    public function save()
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "INSERT INTO repair_bookings (user_id, service_id, description, preferred_date, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisss", $this->user_id, $this->service_id, $this->description, $this->preferred_date, $this->status);

        if ($stmt->execute()) {
            $this->repair_bookings_id = $stmt->insert_id;
            return true;
        }
        return false;
    }


    public static function getActiveBookingsForUser($user_id)
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "SELECT rb.*, rs.service_name 
                  FROM repair_bookings rb
                  JOIN repair_services rs ON rb.service_id = rs.service_id
                  WHERE rb.user_id = ? AND rb.status != 'Completed' AND rb.status != 'Cancelled'
                  ORDER BY rb.preferred_date ASC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $booking = new self(
                $row['user_id'],
                $row['service_id'],
                $row['service_name'],
                $row['description'],
                $row['preferred_date'],
                $row['status']
            );
            $booking->repair_bookings_id = $row['repair_bookings_id'];
            $booking->created_at = $row['created_at'];
            $bookings[] = $booking;
        }

        return $bookings;
    }

    public static function cancelBooking($repair_bookings_id, $user_id)
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "UPDATE repair_bookings SET status = 'Cancelled' WHERE repair_bookings_id = ? AND user_id = ? AND status != 'Completed'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $repair_bookings_id, $user_id);

        $result = $stmt->execute();
        if (!$result) {
            error_log("Cancel booking query failed: " . $stmt->error);
            return false;
        }

        $affected_rows = $stmt->affected_rows;
        if ($affected_rows === 0) {
            error_log("No rows affected when cancelling booking: repair_bookings_id=$repair_bookings_id, user_id=$user_id");
            return false;
        }

        error_log("Booking cancelled successfully: repair_bookings_id=$repair_bookings_id, user_id=$user_id");
        return true;
    }

    public static function getBookingById($repair_bookings_id)
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "SELECT rb.*, rs.service_name 
                  FROM repair_bookings rb
                  JOIN repair_services rs ON rb.service_id = rs.service_id
                  WHERE rb.repair_bookings_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $repair_bookings_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $booking = new self(
                $row['user_id'],
                $row['service_id'],
                $row['service_name'],
                $row['description'],
                $row['preferred_date'],
                $row['status']
            );
            $booking->repair_bookings_id = $row['repair_bookings_id'];
            $booking->created_at = $row['created_at'];
            return $booking;
        }

        return null;
    }

    public function updateStatus($new_status)
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "UPDATE repair_bookings SET status = ? WHERE repair_bookings_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_status, $this->repair_bookings_id);

        if ($stmt->execute()) {
            $this->status = $new_status;
            return true;
        }
        return false;
    }

    public static function getNewBookings()
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "SELECT rb.*, rs.service_name 
                  FROM repair_bookings rb
                  JOIN repair_services rs ON rb.service_id = rs.service_id
                  WHERE rb.status = 'Pending'
                  ORDER BY rb.preferred_date ASC";
        $result = $conn->query($query);

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $booking = new self(
                $row['user_id'],
                $row['service_id'],
                $row['service_name'],
                $row['description'],
                $row['preferred_date'],
                $row['status']
            );
            $booking->repair_bookings_id = $row['repair_bookings_id'];
            $booking->created_at = $row['created_at'];
            $bookings[] = $booking;
        }

        return $bookings;
    }

    public static function getInProgressBookings()
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "SELECT rb.*, rs.service_name 
                  FROM repair_bookings rb
                  JOIN repair_services rs ON rb.service_id = rs.service_id
                  WHERE rb.status = 'In Progress'
                  ORDER BY rb.preferred_date ASC";
        $result = $conn->query($query);

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $booking = new self(
                $row['user_id'],
                $row['service_id'],
                $row['service_name'],
                $row['description'],
                $row['preferred_date'],
                $row['status']
            );
            $booking->repair_bookings_id = $row['repair_bookings_id'];
            $booking->created_at = $row['created_at'];
            $bookings[] = $booking;
        }

        return $bookings;
    }

    public static function getCompletedBookings()
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "SELECT rb.*, rs.service_name 
                  FROM repair_bookings rb
                  JOIN repair_services rs ON rb.service_id = rs.service_id
                  WHERE rb.status = 'Completed'
                  ORDER BY rb.preferred_date ASC
                  LIMIT 10";
        $result = $conn->query($query);

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $booking = new self(
                $row['user_id'],
                $row['service_id'],
                $row['service_name'],
                $row['description'],
                $row['preferred_date'],
                $row['status']
            );
            $booking->repair_bookings_id = $row['repair_bookings_id'];
            $booking->created_at = $row['created_at'];
            $bookings[] = $booking;
        }

        return $bookings;
    }

    public static function getCompletedBookingsForUser($user_id)
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "SELECT rb.*, rs.service_name 
                  FROM repair_bookings rb
                  JOIN repair_services rs ON rb.service_id = rs.service_id
                  WHERE rb.user_id = ? AND rb.status = 'Completed'
                  ORDER BY rb.completed_date DESC
                  LIMIT 10"; // Limit to the last 10 completed bookings
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $booking = new self(
                $row['user_id'],
                $row['service_id'],
                $row['service_name'],
                $row['description'],
                $row['preferred_date'],
                $row['status']
            );
            $booking->repair_bookings_id = $row['repair_bookings_id'];
            $booking->created_at = $row['created_at'];
            $booking->completed_date = $row['completed_date'];
            $bookings[] = $booking;
        }

        return $bookings;
    }

    public static function getBookingHistoryForUser($user_id)
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "SELECT rb.*, rs.service_name 
                  FROM repair_bookings rb
                  JOIN repair_services rs ON rb.service_id = rs.service_id
                  WHERE rb.user_id = ? AND (rb.status = 'Completed' OR rb.status = 'Cancelled')
                  ORDER BY CASE 
                    WHEN rb.status = 'Completed' THEN rb.completed_date 
                    WHEN rb.status = 'Cancelled' THEN rb.created_at
                  END DESC
                  LIMIT 10"; // Limit to the last 10 completed or cancelled bookings
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $booking = new self(
                $row['user_id'],
                $row['service_id'],
                $row['service_name'],
                $row['description'],
                $row['preferred_date'],
                $row['status']
            );
            $booking->repair_bookings_id = $row['repair_bookings_id'];
            $booking->created_at = $row['created_at'];
            $booking->completed_date = $row['completed_date'];
            $bookings[] = $booking;
        }

        return $bookings;
    }

    public static function rescheduleBooking($repair_bookings_id, $user_id, $new_date)
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "UPDATE repair_bookings 
                  SET preferred_date = ? 
                  WHERE repair_bookings_id = ? AND user_id = ? AND status NOT IN ('Completed', 'Cancelled')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $new_date, $repair_bookings_id, $user_id);

        $result = $stmt->execute();
        if (!$result) {
            error_log("Reschedule booking query failed: " . $stmt->error);
            return false;
        }

        $affected_rows = $stmt->affected_rows;
        if ($affected_rows === 0) {
            error_log("No rows affected when rescheduling booking: repair_bookings_id=$repair_bookings_id, user_id=$user_id");
            return false;
        }

        error_log("Booking rescheduled successfully: repair_bookings_id=$repair_bookings_id, user_id=$user_id, new_date=$new_date");
        return true;
    }

    // Add a setter for preferred_date
    public function setPreferredDate($new_date)
    {
        $this->preferred_date = $new_date;
    }

    // Getter methods
    public function getRepairBookingsId()
    {
        return $this->repair_bookings_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }



    public function getServiceId()
    {
        return $this->service_id;
    }

    public function getServiceName()
    {
        return $this->service_name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPreferredDate()
    {
        return $this->preferred_date;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getCompletedDate()
    {
        return $this->completed_date;
    }

    public function reschedule($new_date)
    {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $query = "UPDATE repair_bookings SET preferred_date = ? WHERE repair_bookings_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_date, $this->repair_bookings_id);

        if ($stmt->execute()) {
            $this->preferred_date = $new_date;
            return true;
        }
        return false;
    }
}