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
        /* Add your custom styles here */
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

        h2,
        h3 {
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

        .up-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin: 20px;
            justify-content: center;
        }

        .actions {
            display: flex;
            align-items: center;
            flex-grow: 1;
            margin: 40px auto;
            justify-content: center;
        }

        .search {
            margin-right: 20px;
        }

        .search-bar {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
            max-width: 100%;
        }

        .btns .btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            display: flex;
            align-items: center;
        }

        .btns .btn:hover {
            background-color: #218838;
        }

        .btns .btn i {
            margin-right: 5px;
        }

        @media (max-width: 768px) {

            .content {
                padding: 20px;
            }

            .up-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .search {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .search-bar {
                width: 100%;
            }

            .btns .btn {
                margin-right: 0;
                margin-bottom: 5px;
            }
        }


        @media (max-width: 1300px) {
            .content {
                padding: 20px;
            }
        }


        @media (max-width: 508px) {
            .content {
                padding: 15px;
                position: relative;
                left: -30px;
            }
        }

        .up-content {
            border: inherit !important;
        }

        .table-responsive {
            overflow-x: auto;
            margin: 50px auto;
            width: 75% !important;
        }
    </style>
</head>

<body>


    <div class="sidebar2">
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

                        // Query to join staff_members and driver_info, count drivers per staff_id
                        $sql = "SELECT sm.staff_id, sm.staff_name, COUNT(di.id) AS drivers_registered
                            FROM staff_members sm
                            LEFT JOIN driver_info di ON sm.staff_id = di.staff_id
                            GROUP BY sm.staff_id, sm.staff_name";

                        $result_staff_drivers = $conn->query($sql);

                        if ($result_staff_drivers && $result_staff_drivers->num_rows > 0) {
                            $serial_number = 1; // Initialize serial number
                            while ($row = $result_staff_drivers->fetch_assoc()) {
                                echo "<tr>";
                                // echo "<tr onclick='window.location.href=\"staff_details.php?staff_id=" . $row['staff_id'] . "\"'>";
                                
                                // Clicking the row will redirect here
                                echo "<tr onclick='window.location.href=\"Total_Staffs_count.php\"'>"; 
                               
                                echo "<td>" . $serial_number++ . "</td>";  // Display serial number
                                echo "<td>" . htmlspecialchars($row['staff_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['staff_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['drivers_registered']) . "</td>";  // Display total drivers registered
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No staff members found</td></tr>";
                        }

                        // Close connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</body>

</html>