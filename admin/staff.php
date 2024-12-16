<?php
ob_start(); // Start output buffering

$pageTitle = 'Admin Panel'; 


include('conn.php'); 
include('./partials/header.php');
include('./partials/sidebar.php');

$successMessage = ""; // Initialize success message variable
$errorMessage = ""; // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_name = $_POST['staff_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    $query = "SELECT staff_id FROM staff_members ORDER BY staff_id DESC LIMIT 1";
    $result = $conn->query($query);
    $lastId = $result->fetch_assoc()['staff_id'] ?? 'Staff000'; 

    $lastIdNumber = intval(substr($lastId, 5)); 
    $newIdNumber = $lastIdNumber + 1;
    
    $newId = sprintf("Staff%03d", $newIdNumber);

    $insertQuery = "INSERT INTO staff_members (staff_id, staff_name, email, phone, password, status) VALUES ('$newId', '$staff_name', '$email', '$phone','$password','$status')";

    if ($conn->query($insertQuery) === TRUE) {
        $successMessage = "New staff member added successfully.";
        // Delay redirection by a few seconds to show the message
        header('refresh:3;url=access_management.php');
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
}

ob_end_flush(); // Flush the output buffer and send the output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff Member</title>
    <link rel="stylesheet" href="../css/admin.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0px;
        }

        .form-container {
            margin: 30px auto;
            background-color: #f4f4f4;
            padding: 10px 10%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            padding-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success-message,
        .error-message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .fade-out {
            opacity: 1;
            transition: opacity 2s ease-out;
        }

        .fade-out.hidden {
            opacity: 0;
        }

        @media (max-width: 600px) {
            .sidebar2 {
                padding: 15px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar2">

<div class="form-container">
    <h2>Add New Staff Member</h2>

    <?php if (!empty($successMessage)) : ?>
        <p id="successMessage" class="success-message fade-out"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if (!empty($errorMessage)) : ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form action="staff.php" method="POST">
        <label for="staff_name">Staff Name:</label>
        <input type="text" id="staff_name" name="staff_name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" maxlength="10" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <br>
        <button type="submit" class="add-btn">Add Staff Member</button>
    </form>
</div>
</div>

<script>
// JavaScript to hide the success message after 2 seconds
setTimeout(function() {
    var successMessage = document.getElementById("successMessage");
    if (successMessage) {
        successMessage.classList.add("hidden");
    }
}, 2000);
</script>

</body>
</html>
