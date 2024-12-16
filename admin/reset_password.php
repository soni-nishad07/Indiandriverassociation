<?php
session_start();

include('conn.php');

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $email = $row['email'];

                $update_sql = "UPDATE staff_members SET password = ? WHERE email = ?";
                if ($update_stmt = $conn->prepare($update_sql)) {
                    $update_stmt->bind_param("ss", $hashed_password, $email);
                    if ($update_stmt->execute()) {
                        $delete_sql = "DELETE FROM password_resets WHERE token = ?";
                        if ($delete_stmt = $conn->prepare($delete_sql)) {
                            $delete_stmt->bind_param("s", $token);
                            $delete_stmt->execute();
                            $delete_stmt->close();
                        }

                        $successMessage = "Your password has been reset successfully. You can now <a href='signin.php'>sign in</a>.";
                    } else {
                        $errorMessage = "Failed to update your password. Please try again.";
                    }
                    $update_stmt->close();
                } else {
                    $errorMessage = "Error preparing the update statement: " . $conn->error;
                }
            } else {
                $errorMessage = "Invalid or expired token.";
            }
            $stmt->close();
        } else {
            $errorMessage = "Error preparing the statement: " . $conn->error;
        }
    } else {
        $errorMessage = "Passwords do not match!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/style.css"> 
    
</head>
<body>
    <div class="form-container">
        <h2>Reset Password</h2>

        <?php if (!empty($successMessage)): ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if (empty($successMessage)): 
             ?>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required placeholder="Enter your new password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your new password">
                </div>
                <button type="submit" class="btn">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
