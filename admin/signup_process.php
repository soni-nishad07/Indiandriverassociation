<?php
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Check if the email already exists
    $checkEmailQuery = "SELECT email FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($checkEmailQuery)) {
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            echo "Error executing email check: " . $stmt->error;
            exit();
        }
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email exists
            echo "An account with this email already exists.";
            
            $stmt->close();
        } else {
            $stmt->close();

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user into the users table
            $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            if ($stmt = $conn->prepare($insertQuery)) {
                $stmt->bind_param("sss", $name, $email, $hashed_password);

                if ($stmt->execute()) {
                    // Set session variables for the new user
                    $_SESSION['user_id'] = $stmt->insert_id; // Get the inserted user's ID
                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;

                    // Redirect to index.php in the root directory
                    header("Location: index.php");  // Adjusted path
                    exit();
                } else {
                    echo "Error inserting user: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing insert statement: " . $conn->error;
            }
        }
    } else {
        echo "Error preparing email check statement: " . $conn->error;
    }
}

$conn->close();
?>
