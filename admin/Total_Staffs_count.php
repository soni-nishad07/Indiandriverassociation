<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}


$pageTitle = 'Admin Panel';


include('../admin/partials/header.php');
include('../admin/partials/sidebar.php');
?>


<?php
// Include your database connection
include 'conn.php';

// Fetch total number of drivers registered
$total_registered_by_staff = 0;
$query = "SELECT COUNT(*) AS total FROM driver_info";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $total_registered_by_staff = $row['total'];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registration</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }


        .content {
            margin: 40px auto;
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
        }

        .btns .btn:hover {
            background-color: #218838;
        }

        .btns .btn i {
            margin-right: 5px;
        }

        @media (max-width: 768px) {

            .content {
                padding: 20px;
            }

            .up-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .search {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .search-bar {
                width: 100%;
            }

            .btns .btn {
                margin-right: 0;
                margin-bottom: 5px;
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

        .up-content {
            border: inherit !important;
        }
    </style>
</head>

<body>


    <div class="sidebar2">
        <div class="up-content">
            <div class="content">
                <h2>Forms Filled by <b style="color:red;">Staffs and Administration</b> </h2>
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
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Example data for chart
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
                                text: 'Drivers'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Total Registered Numbers'
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