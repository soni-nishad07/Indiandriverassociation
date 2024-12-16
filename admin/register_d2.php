<?php
session_start();
include('./conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $driver_name = mysqli_real_escape_string($conn, trim($_POST['driver_name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $dl_number = mysqli_real_escape_string($conn, trim($_POST['dl_number']));
    $area_postal_code = mysqli_real_escape_string($conn, trim($_POST['area_postal_code']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $vehicle_type = mysqli_real_escape_string($conn, trim($_POST['vehicle_type']));
    $signature_data = mysqli_real_escape_string($conn, $_POST['signature_data']);
    $driver_mode = 'Standard'; // Example value

    // Handle driver photo upload
    if (isset($_FILES['driver_photo']) && $_FILES['driver_photo']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($_FILES['driver_photo']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            $photo_filename = uniqid('driver_', true) . '.' . $file_ext;
            $photo_destination = '../images/' . $photo_filename;

            // Check if the directory exists
            if (!is_dir('../images')) {
                mkdir('../images', 0777, true);
            }

            if (move_uploaded_file($_FILES['driver_photo']['tmp_name'], $photo_destination)) {
                // Photo uploaded successfully
            } else {
                $_SESSION['error'] = "Failed to move uploaded photo.";
                header("Location: ../admin/register_driver.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid photo file type.";
            header("Location: ../admin/register_driver.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error uploading driver photo.";
        header("Location: ../admin/register_driver.php");
        exit();
    }

    // Handle signature upload or data
    $signature_file = null;
    if (isset($_FILES['signature_file']) && $_FILES['signature_file']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($_FILES['signature_file']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            $signature_filename = uniqid('signature_', true) . '.' . $file_ext;
            $signature_destination = '../images/signature/' . $signature_filename; // Updated path

            // Check if the directory exists
            if (!is_dir('../images/signature')) {
                mkdir('../images/signature', 0777, true);
            }

            if (move_uploaded_file($_FILES['signature_file']['tmp_name'], $signature_destination)) {
                $signature_file = $signature_filename;
            } else {
                $_SESSION['error'] = "Failed to move uploaded signature.";
                header("Location: ../admin/register_driver.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid signature file type.";
            header("Location: ../admin/register_driver.php");
            exit();
        }
    }

    // If signature data exists, save it
    if (!empty($signature_data)) {
        $signature_data = str_replace('data:image/png;base64,', '', $signature_data);
        $signature_data = str_replace(' ', '+', $signature_data);
        $decoded_signature = base64_decode($signature_data);
        $signature_filename = uniqid('signature_', true) . '.png';
        $signature_path = '../images/signature/' . $signature_filename; // Updated path

        // Check if the directory exists
        if (!is_dir('../images/signature')) {
            mkdir('../images/signature', 0777, true);
        }

        file_put_contents($signature_path, $decoded_signature);
        $signature_file = $signature_filename;
    }

    // Insert into database
    $insert_query = "INSERT INTO driver_info (driver_mode, driver_name, driver_photo, phone, dl_number, area_postal_code, address, vehicle_type, signature_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($insert_query)) {
        $stmt->bind_param("ssssssssss", $driver_mode, $driver_name, $photo_filename, $phone, $dl_number, $area_postal_code, $address, $vehicle_type, $signature_file);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Driver registered successfully.";
            header("Location: ../admin/drivers_info.php");
            exit();
        } else {
            $_SESSION['error'] = "Database insertion error: " . $stmt->error;
            header("Location: ../admin/register_driver.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Database preparation error: " . $conn->error;
        header("Location: ../admin/register_driver.php");
        exit();
    }
} else {
    header("Location: ../admin/register_driver.php");
    exit();
}
?>
