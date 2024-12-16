<?php
session_start();
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];

                $login_time = date('Y-m-d H:i:s');

                header("Location: index.php");
                                // header('Location: record.php');

                exit();
            } else {
                echo "Invalid email or password."; 
                // header("Location: index.php");
                                // header('Location: record.php');
                                            header("Location: signin.php");

            }
        } else {
            // echo "Invalid email or password."; 
            '
            <script>
            alert("Invalid email or password.");
            </script>';
            header("Location: signin.php");
        }

        $stmt->close();
    } else {
        // echo "Error preparing statement: " . $conn->error; 
        ' <script>
        header("Location: signin.php");
        alert("Error preparing statement: " . $conn->error);
        </script>
        ';
    }

    $conn->close();
}
?>
