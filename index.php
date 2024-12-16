<?php
// session_start(); 

include('./partials/header.php');

if (isset($_SESSION['message'])) {
    echo '<p class="message">' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']); 
}
?>



<div class="info">
    <video autoplay muted loop playsinline id="bg-video">
        <source src="./images/video2.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="info-content">
        <h1><span class="wel">Welcome to</span>  <br/><span class="ida">Indian <br> Drivers <br> Association</span></h1>
        <!-- <p>Connecting and Empowering Drivers Across India</p> -->
        <a href="contact.php" class="btn-info">Get In Touch</a>
    </div>
</div>


<div class="about">
    <div class="about-content">
        <h2>About Us</h2>
        <p>We are dedicated to empowering drivers across India. Whether you're an auto driver, car/taxi driver, bus operator, or responsible for transporting goods, we're here to support you. Our mission is to provide a unified platform where drivers can connect, share information, and receive assistance with government-related issues. Join us in making a difference!</p>
    </div>
    <div class="about-img">
        <img src="images/about_bg.jpg" alt="" class="aboutImg">
        <!-- <img src="images/Rectangle 39.png" class="rect" alt=""> -->
    </div>
</div>




<div class="icons">
        <a href="registration.php">
        <img src="images/auto.png" alt="">
        <span>Autos</span>
    </a>
    


    <a href="registration.php">
        <img src="images/Car.png" alt="">
        <span>Cars</span>
    </a>
    <a href="registration.php">
        <img src="images/Bus.png" alt="">
        <span class="bus">Bus</span>
    </a>
        <a href="registration.php">
        <img src="images/truck.png" alt="">
        <span>Trucks</span>
    </a>
    
    <a href="registration.php">
        <img src="images/Bike.png" alt="">
        <span class="bus">Bike</span>
    </a>
</div>


<div class="choose">
    <div class="content">
        <h1>What Do We Offer</h1>
        <p>Join our community, where your rights are our priority, and responsible driving is our commitment. Here's what we offer:</p>
    </div>
    <div class="cards">
        <div class="card1">
            <img src="images/card1.png" alt="" />
           <div class="card_content">
           <h4>Professional Community</h4>
           <p>A platform to connect with fellow drivers across India.</p>
           </div>
        </div>
        <div class="card2">
            <img src="images/card2.png" alt="" />
            <div class="card_content">
            <h4>Support and Assistance</h4>
            <p>Help with resolving government-related issues such as licensing or permits.</p>
</div>
        </div>
        <div class="card3">
            <img src="images/card3.png" alt="" />
            <div class="card_content">
            <h4>Member Benefits</h4>
            <p>Exclusive discounts on vehicle insurance, fuel, and repairs.</p>
</div>
        </div>
       <div class="card3">
            <img src="images/card4.png" alt="" />
            <div class="card_content">
            <h4>Access to Resources and Tools</h4>
            <p class="p4">Tools for tracking driving hours, fuel expenses, and vehicle maintenance.</p>
</div>
        </div> 
    </div>
</div>

<?php include('./partials/footer.php'); ?>



</body>
</html>
