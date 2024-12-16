<?php
// Include the database connection file
include('conn.php');

// Fetch data from the 'driver_info' table
$sql = "SELECT id, driver_name, phone, dl_number, area_postal_code, address, vehicle_type FROM driver_info";
$res = mysqli_query($conn, $sql); // Use $conn here

// Start building the HTML table structure
$html = '<table border="1">';
$html .= '<tr>
            <th>Id</th>
            <th>Driver Name</th>
            <th>Phone</th>
            <th>DL Number</th>
            <th>Area/Postal Code</th>
            <th>Address</th>
            <th>Vehicle Type</th>
          </tr>';

// Loop through each row and add it to the HTML table
while ($row = mysqli_fetch_assoc($res)) {
    $html .= '<tr>
                <td>' . htmlspecialchars($row['id']) . '</td>
                <td>' . htmlspecialchars($row['driver_name']) . '</td>
                <td>' . htmlspecialchars($row['phone']) . '</td>
                <td>' . htmlspecialchars($row['dl_number']) . '</td>
                <td>' . htmlspecialchars($row['area_postal_code']) . '</td>
                <td>' . htmlspecialchars($row['address']) . '</td>
                <td>' . htmlspecialchars($row['vehicle_type']) . '</td>
              </tr>';
}

// Close the table structure
$html .= '</table>';

// Set headers for the file download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=Driver_Info.xls');

// Output the HTML content
echo $html;

// Close the database connection
$conn->close();
?>
