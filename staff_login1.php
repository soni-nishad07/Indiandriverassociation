<?php
session_start();
include('admin/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to check login credentials
    $query = "SELECT * FROM staff_members WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $staff = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['staff_id'] = $staff['staff_id'];
        $_SESSION['staff_name'] = $staff['staff_name'];
        $_SESSION['status'] = $staff['status'];
        
        // Redirect to dashboard or staff management page
        header('Location: admin/record.php');
        exit();
    } else {
        $errorMessage = "Invalid email or password.";
    }

    $stmt->close();
}
?>

<?php include('./partials/header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/staff.css">

</head>
<body>

    <div class="login-container">
        <h2>Staff Login</h2>

        <?php if (isset($errorMessage)): ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

        <form action="staff_login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"  placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <div class="register-link">
            <!--Don't have an account? <a href="admin/signup.php">Register Here</a>-->
        </div>
    </div>

</body>
</html>
