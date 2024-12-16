<?php
include('conn.php');

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=staff_login_summary.csv');

$query = "SELECT s.staff_id, s.staff_name, l.login_time, l.logout_time 
          FROM staff_members s 
          JOIN staff_login_summary l 
          ON s.staff_id = l.staff_id 
          ORDER BY l.login_time DESC";
$result = $conn->query($query);

$output = fopen('php://output', 'w');

fputcsv($output, array('Staff ID', 'Staff Name', 'Login Time', 'Logout Time'));

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
