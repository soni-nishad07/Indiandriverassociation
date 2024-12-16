

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0px 20px;
            background-color: #f4f4f4;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-left .logo {
            max-height: 60px; /* Adjust logo size */
        }

        .header-center {
            flex: 1;
            text-align: center;
        }

        .header-center h2 {
            font-size: 24px;
            color: #d42e18;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-right span {
            font-size: 16px;
            color: #333;
        }

        .header-right .btn.logout {
            background-color: #d42e18;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .header-right .btn.logout:hover {
            background-color: #b02616;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-left, .header-right {
                width: 100%;
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
            }

            .header-center {
                width: 100%;
                text-align: left;
                margin-bottom: 10px;
            }

            .header-center h2 {
                font-size: 20px;
            }

            .header-right {
                justify-content: flex-end;
            }
        }

        @media (max-width: 480px) {
            .header-left .logo {
                max-height: 50px;
            }

            .header-center h2 {
                font-size: 18px;
            }

            .header-right .btn.logout {
                padding: 6px 12px;
                font-size: 14px;
            }

            .header-right span {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <header class="header">
     <a href="index.php">
     <div class="header-left">
            <img src="../images/logo.png" alt="logo" class="logo">
        </div>
     </a>
        <div class="header-center">
            <!-- <h2>Admin Panel</h2> -->
            <h2><?php echo htmlspecialchars($pageTitle); ?></h2> <!-- Dynamic Title -->
        </div>
        <div class="header-right">
            <?php if (isset($_SESSION['staff_name'])): ?>
                <span><?php echo htmlspecialchars($_SESSION['staff_name']); ?>!</span>
            <?php endif; ?>
            <a href="logout.php" class="btn logout">Logout</a>
        </div>
    </header>
</body>

</html>
