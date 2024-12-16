<?php
include '../admin/conn.php';
session_start(); 

$target_dir = "../images/";
$uploadOk = 1;
$imageFileType = "";
$driver_photo = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES["driver_photo"]) && $_FILES["driver_photo"]["error"] == UPLOAD_ERR_OK) {
        $target_file = $target_dir . basename($_FILES["driver_photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["driver_photo"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['message'] = "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["driver_photo"]["size"] > 5000000) {
            $_SESSION['message'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        $allowedFileTypes = ["jpg", "jpeg", "png", "gif", "bmp", "webp"];
        if (!in_array($imageFileType, $allowedFileTypes)) {
            $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG, GIF, BMP, & WEBP files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $_SESSION['message'] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["driver_photo"]["tmp_name"], $target_file)) {
                $driver_photo = htmlspecialchars(basename($_FILES["driver_photo"]["name"]));
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
                $uploadOk = 0;
            }
        }
    }

    // Retrieve form data
    $driver_mode = $_POST['driver_mode'] ?? '';
    $driver_name = $_POST['driver_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $dl_number = $_POST['dl_number'] ?? '';
    $area_postal_code = $_POST['area_postal_code'] ?? '';
    $address = $_POST['address'] ?? '';
    $vehicle_type = $_POST['vehicle_type'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
        header("Location: ../registration.php");
        exit();
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO driver_info (driver_mode, driver_name, driver_photo, phone, dl_number, area_postal_code, address, vehicle_type, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        $_SESSION['message'] = "Error preparing the statement: " . $conn->error;
        header("Location: ../registration.php");
        exit();
    }

    $stmt->bind_param("ssssssssssss", $driver_mode, $driver_name, $driver_photo, $phone, $dl_number, $area_postal_code, $address, $vehicle_type, $password);

    if ($stmt->execute()) {
        // Get the ID of the last inserted row
        $last_id = $conn->insert_id;
        
        // Update the password for that specific record
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE driver_info SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed_password, $last_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Registration successful!";
        } else {
            $_SESSION['message'] = "Error updating password: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../index.php");
    exit();
}
?>
