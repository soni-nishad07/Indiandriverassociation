
<?php
// session_start(); 
include('session.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian driver association</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="css/registration.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <style>
        a {
            color: inherit;
            text-decoration: none;
        }

        .floating-icons {
            position: fixed;
            top: 50%;
            right: 20px;
            z-index: 1000;
        }

        .floating-icon {
            position: relative;
            width: 40px;
            height: 40px;
            background-color: #25D366;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 24px;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .floating-icon.telegram {
            background-color: #0088cc;
        }

        .floating-icon:hover {
            transform: scale(1.1);
        }

        .floating-icon .tooltip {
            visibility: hidden;
            width: 160px;
            background-color: #4169E1;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: opacity 0.3s, transform 0.3s;
            white-space: nowrap;
            font-size: 14px;
            z-index: 1;
        }

        .floating-icon .tooltip::after {
            content: "";
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent transparent #555;
        }

        .floating-icon:hover .tooltip {
            visibility: visible;
            opacity: 1;
            transform: translateY(-50%) translateX(-10px);
        }

        .translate-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-right: 8px;
        }

        .translate-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            padding: 10px;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        #google_translate_element select {
            width: 100%;
            padding: 8px;
            border: none;
            background-color: #f0f0f0;
            font-size: 14px;
        }

        #google_translate_element select:hover {
            background-color: #e0e0e0;
        }

        #google_translate_element .goog-te-combo {
            padding: 8px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .floating-icons {
                right: 10px;
            }

            .floating-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }

            .floating-icon .tooltip {
                width: 140px;
                right: 50px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="sign-in">

    <div class="sign-in">
    <?php if (isset($_SESSION['name'])): ?>
        <!-- Show logged-in admin name -->
        <span style="color:white; margin-right:8px; font-size:22px">
            Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!
        </span>
        <a href="admin/logout.php" class="button" type="button" style="text-decoration: none; color:white; margin-right:8px">Logout</a>
    <?php elseif (isset($_SESSION['staff_name'])): ?>
        <!-- Show logged-in staff name -->
        <span style="color:white; margin-right:8px; font-size:22px">
            Welcome, <?php echo htmlspecialchars($_SESSION['staff_name']); ?>!
        </span>
        <a href="admin/staff_log.php" class="button" type="button" style="text-decoration: none; color:white; margin-right:8px">Logout</a>
    <?php else: ?>
        <!-- Show Sign In button if no one is logged in -->
        <a style="text-decoration: none; color:white; margin-right:8px" href="./staff_login.php" class="button" type="button">Sign In</a>
    <?php endif; ?>
</div>



        

        <!-- Language Change Dropdown -->
        <div class="dropdown">
            <button class="translate-button">Change Language</button>
            <div id="google_translate_element" class="dropdown-content"></div>
        </div>

        <!-- Social Media Icons -->
        <div class="m_social-icons">
            <a class="m_icon" href="https://www.facebook.com/Indiandriversassociation/"><i class="fa-brands fa-facebook-f"></i></a>
            <a class="m_icon" href="https://www.instagram.com/indiandriversassociation?igsh=dW9za3FnZ2xmMHcz&utm_source=qr"><i class="fa-brands fa-instagram"></i></a>
            <a class="m_icon" href="#"><i class="fa-brands fa-twitter"></i></a>
        </div>
    </div>

    <div class="red_line"></div>



    <div class="contact">
        <div class="social">
            <a href="https://www.facebook.com/Indiandriversassociation/"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/indiandriversassociation?igsh=dW9za3FnZ2xmMHcz&utm_source=qr"><i
                    class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
        </div>
        <div class="contact-info">
            <a href="tel:+91 6362301918"><i class="fa-solid fa-phone"></i>+91 6362301918</a>
            <a href="mailto:indiandriversassociation.org@gmail.com"><i
                    class="fa-regular fa-envelope"></i>indiandriversassociation.org@gmail.com</a>
        </div>
    </div>






    <nav class="navbar navbar-expand-lg bg-body-tertiary  ">
        <div class="container-fluid ">
            <a class="navbar-brand nav " href="#"> <img src="images/logo.png" alt="Logo"  class="header_logo">
            </a>



            <div class="join-head">
                <span class="association "> INDIAN DRIVERS ASSOCIATION (R)
                </span>
                <a href="registration.php">
                    <div class="join">
                        JOIN
                    </div>
                </a>
                <div class="join_contact"> <a href="tel:+91 6362301918">+91 6362301918</a>
                </div>
                <div class="join_association">   <a href="mailto:indiandriversassociation.org@gmail.com">indiandriversassociation.org@gmail.com</a>
     
                </div>
            </div>




            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse nav-elements" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About Us</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="registration.php">Registration</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>

            </div>
        </div>
    </nav>


    <div class="floating-icons">
        <?php
        include('admin/conn.php');

        // Fetching the links from the database
        $query = "SELECT whatsapp_link, telegram_link FROM group_links WHERE id = 1";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $links = $result->fetch_assoc();
            $whatsappLink = htmlspecialchars($links['whatsapp_link']);
            $telegramLink = htmlspecialchars($links['telegram_link']);
        } else {
            $whatsappLink = ''; // Default link if not found
            $telegramLink = ''; // Default link if not found
        }
        ?>
        <a href="<?php echo $whatsappLink; ?>" class="floating-icon whatsapp" target="_blank" rel="noopener noreferrer"
            aria-label="Join WhatsApp Group">
            <i class="fab fa-whatsapp"></i>
            <span class="tooltip">Join WhatsApp Group</span>
        </a>
        <a href="<?php echo $telegramLink; ?>" class="floating-icon telegram" target="_blank" rel="noopener noreferrer"
            aria-label="Join Telegram Group">
            <i class="fab fa-telegram-plane"></i>
            <span class="tooltip">Join Telegram Group</span>
        </a>
    </div>

    <!-- Google Translate Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,ta,bn,te,ml,kn,mr,gu',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript"
        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script type="text/javascript"
        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>

</html>