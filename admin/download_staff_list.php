
<?php
include('conn.php');

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="staff_list.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Staff Name', 'Email', 'Phone', 'Role', 'Status')); // CSV header

$query = "SELECT staff_id, staff_name, email, phone, role, status FROM staff_members";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
?>
