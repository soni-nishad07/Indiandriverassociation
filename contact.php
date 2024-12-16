<?php
    include('./partials/header.php');
?>

    <div class="info-contact">
        <div class="info-content-a">
            <h1>Contact Us</h1>
        </div>
    </div>

    <div class="get">
        <h1 style="text-align: center;">Get In Touch</h1>
        <p>Have questions or need assistance? Reach out to us, and we'll be happy to help. Whether it's a query about your membership, or guidance on filling out the form, or anything else related to our association, we're here for you.</p>
        <div class="contact-us">
        <div class="contact-form-container">
            <form action="/submit-form" method="POST">

            <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Your name " required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" placeholder="Your Phone Number" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" placeholder="Your Address" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
        <div class="contactImg">
            <img src="images/contact2.png" alt="Contact Us">
        </div>
    </div>
    </div>

    <?php
    include('./partials/footer.php');
    ?>
</body>
</html>
