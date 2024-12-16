<?php
include('conn.php');

// Check if ID is provided in the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Driver ID is required.");
}

$driver_id = $_GET['id'];

// Optionally check for related records (for example, in a trips table)
$relatedQuery = "SELECT COUNT(*) FROM driver_info WHERE id = ?";
$relatedStmt = $conn->prepare($relatedQuery);
$relatedStmt->bind_param("i", $id);
$relatedStmt->execute();
$relatedStmt->bind_result($relatedCount);
$relatedStmt->fetch();
$relatedStmt->close();

if ($relatedCount > 0) {
    die("Cannot delete driver. There are related records in the trips table.");
}

// Delete the driver
$deleteQuery = "DELETE FROM driver_info WHERE id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $driver_id);

if ($stmt->execute()) {
    header("Location: drivers_info.php?success=Driver deleted successfully");
    exit();
} else {
    die("Error: " . $stmt->error);
}
?>
