<?php
include('./partials/header.php');
?>
<div class="registration-container mt-5 mb-5">
    <h2 style="text-align: center;">Registration Form</h2>


    <?php
    // Check if there's an error and display it
    if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
        echo "<div class='error-msg' style='color: red; text-align: center; margin-bottom: 15px;'>"
            . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']); // Clear the error after displaying it
    }

    // Check if there's a success message and display it
    if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
        echo "<div class='success-msg' style='color: green; text-align: center; margin-bottom: 15px;'>"
            . $_SESSION['success'] . "</div>";
        unset($_SESSION['success']); // Clear the success message after displaying it
    }
    ?>


    <form class="registration-form" action="admin/register_driver1.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="driver_name">Driver Name</label>
            <input type="text" id="driver_name" name="driver_name" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
            <label for="driver_photo">Driver Photo</label>
            <input type="file" id="driver_photo" name="driver_photo" required>
        </div>

        <div class="form-group">
            <label for="phone">Driver Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" maxlength="10" required>
        </div>

        <div class="form-group">
            <label for="dl_number">DL Number</label>
            <input type="text" id="dl_number" name="dl_number" placeholder="Enter your DL number" required>
        </div>

        <div class="form-group">
            <label for="area_postal_code">Area Postal Code</label>
            <input type="text" id="area_postal_code" name="area_postal_code" placeholder="Enter your postal code" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Enter your address" required>
        </div>

        <div class="form-group">
            <label for="vehicle_type">Vehicle Type</label>
            <select id="vehicle_type" name="vehicle_type" required>
                <option value="" disabled selected>Select your vehicle type</option>
                <option value="auto">Auto</option>
                <option value="car">Car</option>
                <option value="bus">Bus</option>
                <option value="truck">Truck</option>
                <option value="bike">Bike</option>
            </select>
        </div>

        <div class="form-group">
            <label>Signature</label>
            <p>Upload your signature (scanned file):</p>
            <input type="file" id="signature_file" name="signature_file" accept=".jpg, .jpeg, .png">
            <p>Or sign directly on your mobile device:</p>
            <div id="signature_pad" style="border: 1px solid #ccc; width: 100%; height: 200px; touch-action: none;margin:20px 0px;"></div>
            <button type="button" id="clear_signature">Clear</button>
            <input type="hidden" id="signature_data" name="signature_data">
        </div>

        <div class="form-group">
            <input type="submit" value="Register">
        </div>
    </form>
</div>


<style>
    .registration-container {
        width: 90%;
        margin: 0 auto;
    }

    /* Adjust canvas for mobile responsiveness */
    #signature_pad {
        border: 1px solid #ccc;
        width: 100%;
        height: 200px;
        touch-action: none;
    }

    @media (max-width: 600px) {
        #signature_pad {
            height: 150px; /* Reduce height for smaller screens */
        }
    }

    /* Make the form fields adapt to smaller screen sizes */
    .form-group input, 
    .form-group select {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
    }
</style>

<script>
    const canvas = document.createElement('canvas');
    const signaturePad = document.getElementById('signature_pad');
    signaturePad.appendChild(canvas);

    function resizeCanvas() {
        canvas.width = signaturePad.clientWidth;
        canvas.height = signaturePad.clientHeight;
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();  // Initial canvas setup

    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    const signatureFileInput = document.getElementById('signature_file');
    const clearSignatureButton = document.getElementById('clear_signature');
    const signatureDataInput = document.getElementById('signature_data');

    // Handle touch events for mobile devices
    canvas.addEventListener('touchstart', (e) => {
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches[0];
        lastX = touch.clientX - rect.left;
        lastY = touch.clientY - rect.top;
        signatureFileInput.disabled = true; // Disable file input when drawing
    });

    canvas.addEventListener('touchmove', (e) => {
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches[0];
        const offsetX = touch.clientX - rect.left;
        const offsetY = touch.clientY - rect.top;
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(offsetX, offsetY);
        ctx.stroke();
        [lastX, lastY] = [offsetX, offsetY];
    });

    canvas.addEventListener('touchend', () => isDrawing = false);

    canvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        [lastX, lastY] = [e.offsetX, e.offsetY];
        signatureFileInput.disabled = true; // Disable file input when drawing
    });

    canvas.addEventListener('mousemove', (e) => {
        if (!isDrawing) return;
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        [lastX, lastY] = [e.offsetX, e.offsetY];
    });

    canvas.addEventListener('mouseup', () => isDrawing = false);
    canvas.addEventListener('mouseout', () => isDrawing = false);

    signatureFileInput.addEventListener('change', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear canvas when file is uploaded
        canvas.style.pointerEvents = 'none'; // Disable drawing on canvas
    });

    clearSignatureButton.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        canvas.style.pointerEvents = 'auto'; // Re-enable canvas for drawing
        signatureFileInput.disabled = false; // Re-enable file input
        signatureFileInput.value = ''; // Clear the file input
    });

    document.querySelector('form').addEventListener('submit', (event) => {
        const signatureFile = signatureFileInput.files.length > 0;
        const signatureData = canvas.toDataURL();

        // Store signature data only if drawn
        if (!signatureFile) {
            signatureDataInput.value = signatureData;
        } else {
            signatureDataInput.value = ''; // Clear if using the uploaded file
        }
    });
</script>



<?php
include('./partials/footer.php');
?>
