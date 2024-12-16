<?php
session_start(); // Start the session

$pageTitle = 'Admin Panel'; 

// Check if admin is logged in
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: admin_login.php"); // Redirect to admin login if not logged in
//     exit();
// }

include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selected_drivers']) && is_array($_POST['selected_drivers']) && count($_POST['selected_drivers']) > 0) {
        // Sanitize selected IDs
        $selected_ids = array_map('intval', $_POST['selected_drivers']);

        // Prepare placeholders for SQL IN clause
        $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));

        // Prepare the SQL statement
        $query = "SELECT id, driver_mode, driver_name, driver_photo, phone, dl_number, area_postal_code, address, vehicle_type, signature_data FROM driver_info WHERE id IN ($placeholders)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Dynamically bind parameters
        $types = str_repeat('i', count($selected_ids)); // assuming 'id' is integer
        $stmt->bind_param($types, ...$selected_ids);

        $stmt->execute();
        $result = $stmt->get_result();

        $drivers = [];
        while ($row = $result->fetch_assoc()) {
            $drivers[] = $row;
        }
        $stmt->close();

        if (count($drivers) === 0) {
            echo "<p>No drivers found for the selected IDs.</p>";
            echo "<a href='driver_info.php'>Go Back</a>";
            exit();
        }

        // Generate the printable HTML page
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Selected Drivers Information</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }

                .driver-section {
                    page-break-after: always;
                }

                .driver-section:last-child {
                    page-break-after: never;
                }

                h2 {
                    text-align: center;
                    color: #333;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }

                th, td {
                    padding: 8px 12px;
                    border: 1px solid #ccc;
                    vertical-align: top;
                }

                th {
                    background-color: #f2f2f2;
                    text-align: left;
                    width: 200px;
                }

                .driver-photo, .signature {
                    width: 150px;
                    height: auto;
                }

                @media print {
                    .print-btn {
                        display: none;
                    }
                }

                .print-btn {
                    margin-bottom: 20px;
                    padding: 10px 15px;
                    background-color: #28a745;
                    color: white;
                    border: none;
                    cursor: pointer;
                    border-radius: 4px;
                }

                .print-btn:hover {
                    background-color: #218838;
                }
            </style>
            <script>
                function printAll() {
                    window.print();
                }
            </script>
        </head>
        <body>
            <button onclick="printAll()" class="print-btn">Print All Drivers</button>

            <?php foreach ($drivers as $driver): ?>
                <div class="driver-section">
                    <h2>Driver Information</h2>
                    <table>
                        <tr>
                            <th>Driver ID</th>
                            <td><?php echo 'DRV' . str_pad($driver['id'], 3, '0', STR_PAD_LEFT); ?></td>
                        </tr>
                        <tr>
                            <th>Driver Mode</th>
                            <td><?php echo htmlspecialchars($driver['driver_mode']); ?></td>
                        </tr>
                        <tr>
                            <th>Driver Name</th>
                            <td><?php echo htmlspecialchars($driver['driver_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Driver Photo</th>
                            <td>
                                <?php
                                if (!empty($driver['driver_photo']) && file_exists('../images/' . $driver['driver_photo'])) {
                                    echo "<img src='../images/" . htmlspecialchars($driver['driver_photo']) . "' alt='Driver Photo' class='driver-photo'>";
                                } else {
                                    echo "No photo available.";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><?php echo htmlspecialchars($driver['phone']); ?></td>
                        </tr>
                        <tr>
                            <th>DL Number</th>
                            <td><?php echo htmlspecialchars($driver['dl_number']); ?></td>
                        </tr>
                        <tr>
                            <th>Area Postal Code</th>
                            <td><?php echo htmlspecialchars($driver['area_postal_code']); ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo htmlspecialchars($driver['address']); ?></td>
                        </tr>
                      
                      
                        <tr>
                            <th>Vehicle Type</th>
                            <td><?php echo htmlspecialchars($driver['vehicle_type']); ?></td>
                        </tr>
                        <!-- <tr>
                            <th>Signature</th>
                            <td> -->
                                <!-- <?php
                                // if (!empty($driver['signature_file']) && file_exists('../signatures/' . $driver['signature_file'])) {
                                //     echo "<img src='../signatures/" . htmlspecialchars($driver['signature_file']) . "' alt='Signature' class='signature'>";
                                // }
                                // elseif (!empty($driver['signature_data'])) {
                                //     echo "<img src='" . htmlspecialchars($driver['signature_data']) . "' alt='Signature' class='signature'>";
                                // }
                                // else {
                                //     echo "No signature available.";
                                // }
                                ?> -->
                            <!-- </td>
                        </tr> -->
                    </table>
                </div>
            <?php endforeach; ?>
        </body>
        </html>
        <?php
        exit();
    } else {
        // No drivers selected
        echo "<p>No drivers selected for download. Please go back and select at least one driver.</p>";
        echo "<a href='driver_info.php'>Go Back</a>";
        exit();
    }
} else {
    // Invalid request method
    echo "<p>Invalid request method.</p>";
    echo "<a href='driver_info.php'>Go Back</a>";
    exit();
}

$conn->close();
?>
