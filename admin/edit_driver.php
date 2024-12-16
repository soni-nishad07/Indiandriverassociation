<?php
include('conn.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Driver ID is required.");
}

$driver_id = $_GET['id'];

$query = "SELECT driver_name, phone, dl_number, area_postal_code, address, vehicle_type, driver_photo FROM driver_info WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Driver not found.");
}

$driver = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $driver_name = $_POST['driver_name'];
    $phone = $_POST['phone'];
    $dl_number = $_POST['dl_number'];
    $area_postal_code = $_POST['area_postal_code'];
    $address = $_POST['address'];
    $vehicle_type = $_POST['vehicle_type'];

    // Initialize driver_photo to existing value
    $driver_photo = $driver['driver_photo'];
    
    // Check if a new file has been uploaded
    if (!empty($_FILES['driver_photo']['name'])) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES['driver_photo']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image
        $check = getimagesize($_FILES['driver_photo']['tmp_name']);
        if ($check === false) {
            $errorMessage = "File is not an image.";
        } else {
            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES['driver_photo']['tmp_name'], $target_file)) {
                $driver_photo = $target_file; 
            } else {
                $errorMessage = "Error uploading the image.";
            }
        }
    }

    // Prepare the update query
    $updateQuery = "UPDATE driver_info SET driver_name = ?, phone = ?, dl_number = ?, area_postal_code = ?, address = ?,  vehicle_type = ?, driver_photo = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    
    // Make sure to bind the parameters correctly
    $stmt->bind_param("ssssssssi", $driver_name, $phone, $dl_number, $area_postal_code, $address, $vehicle_type, $driver_photo, $driver_id);

    if ($stmt->execute()) {
        header("Location: drivers_info.php?success=Driver updated successfully");
        exit();
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Driver</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    margin: 0;
    padding: 0;
}

.form-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px 40px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid #dee2e6;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #343a40;
    font-weight: 600;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #495057;
}

input[type="text"],
input[type="date"],
textarea,
select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="date"]:focus,
textarea:focus,
select:focus {
    border-color: #80bdff;
    outline: none;
}

textarea {
    resize: vertical;
}

.preview-image {
    max-width: 100px;
    margin-top: 10px;
    border-radius: 5px;
}

.submit-button {
    width: 100%;
    padding: 12px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.submit-button:hover {
    background-color: #218838;
}

.error-message {
    color: #dc3545;
    text-align: center;
    margin-bottom: 20px;
}

/* Responsive Styles */
@media (max-width: 600px) {
    .form-container {
        margin: 20px;
        padding: 15px;
    }

    .submit-button {
        font-size: 14px;
    }
}

    </style>
</head>

<body>
    <div class="form-container">
        <h2>Edit Driver</h2>

        <?php if (isset($errorMessage)) : ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <form action="edit_driver.php?id=<?php echo htmlspecialchars($driver_id); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="driver_name">Driver Name:</label>
                <input type="text" id="driver_name" name="driver_name" value="<?php echo htmlspecialchars($driver['driver_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($driver['phone']); ?>" maxlength="10" required>
            </div>

            <div class="form-group">
                <label for="dl_number">DL Number:</label>
                <input type="text" id="dl_number" name="dl_number" value="<?php echo htmlspecialchars($driver['dl_number']); ?>" required>
            </div>

            <div class="form-group">
                <label for="area_postal_code">Area Postal Code:</label>
                <input type="text" id="area_postal_code" name="area_postal_code" value="<?php echo htmlspecialchars($driver['area_postal_code']); ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($driver['address']); ?></textarea>
            </div>

   

            <div class="form-group">
                <label for="vehicle_type">Vehicle Type:</label>
                <select id="vehicle_type" name="vehicle_type" required>
                    <option value="" disabled>Select your vehicle type</option>
                    <option value="car" <?php echo ($driver['vehicle_type'] == 'car') ? 'selected' : ''; ?>>Car</option>
                    <option value="truck" <?php echo ($driver['vehicle_type'] == 'truck') ? 'selected' : ''; ?>>Truck</option>
                    <option value="motorcycle" <?php echo ($driver['vehicle_type'] == 'motorcycle') ? 'selected' : ''; ?>>Motorcycle</option>
                    <option value="bus" <?php echo ($driver['vehicle_type'] == 'bus') ? 'selected' : ''; ?>>Bus</option>
                    <option value="auto" <?php echo ($driver['vehicle_type'] == 'auto') ? 'selected' : ''; ?>>Auto</option>
                </select>
            </div>

            <div class="form-group">
                <label for="driver_photo">Driver Photo:</label>
                <input type="file" id="driver_photo" name="driver_photo">
                <?php if (!empty($driver['driver_photo'])): ?>
                    <img src="<?php echo htmlspecialchars($driver['driver_photo']); ?>" alt="Current Driver Photo" class="preview-image">
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-button">Update Driver</button>
        </form>
    </div>
</body>

</html>
