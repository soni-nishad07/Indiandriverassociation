<?php  
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
include('conn.php');

$pageTitle = 'Admin Panel'; 


include('./partials/header.php'); // Ensure this file exists or remove if not needed
include('./partials/sidebar.php'); // Ensure this file exists or remove if not needed

// Initialize variables
$whatsappLink = '';
$telegramLink = '';

// Fetch existing links from the database
$query = "SELECT whatsapp_link, telegram_link FROM group_links WHERE id = 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $links = $result->fetch_assoc();
    $whatsappLink = htmlspecialchars($links['whatsapp_link']);
    $telegramLink = htmlspecialchars($links['telegram_link']);
} else {
    // Optionally, insert a default row if it doesn't exist
    $insertQuery = "INSERT INTO group_links (id, whatsapp_link, telegram_link) VALUES (1, '', '')";
    if ($conn->query($insertQuery) === TRUE) {
        // Row inserted successfully
    } else {
        error_log("Error inserting default row: " . $conn->error);
        // Optionally, display an error message to the admin
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Social Groups Management</title>
    <link rel="stylesheet" href="css/admin.css"> 
    <style>


        .social_content {
    margin: auto;
    text-align: center;
    justify-content: center;
    display: flex;
    flex-wrap: wrap;
    margin: 60px auto;
}

        .social-links {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    gap: 15px;
    margin-top: 50px;
    flex-wrap: wrap;
}


        .link-item {
            background-color: #fff;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        .link-item h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 18px;
        }

        .link-item p {
            margin: 0;
            font-size: 16px;
            color: #555;
            word-break: break-all;
        }

        .no-link {
            color: #dc3545;
            font-style: italic;
        }

        .manage-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            /* margin-left: 50%; */
            /* transform: translateX(-50%); */
        }

        .manage-button:hover {
            background-color: #0056b3;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .manage-button {
                margin-left: 0;
                transform: none;
            }

            .link-item {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="sidebar2">

<div class="container">
        
<div class="social_content">
        <a href="group_links.php" class="manage-button" type="button">Add / Update Links</a>
        <div class="social-links">
            <div class="link-item">
                <h3>WhatsApp Group Link:</h3>
                <?php if (!empty($whatsappLink)): ?>
                    <p><a href="<?php echo $whatsappLink; ?>" target="_blank"><?php echo $whatsappLink; ?></a></p>
                <?php else: ?>
                    <p class="no-link">No WhatsApp link set.</p>
                <?php endif; ?>
            </div>

            <div class="link-item">
                <h3>Telegram Group Link:</h3>
                <?php if (!empty($telegramLink)): ?>
                    <p><a href="<?php echo $telegramLink; ?>" target="_blank"><?php echo $telegramLink; ?></a></p>
                <?php else: ?>
                    <p class="no-link">No Telegram link set.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
                </div>

</body>
</html>
