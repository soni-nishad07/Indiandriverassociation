<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="../css/signin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="form-container">
        <form action="login_process.php" method="POST">
            <h2>Sign In</h2>
            <div class="form-group">
                <label for="email">Email Id</label>
                <input type="email" id="email" name="email" placeholder="Enter your email id" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <i class="fas fa-eye" id="togglePassword"></i>
            </div>
            <div class="form-group checkbox-group">
                <div class="remember-container">
                    <input type="checkbox" id="remember-me" name="remember_me">
                    <label for="remember-me" class="rem-me">Remember me</label>
                </div>
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <button type="submit" class="btn">Login</button>
            <!--<p>Don’t have an Account? <a href="signup.php" style="text-decoration:none; font-weight:600">Sign Up</a></p>-->
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        document.querySelector('.rem-me').addEventListener('click', function() {
            const checkbox = document.querySelector('#remember-me');
            checkbox.checked = !checkbox.checked;
        });
    </script>
</body>

</html>
