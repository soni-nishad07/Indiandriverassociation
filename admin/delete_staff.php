<?php
include('conn.php');

if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];
    
    // Delete staff member
    $deleteQuery = "DELETE FROM staff_members WHERE staff_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param('s', $staff_id);

    if ($stmt->execute()) {
        header("Location: access_management.php?success=1");
        exit();
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }
} else {
    echo "No staff ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Staff Member</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="form-container">
    <h2>Delete Staff Member</h2>

    <?php if (isset($errorMessage)) : ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php else : ?>
        <p>Staff member has been deleted successfully.</p>
    <?php endif; ?>
</div>

</body>
</html>
