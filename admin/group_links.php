<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pageTitle = 'Admin Panel'; 


// Include necessary files
include('conn.php'); // Ensure this file establishes a secure connection to your database
include('./partials/header.php'); // Optional: Include your header partial
include('./partials/sidebar.php'); // Optional: Include your sidebar partial

// Initialize variables
$whatsappLink = '';
$telegramLink = '';
$message = '';

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
        $message = "Error initializing group links. Please try again.";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Trim inputs to remove extra spaces
    $newWhatsAppLink = trim($_POST['whatsapp_link']);
    $newTelegramLink = trim($_POST['telegram_link']);

    // Validate URLs
    if (!filter_var($newWhatsAppLink, FILTER_VALIDATE_URL)) {
        $message = "Invalid WhatsApp link. Please enter a valid URL.";
    } elseif (!filter_var($newTelegramLink, FILTER_VALIDATE_URL)) {
        $message = "Invalid Telegram link. Please enter a valid URL.";
    } else {
        // Prepare and bind the update statement
        $updateQuery = "UPDATE group_links SET whatsapp_link = ?, telegram_link = ? WHERE id = 1";
        $stmt = $conn->prepare($updateQuery);

        if ($stmt) {
            $stmt->bind_param('ss', $newWhatsAppLink, $newTelegramLink);
            if ($stmt->execute()) {
                // Redirect with success message
                header('Location: group_links.php?message=Links updated successfully');
                exit;
            } else {
                error_log("Error updating links: " . $stmt->error);
                $message = "Error updating links. Please try again.";
            }
            $stmt->close();
        } else {
            error_log("Error preparing statement: " . $conn->error);
            $message = "Error preparing statement. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Group Links</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 12px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            color: #28a745;
            margin-top: 15px;
        }

        .error {
            text-align: center;
            color: #dc3545;
            margin-top: 15px;
        }

        .current-links {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .current-links h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .current-links p {
            font-size: 16px;
            color: #555;
        }

        .current-links a {
            color: #007bff;
            text-decoration: none;
        }

        .current-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            form, .current-links {
                padding: 20px;
            }

            button {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Manage Group Links</h1>
    
    <?php if (!empty($message)): ?>
        <p class="<?php echo (strpos($message, 'successfully') !== false) ? 'message' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    
    <?php if (isset($_GET['message'])): ?>
        <p class="message"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>
    
    <form method="post">
        <label for="whatsapp_link">WhatsApp Link:</label>
        <input type="text" id="whatsapp_link" name="whatsapp_link" value="<?php echo $whatsappLink; ?>" required>
        
        <label for="telegram_link">Telegram Link:</label>
        <input type="text" id="telegram_link" name="telegram_link" value="<?php echo $telegramLink; ?>" required>
        
        <button type="submit">Update Links</button>
    </form>

    <!-- <div class="current-links">
        <h2>Current Stored Links</h2>
        <p><strong>WhatsApp Link:</strong> 
            <?php 
                if ($whatsappLink) {
                    echo '<a href="' . $whatsappLink . '" target="_blank">' . $whatsappLink . '</a>';
                } else {
                    echo 'Not Set';
                }
            ?>
        </p>
        <p><strong>Telegram Link:</strong> 
            <?php 
                if ($telegramLink) {
                    echo '<a href="' . $telegramLink . '" target="_blank">' . $telegramLink . '</a>';
                } else {
                    echo 'Not Set';
                }
            ?>
        </p>
    </div> -->
</body>
</html>
