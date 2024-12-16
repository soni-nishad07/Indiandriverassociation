<?php
session_start();

// Redirect to signin if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

include 'conn.php'; // Database connection

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registrations</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .filter-container {
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="filter-container">
        <form method="GET" action="">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" required>
            <button type="submit">Filter</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Driver Name</th>
                <th>Phone</th>
                <th>DL Number</th>
                <th>Vehicle Type</th>
                <th>Registration Date</th>
                <th>Staff ID</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Set default date range or fetch from user input
            $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '2000-01-01';
            $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

            // Query to filter drivers by registration date
            $sql = "SELECT id, driver_name, phone, dl_number, vehicle_type, registration_date, staff_id 
                    FROM driver_info 
                    WHERE registration_date BETWEEN '$start_date' AND '$end_date'
                    ORDER BY registration_date DESC";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $serial_number = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $serial_number++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['driver_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dl_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['registration_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['staff_id']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found for the selected date range.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</body>

</html>
