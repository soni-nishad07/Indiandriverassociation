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
                <input type="text" placeholder="Search..." class="search-bar" onkeyup="filterTable()">
            </div>
            <div class="btns">
                <button type="submit" form="driversForm" class="btn download">
                    <i class="fas fa-download"></i> Download
                </button>
                <a href="../manual_reg.php" class="btn add">
                    <i class="fas fa-plus"></i> Add New Driver
                </a>

                  <a href="export_driver_info.php" class="mt-1 mb-1">
                  <button  type="button" class="btn btn-primary">
        <i class="fas fa-file-excel"></i> Export 
    </button>

                  </a>

            </div>
        </div>
    </div>

    <div class="table-responsive">
        <form id="driversForm" action="download_driver_info.php" method="POST">
            <div class="actions">
                <div class="date-filters">
                    <input type="date" id="startDate" name="start_date" class="date-input" placeholder="Start Date" onchange="filterTable()">
                    <input type="date" id="endDate" name="end_date" class="date-input" placeholder="End Date" onchange="filterTable()">
                </div>
            </div>
            <div class="driver-table">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                            </th>
                            <th>Id</th>
                            <th>Driver Name</th>
                            <th>Driver Photo</th>
                            <th>Phone</th>
                            <th>DL Number</th>
                            <th>Area/Postal Code</th>
                            <th>Address</th>
                            <th>Vehicle Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="driversTableBody">
                        <?php
                        // Fetch driver data from the database
                        $query = "SELECT id, driver_name, driver_photo, phone, dl_number, area_postal_code, address, vehicle_type, signature_file, registration_date FROM driver_info";
                        $result = $conn->query($query);

                        while ($row = $result->fetch_assoc()): ?>
                        <tr data-registration-date="<?php echo $row['registration_date']; ?>">
                            <td>
                                <input type="checkbox" name="selected_drivers[]" value="<?php echo $row['id']; ?>" class="driver-checkbox">
                            </td>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['driver_name']); ?></td>
                            <td>
                                <?php if ($row['driver_photo']): ?>
                                <img src="../images/<?php echo $row['driver_photo']; ?>" alt="Driver Photo" style="width: 50px; height: 50px;">
                                <?php else: ?>
                                No photo
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['dl_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['area_postal_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                            <td>
                                <a href='edit_driver.php?id=<?php echo htmlspecialchars($row['id']); ?>' class='btn edit'><i class='fas fa-edit'></i></a>
                                <a href='delete_driver.php?id=<?php echo htmlspecialchars($row['id']); ?>' class='btn delete' onclick='return confirm("Are you sure you want to delete this driver?")'><i class='fas fa-trash'></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.driver-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked && checkbox.closest('tr').style.display !== 'none';
    });
}

function filterTable() {
    const input = document.querySelector('.search-bar');
    const filter = input.value.toLowerCase();
    const startDate = new Date(document.getElementById('startDate').value);
    const endDate = new Date(document.getElementById('endDate').value);
    const rows = document.querySelectorAll('#driversTableBody tr');

    rows.forEach(row => {
        const cells = row.getElementsByTagName('td');
        const registrationDate = new Date(row.getAttribute('data-registration-date'));
        let found = true;

        // Filter based on search input
        if (filter) {
            found = false;
            for (let i = 1; i < cells.length; i++) {
                if (cells[i].innerText.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }

        // Filter based on date range
        if (found && (startDate || endDate)) {
            if (startDate && registrationDate < startDate) {
                found = false;
            }
            if (endDate && registrationDate > endDate) {
                found = false;
            }
        }

        row.style.display = found ? '' : 'none';
    });

    // Update Select All checkbox state
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.driver-checkbox');
    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
    selectAll.checked = allChecked; // Check Select All only if all are checked
    selectAll.indeterminate = anyChecked && !allChecked; // Set indeterminate state if some but not all are checked
}
</script>
</body>
</html>
