<?php
// session_start();
// if (!isset($_SESSION['staff_id'])) {
//     header('Location: ../staff_login.php');
//     exit();
// }

// include 'conn.php'; 

// $staff_id = $_SESSION['staff_id'];
// $staff_name = $_SESSION['staff_name'];

// $total_registered_by_staff = 0;
// $query = "SELECT COUNT(*) AS total FROM driver_info WHERE staff_id = ?";
// $stmt = $conn->prepare($query);
// $stmt->bind_param("i", $staff_id);
// $stmt->execute();
// $result = $stmt->get_result();
// if ($result && $row = $result->fetch_assoc()) {
//     $total_registered_by_staff = $row['total'];
// }
// $stmt->close();
// $conn->close();




session_start();
if (!isset($_SESSION['staff_id'])) {
    header('Location: ../staff_login.php');
    exit();
}

include 'conn.php'; // Database connection

$staff_id = $_SESSION['staff_id'];
$staff_name = $_SESSION['staff_name'];

// Fetch the total number of drivers registered by this staff member
$total_registered_by_staff = 0;
$query = "SELECT COUNT(*) AS total FROM driver_info WHERE staff_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $staff_id); // 's' for string type (adjusted for your staff_id type)
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $total_registered_by_staff = $row['total'];
}
$stmt->close();
$conn->close();

?>




<?php

$pageTitle = 'Staff Login';

// include 'partials/header.php';

include 'partials/staff_header.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Records</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .content {
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        .chart-container {
            width: 100%;
            max-width: 800px;
            margin: auto;
        }

        h2,
        h3 {
            text-align: center;
            color: #333;
        }

        h2 {
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 30px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .up-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin: 20px;
            justify-content: center;
        }

        .actions {
            display: flex;
            align-items: center;
            flex-grow: 1;
            margin: 40px auto;
            justify-content: center;
        }

        .search {
            margin-right: 20px;
        }

        .search-bar {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
            max-width: 100%;
        }

        .btns .btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            white-space: nowrap;
        }

        .btns .btn:hover {
            background-color: #218838;
        }

        .btns .btn i {
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .up-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .search {
                margin-right: 0;
                margin-bottom: 10px;
                margin: 8px 40px;
            }

            .search-bar {
                width: 100%;
            }

            .btns .btn {
                margin-right: 0;
                margin-bottom: 5px;
                white-space: nowrap;
            }

            .content {
                padding: 20px;
            }
        }

        @media (max-width: 1300px) {
            .content {
                padding: 20px;
            }
        }


        @media (max-width: 508px) {
            .content {
                padding: 15px;
                position: relative;
                left: -30px;
            }
        }
    </style>
</head>

<body>


    <div class="up-content">
        <div class="actions">
            <div class="search">
                <input type="text" placeholder="Search..." class="search-bar w-100 d-flex">
            </div>
            <div class="btns">
                <a href="../manual_reg.php" class="btn add"><i class="fas fa-plus"></i> Add New Driver</a>
            </div>
        </div>
    </div>

    <div class="content">
        <h2>Forms Filled by <b style="color:red;"><?php echo htmlspecialchars($_SESSION['staff_name']); ?></b> </h2>

        <?php
        if (!empty($_SESSION['error'])) {
            echo '<div class="error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <div class="chart-container">
            <canvas id="formsChart"></canvas>
        </div>

        <h3>Total Drivers Registered: <?php echo htmlspecialchars($total_registered_by_staff); ?></h3>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var formsData = <?php echo json_encode($forms_data ?? []); ?>; // Ensure forms_data is initialized

            var formNames = formsData.map(function(data) {
                return data.form_name;
            });
            var formCounts = formsData.map(function(data) {
                return data.form_count;
            });

            var ctx = document.getElementById('formsChart').getContext('2d');
            var formsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: formNames,
                    datasets: [{
                        label: 'Number of Forms Filled',
                        data: formCounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Form Name'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Forms Filled by You'
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        });
    </script>

</body>

</html>