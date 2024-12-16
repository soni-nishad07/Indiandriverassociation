<?php
include('conn.php');

if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];
    
    // Fetch staff details
    $query = "SELECT * FROM staff_members WHERE staff_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $staff = $result->fetch_assoc();
    
    if (!$staff) {
        echo "Staff member not found.";
        exit();
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $staff_name = $_POST['staff_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $status = $_POST['status'];

        $updateQuery = "UPDATE staff_members SET staff_name = ?, email = ?, phone = ?, password = ?, status = ? WHERE staff_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('ssssss', $staff_name, $email, $phone, $password, $status, $staff_id);

        if ($stmt->execute()) {
            header("Location: access_management.php?success=1");
            exit();
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }
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
    <title>Edit Staff Member</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: darkgreen;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }

            input[type="text"],
            input[type="email"],
            select,
            button {
                font-size: 16px;
                padding: 12px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 10px;
            }

            h2 {
                font-size: 20px;
            }

            input[type="text"],
            input[type="email"],
            select,
            button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Staff Member</h2>

    <?php if (isset($errorMessage)) : ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form action="edit_staff.php?id=<?php echo htmlspecialchars($staff_id); ?>" method="POST">
        <label for="staff_name">Staff Name:</label>
        <input type="text" id="staff_name" name="staff_name" value="<?php echo htmlspecialchars($staff['staff_name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($staff['phone']); ?>" required>

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($staff['password']); ?>" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active" <?php echo $staff['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo $staff['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
        <br>
        <button type="submit">Update Staff Member</button>
    </form>
</div>

</body>
</html>
