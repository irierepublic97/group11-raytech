<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'technician') {
    header("Location: login.php");
    exit();
}

require_once '../classes/Database.php';
$db = Database::getInstance();
$conn = $db->getConnection();


$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';


$allowed_sorts = ['repair_bookings_id', 'customer_name', 'service_name', 'preferred_date', 'status', 'created_at'];
if (!in_array($sort, $allowed_sorts)) {
    $sort = 'created_at';
}
if ($order != 'ASC' && $order != 'DESC') {
    $order = 'DESC';
}

// Fetch all repair bookings with sorting
$query = "SELECT rb.*, rs.service_name, u.username as customer_name
          FROM repair_bookings rb
          JOIN repair_services rs ON rb.service_id = rs.service_id
          JOIN users u ON rb.user_id = u.user_id
          ORDER BY ";

// Add table prefix to ambiguous columns
if ($sort == 'service_name') {
    $query .= "rs.service_name $order";
} elseif ($sort == 'customer_name') {
    $query .= "u.username $order";
} else {
    $query .= "rb.$sort $order";
}

$result = $conn->query($query);

if ($result === false) {

    error_log("Database query failed: " . $conn->error);

    die("An error occurred while fetching the bookings. Please try again later.");
}

$bookings = array();
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}


function sortLink($field, $label, $currentSort, $currentOrder)
{
    $newOrder = ($currentSort === $field && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
    $icon = '';
    if ($currentSort === $field) {
        $icon = $currentOrder === 'ASC' ? ' ▲' : ' ▼';
    }
    return "<a href='?sort=$field&order=$newOrder'>$label$icon</a>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Schedule - Raytech Advanced Repair Services</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .table-responsive {
            overflow-x: auto;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            color: #e0e0e0;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: bold;
            color: #ffffff;
        }

        th a {
            color: #ffffff;
            text-decoration: none;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #ffa50033;
            color: #ffd700;
        }

        .status-in-progress {
            background-color: #3498db33;
            color: #87cefa;
        }

        .status-completed {
            background-color: #2ecc7133;
            color: #98fb98;
        }

        .status-cancelled {
            background-color: #e74c3c33;
            color: #ff6b6b;
        }

        .btn-primary {
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="../assets/images/logo.jpg" alt="Raytech Logo">
                <h1>Raytech</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="technician_dashboard.php">Dashboard</a></li>
                    <li><a href="../actions/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>All Repair Bookings</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><?php echo sortLink('repair_bookings_id', 'Booking ID', $sort, $order); ?></th>
                            <th><?php echo sortLink('customer_name', 'Customer', $sort, $order); ?></th>
                            <th><?php echo sortLink('service_name', 'Service', $sort, $order); ?></th>
                            <th>Description</th>
                            <th><?php echo sortLink('preferred_date', 'Preferred Date', $sort, $order); ?></th>
                            <th><?php echo sortLink('status', 'Status', $sort, $order); ?></th>
                            <th><?php echo sortLink('created_at', 'Created At', $sort, $order); ?></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['repair_bookings_id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                <td><?php echo htmlspecialchars(substr($booking['description'], 0, 30)) . (strlen($booking['description']) > 30 ? '...' : ''); ?>
                                </td>
                                <td><?php echo htmlspecialchars($booking['preferred_date']); ?></td>
                                <td>
                                    <span
                                        class="status status-<?php echo strtolower(str_replace(' ', '-', $booking['status'])); ?>">
                                        <?php echo htmlspecialchars($booking['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($booking['created_at']); ?></td>
                                <td>
                                    <a href="view_booking.php?id=<?php echo $booking['repair_bookings_id']; ?>"
                                        class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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