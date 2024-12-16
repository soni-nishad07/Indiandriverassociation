<?php
session_start();

// Redirect to signin if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$pageTitle = 'Admin Panel';

include('../admin/partials/header.php');
include('../admin/partials/sidebar.php');

// Include your database connection
include 'conn.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registration</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .content {
            margin: 40px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        .chart-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
        }

        h2, h3 {
            text-align: center;
            color: #333;
        }

        h2 {
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 30px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 20px;
            padding-top: 40px;
        }

        .filter-form label,
        .filter-form select,
        .filter-form button {
            font-size: 16px;
        }

        .table-responsive {
            overflow-x: auto;
            margin: 50px auto;
            width: 75% !important;
            padding-bottom: 60px;
        }

        .timebased_filer
        {
            padding: 10px 30px;
            color: #ffffff;
            border: 1px solid #d91a21;
            background-color: #d91a21;
            margin-bottom: 10px;
            border-radius: 4px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <div class="content">
        <form method="GET" action="" class="filter-form">
            <label for="month">Select Month:</label>
            <select name="month" id="month">
                <option value="">--Select Month--</option>
                <?php for ($m = 1; $m <= 12; $m++) {
                    $monthName = date("F", mktime(0, 0, 0, $m, 1));
                    echo "<option value=\"$m\">$monthName</option>";
                } ?>
            </select>

            <label for="year">Select Year:</label>
            <select name="year" id="year">
                <option value="">--Select Year--</option>
                <?php for ($y = date("Y"); $y >= 2000; $y--) {
                    echo "<option value=\"$y\">$y</option>";
                } ?>
            </select>

            <button type="submit" class="timebased_filer">Filter</button>
        </form>

        <div class="table-responsive">
            <div class="staff-table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th> <!-- Serial Number -->
                            <th>Staff ID</th>
                            <th>Staff Name</th>
                            <th>Total Drivers Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Default to current month and year if not provided
                        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
                        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

                        // Calculate start and end dates of the month
                        $startDate = "$year-$month-01";
                        $endDate = date("Y-m-t", strtotime($startDate));

                        // Query to join staff_members and driver_info, count drivers per staff_id in the selected month
                        $sql = "SELECT sm.staff_id, sm.staff_name, COUNT(di.id) AS drivers_registered
                                FROM staff_members sm
                                LEFT JOIN driver_info di 
                                ON sm.staff_id = di.staff_id 
                                AND di.registration_date BETWEEN ? AND ?
                                GROUP BY sm.staff_id, sm.staff_name";

                        // Prepare and execute the statement
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $startDate, $endDate);
                        $stmt->execute();
                        $result_staff_drivers = $stmt->get_result();

                        if ($result_staff_drivers && $result_staff_drivers->num_rows > 0) {
                            $serial_number = 1; // Initialize serial number
                            while ($row = $result_staff_drivers->fetch_assoc()) {
                                echo "<tr onclick=\"window.location.href='Total_Staffs_count.php'\">";
                                echo "<td>" . $serial_number++ . "</td>";  // Display serial number
                                echo "<td>" . htmlspecialchars($row['staff_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['staff_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['drivers_registered']) . "</td>";  // Display total drivers registered
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No records found for the selected month</td></tr>";
                        }

                        // Close the statement
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
