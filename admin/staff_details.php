<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

// Include database connection
include 'conn.php';

// Check if the staff_id is passed in the URL and get the details
$staff_id = isset($_GET['staff_id']) ? $_GET['staff_id'] : null;

if ($staff_id) {
    // Query to get detailed staff information
    $sql = "SELECT sm.staff_id, sm.staff_name, sm.email, sm.phone, COUNT(di.id) AS drivers_registered
            FROM staff_members sm
            LEFT JOIN driver_info di ON sm.staff_id = di.staff_id
            WHERE sm.staff_id = ?
            GROUP BY sm.staff_id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $staff_id); // Bind the staff_id
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $staff_name = $row['staff_name'];
        $staff_email = $row['email'];
        $staff_phone = $row['phone'];
        $drivers_registered = $row['drivers_registered'];
    } else {
        $staff_name = "No details found";
        $staff_email = "";
        $staff_phone = "";
        $drivers_registered = 0;
    }
    $stmt->close();
} else {
    // Handle case when staff_id is not passed
    echo "Invalid staff ID.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Details</title>
</head>
<body>
    <div>
        <h2>Staff Details</h2>
        <p><strong>Staff Name:</strong> <?php echo htmlspecialchars($staff_name); ?></p>
        <p><strong>Staff Email:</strong> <?php echo htmlspecialchars($staff_email); ?></p>
        <p><strong>Staff Phone:</strong> <?php echo htmlspecialchars($staff_phone); ?></p>
        <p><strong>Total Drivers Registered:</strong> <?php echo htmlspecialchars($drivers_registered); ?></p>
    </div>
</body>
</html>
