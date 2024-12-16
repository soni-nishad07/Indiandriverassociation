<?php
session_start(); // Start the session

$pageTitle = 'Admin Panel'; 

include('conn.php');

// Handle file upload for signature when registering a driver
$signature_file = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['signature_file']) && $_FILES['signature_file']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($_FILES['signature_file']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            $signature_filename = uniqid('signature_', true) . '.' . $file_ext;
            $signature_destination = '../images/signature/' . $signature_filename;

            // Ensure directory exists
            if (!is_dir('../images/signature')) {
                mkdir('../images/signature', 0777, true);
            }

            if (move_uploaded_file($_FILES['signature_file']['tmp_name'], $signature_destination)) {
                // Store the filename in the database or session as needed
                $signature_file = $signature_filename;
            } else {
                $_SESSION['error'] = "Failed to upload the signature.";
                header("Location: ../admin/register_driver.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
            header("Location: ../admin/register_driver.php");
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selected_drivers']) && is_array($_POST['selected_drivers']) && count($_POST['selected_drivers']) > 0) {
        // Sanitize selected IDs
        $selected_ids = array_map('intval', $_POST['selected_drivers']);

        // Prepare placeholders for SQL IN clause
        $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));

        // Prepare the SQL statement
        $query = "SELECT id, driver_mode, driver_name, driver_photo, phone, dl_number, area_postal_code, address, vehicle_type, signature_file FROM driver_info WHERE id IN ($placeholders)";
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
                    color: #333;
                }

                .driver-section {
                    page-break-after: always;
                    border: 1px solid #ccc;
                    padding: 20px;
                    margin-bottom: 30px;
                }

                .driver-section:last-child {
                    page-break-after: never;
                }

                h2 {
                    text-align: center;
                    font-size: 24px;
                    margin-bottom: 20px;
                }

                .content-wrapper {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                    margin-bottom: 20px;
                }

                .content-wrapper img {
                    width: 150px;
                    height: auto;
                    border: 1px solid #ddd;
                    margin: 20px 0px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th, td {
                    padding: 8px 12px;
                    border: 1px solid #ccc;
                    vertical-align: top;
                }

                th {
                    background-color: #f9f9f9;
                    width: 200px;
                }

                .signature {
                    margin-top: 15px;
                }

                @media print {
                    .print-btn {
                        display: none;
                    }
                }

                .print-btn {
                    padding: 10px 15px;
                    background-color: #28a745;
                    color: white;
                    border: none;
                    cursor: pointer;
                    border-radius: 4px;
                    margin: 40px auto;
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
                    <div class="content-wrapper">
                        <div class="left-column">
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
                            </table>
                        </div>
                        <div class="right-column">
                            <div>
                            <strong>Driver Photo:</strong><br>
                                <?php
                                if (!empty($driver['driver_photo']) && file_exists('../images/' . $driver['driver_photo'])) {
                                    echo "<img src='../images/" . htmlspecialchars($driver['driver_photo']) . "' alt='Driver Photo'>";
                                } else {
                                    echo "No photo available.";
                                }
                                ?>
                            </div>
                            <div class="signature">
                                <strong>Signature:</strong><br>
                                <?php
                                if (!empty($driver['signature_file']) && file_exists('../images/signature/' . $driver['signature_file'])) {
                                    echo "<img src='../images/signature/" . htmlspecialchars($driver['signature_file']) . "' alt='Driver Signature' class='signature'>";
                                } else {
                                    echo "No signature available.";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
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
