<?php

$pageTitle = 'Admin panel'; 

include('./partials/header.php');
include('./partials/sidebar.php');
include('conn.php');
?>

<body>
    
<div class="sidebar2">

    <div class="up-content">
        <div class="actions">
            <div class="search">
                <input type="text" placeholder="Search..." class="search-bar">
            </div>
            <div class="btns">
                <a href="download_staff_list.php" class="btn download"><i class="fas fa-download"></i> Download</a>
                <a href="staff.php" class="btn add"><i class="fas fa-plus"></i> Add New Staff Member</a>
            </div>
        </div>
    </div>

    <!-- Success Message Display -->
    <?php if (isset($_GET['message'])) : ?>
        <p class="success-message"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <div class="table-responsive">
    <div class="staff-table">
        <table>
            <thead>
                <tr>
                    <!-- <th>Serial</th> -->
                    <th>ID</th>
                    <th>Staff Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <!-- <th>Password</th> -->
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT staff_id, staff_name, email, phone, password, status FROM staff_members ORDER BY staff_id";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $serial = 1; 
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        // echo "<td>" . $serial++ . "</td>"; // Display serial number if needed
                        echo "<td>" . htmlspecialchars($row['staff_id']) . "</td>"; // Display staff_id
                        echo "<td>" . htmlspecialchars($row['staff_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        // echo "<td>" . htmlspecialchars($row['password']) . "</td>"; 
                        echo "<td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>";
                        echo "<td>
                            <a href='edit_staff.php?id=" . htmlspecialchars($row['staff_id']) . "' class='btn edit'><i class='fas fa-edit'></i></a>
                            <a href='delete_staff.php?id=" . htmlspecialchars($row['staff_id']) . "' class='btn delete' onclick='return confirm(\"Are you sure you want to delete this staff member?\")'><i class='fas fa-trash'></i></a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No staff members found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
            </div>

            </div>
            
</body>

</html>
