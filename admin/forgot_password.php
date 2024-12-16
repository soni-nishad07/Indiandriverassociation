<?php
session_start();

include ('conn.php');

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        $sql = "SELECT * FROM staff_members WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $token = bin2hex(random_bytes(50)); 
                $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

                $token_sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
                if ($token_stmt = $conn->prepare($token_sql)) {
                    $token_stmt->bind_param("sss", $email, $token, $expiry);
                    if ($token_stmt->execute()) {
                        $reset_link = "http://yourwebsite.com/reset_password.php?token=$token";

                        $subject = "Password Reset Request";
                        $message = "Hello,\n\nWe received a request to reset your password. Click the link below to reset your password:\n\n$reset_link\n\nIf you did not request a password reset, please ignore this email.\n\nRegards,\nYour Company";
                        $headers = "From: no-reply@yourwebsite.com";

                        if (mail($email, $subject, $message, $headers)) {
                            $successMessage = "A password reset link has been sent to your email address.";
                        } else {
                            $errorMessage = "Failed to send the password reset email. Please try again later.";
                        }
                    } else {
                        $errorMessage = "Failed to store the reset token. Please try again.";
                    }
                    $token_stmt->close();
                } else {
                    $errorMessage = "Error preparing the password reset statement: " . $conn->error;
                }
            } else {
                $errorMessage = "No account found with that email address.";
            }

            $stmt->close();
        } else {
            $errorMessage = "Error preparing the statement: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/admin.css"> 
   
</head>
<body>
    <div class="form-container">
        <h2>Forgot Password</h2>

        <?php if (!empty($successMessage)): ?>
            <p class="success-message"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <div class="form-group">
                <label for="email">Enter your email address</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            <button type="submit" class="btn">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
