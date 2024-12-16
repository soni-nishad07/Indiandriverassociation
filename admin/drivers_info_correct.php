<?php
include('conn.php');

$pageTitle = 'Admin Panel'; 


include('./partials/header.php');
include('./partials/sidebar.php');
?>

<body>

<div class="sidebar2">

<div class="up-content">
    <div class="actions">
        <div class="search">
            <input type="text" placeholder="Search..." class="search-bar" onkeyup="searchDrivers()">
        </div>
        <div class="btns">
            <!-- Download Selected Drivers -->
            <button type="submit" form="driversForm" class="btn download">
                <i class="fas fa-download"></i> Download
            </button>
            <a href="../manual_reg.php" class="btn add">
                <i class="fas fa-plus"></i> Add New Driver
            </a>
        </div>
    </div>
</div>

<!-- Form wrapping the table -->
<div class="table-responsive">
    <form id="driversForm" action="download_driver_info.php" method="POST">
        <div class="driver-table">
            <table>
                <thead>
                    <tr>
                        <!-- Checkbox for selecting all drivers -->
                        <th>
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                        </th>
                        <th>Id</th>
                        <th>Driver Name</th>
                        <th>Driver Photo</th>
                        <th>Phone</th>
                        <th>DL Number</th>
                        <th>Area Postal Code</th>
                        <th>Address</th>
                        <th>Vehicle Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="driversTableBody">
                    <?php
                    // Query to fetch driver details
                    $query = "SELECT id, driver_name, driver_photo, phone, dl_number, area_postal_code, address, vehicle_type FROM driver_info ORDER BY id";
                    $result = $conn->query($query);
                    $serialNumber = 1;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $driver_code = 'DRV' . str_pad($serialNumber, 3, '0', STR_PAD_LEFT);
                            echo "<tr>";

                            // Selection Checkbox
                            echo "<td><input type='checkbox' name='selected_drivers[]' value='" . htmlspecialchars($row['id']) . "'></td>";

                            echo "<td>" . htmlspecialchars($driver_code) . "</td>";
                            echo "<td>" . htmlspecialchars($row['driver_name']) . "</td>";
                            echo "<td><img src='../images/" . htmlspecialchars($row['driver_photo']) . "' alt='Driver Photo' style='width:50px;height:auto;'></td>";
                            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dl_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['area_postal_code']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                            echo "<td>
                                <a href='edit_driver.php?id=" . htmlspecialchars($row['id']) . "' class='btn edit'><i class='fas fa-edit'></i></a>
                                <a href='delete_driver.php?id=" . htmlspecialchars($row['id']) . "' class='btn delete' onclick='return confirm(\"Are you sure you want to delete this driver?\")'><i class='fas fa-trash'></i></a>
                            </td>";
                            echo "</tr>";

                            $serialNumber++;
                        }
                    } else {
                        echo "<tr><td colspan='11'>No drivers found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </form>
</div>

    
</div>

<!-- JavaScript for 'Select All' functionality -->
<script>
    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('input[name="selected_drivers[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
    }

    // Implement search functionality
    function searchDrivers() {
        const input = document.querySelector('.search-bar');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#driversTableBody tr');

        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            let match = false;
            for (let i = 1; i < cells.length - 1; i++) { // Exclude checkbox and action columns
                if (cells[i].innerText.toLowerCase().includes(filter)) {
                    match = true;
                    break;
                }
            }
            row.style.display = match ? '' : 'none';
        });
    }
</script>
</body>
