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
    <!--<link rel="stylesheet" href="../css/downloas.css">-->
        <link rel="stylesheet" href="../css/downloas.css">

    <style>
        @media print {

            .driver_phto_img {
                display: block !important;
                height: 1100px;
            }

            .driver_img1 {
                height: 1100px !important;
            }


            .print-btn {
                display: none;
            }


            .driver-section {
                /* page-break-after: always; */
            }

            img {
                width: 890px;
                /* height: auto; */
            }

            .content-wrapper {
                margin-bottom: 20px;
            }

            span.driver_phto img {
                position: absolute;
                top: 22%;
                right: 5%;
                width: 200px;
                height: 250px;
                border-radius: 2px;
            }

            .signature {
                margin-top: 15px;
                position: absolute;
                bottom: 13%;
                right: 0%;
            }

            .driver_phto img {
                display: block;
                /* max-width: 150px; */
                /* height: auto; */
            }




            .diver_n {
                font-size: 26px;
                position: absolute;
                font-weight: 800;
                top: 26%;
                left: 30%;
            }

            .diver_phn {
                font-size: 26px;
                position: absolute;
                font-weight: 800;
                top: 31%;
                left: 34%;
            }

            .dl_n {
                font-size: 26px;
                position: absolute;
                font-weight: 800;
                top: 36%;
                left: 30%;
            }

            .postal_code {
                font-size: 26px;
                position: absolute;
                font-weight: 800;
                top: 41%;
                left: 50%;
            }


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

    <?php 
        $count = 0; // Initialize a counter
    foreach ($drivers as $index => $driver): // Add index to determine the first page
    ?>
    <div class="driver-section">
        <h2>Driver Information</h2>


        <div class="content-wrapper" style="position: relative;">
            <!-- Conditional image display -->

            <img src="../images/drive-print.jpg" alt="Driver Image" style="display: block;" class="driver_img1">

            <div class="overlay"
                style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; color: #000;">

                <div class="driver_section">

                    <div class="dwnl_l">
                        <div class="diver_n">
                            <?php echo htmlspecialchars($driver['driver_name']); ?>
                        </div>

                        <div class="diver_phn">
                            <?php echo htmlspecialchars($driver['phone']); ?>
                        </div>

                        <div class="dl_n">
                            <?php echo htmlspecialchars($driver['dl_number']); ?>
                        </div>

                        <div class="postal_code">
                            <?php echo htmlspecialchars($driver['area_postal_code']); ?>
                        </div>
                    </div>

                    <span class="driver_phto">
                        <?php
                            if (!empty($driver['driver_photo']) && file_exists('../images/' . $driver['driver_photo'])) {
                                echo "<img src='../images/" . htmlspecialchars($driver['driver_photo']) . "' alt='Driver Photo'>";
                            } else {
                                echo "No photo available.";
                            }
                            ?>
                    </span>

                    <div class="signature">
                        <?php if ($driver['signature_file']): ?>
                        <img src="../images/signature/<?php echo htmlspecialchars($driver['signature_file']); ?>"
                            alt="Signature" style="width: 150px;">
                        <?php else: ?>
                        <p>No signature uploaded.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <img src="../images/print2.jpg" alt="Driver Image" style="display: none" class="driver_phto_img">


    </div>
    <?php
 endforeach; 
 ?>
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