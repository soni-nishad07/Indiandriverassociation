<?php
include('./partials/header.php');
?>
<div class="registration-container mt-5 mb-5">
    <h2 style="text-align: center;">Registration Form</h2>


    <?php
// Check if there's an error and display it
if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    echo "<div class='error-msg' style='color: red; text-align: center; margin-bottom: 15px;' id='error-msg'>"
        . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']); // Clear the error after displaying it
}

// Check if there's a success message and display it
if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
    echo "<div class='success-msg' style='color: green; text-align: center; margin-bottom: 15px;' id='success-msg'>"
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
const signatureFile = document.getElementById('signature_file');
const clearBtn = document.getElementById('clear_signature');
const hiddenSignatureInput = document.getElementById('signature_data');

signaturePad.appendChild(canvas);
canvas.width = signaturePad.clientWidth;
canvas.height = signaturePad.clientHeight;
const ctx = canvas.getContext('2d');

let isDrawing = false;
let lastX = 0;
let lastY = 0;

// Function to handle drawing
function draw(x, y) {
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(x, y);
    ctx.stroke();
    [lastX, lastY] = [x, y];
}

// Mouse events
canvas.addEventListener('mousedown', (e) => {
    isDrawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
});

canvas.addEventListener('mousemove', (e) => {
    if (!isDrawing) return;
    draw(e.offsetX, e.offsetY);
});

canvas.addEventListener('mouseup', () => {
    isDrawing = false;
    hiddenSignatureInput.value = canvas.toDataURL(); // Save signature data
    signatureFile.disabled = true; // Disable file upload when signature is drawn
    clearBtn.disabled = false; // Enable the clear button
});

// Touch events for mobile devices
canvas.addEventListener('touchstart', (e) => {
    isDrawing = true;
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    lastX = touch.clientX - rect.left;
    lastY = touch.clientY - rect.top;
});

canvas.addEventListener('touchmove', (e) => {
    if (!isDrawing) return;
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    draw(x, y);
    e.preventDefault(); // Prevent scrolling while drawing
});

canvas.addEventListener('touchend', () => {
    isDrawing = false;
    hiddenSignatureInput.value = canvas.toDataURL(); // Save signature data
    signatureFile.disabled = true; // Disable file upload when signature is drawn
    clearBtn.disabled = false; // Enable the clear button
});

// Clear signature
clearBtn.addEventListener('click', () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hiddenSignatureInput.value = ''; // Clear signature data
    signatureFile.disabled = false; // Re-enable file upload
    clearBtn.disabled = true; // Disable clear button
});

// Disable canvas if file is selected
function disableCanvas() {
    if (signatureFile.files.length > 0) {
        canvas.style.pointerEvents = 'none'; // Disable canvas drawing
        hiddenSignatureInput.value = ''; // Clear canvas data
        clearBtn.disabled = true; // Disable clear button
    } else {
        canvas.style.pointerEvents = 'auto'; // Enable canvas drawing
    }
}

// Disable file input when the canvas is drawn on
document.querySelector('form').addEventListener('submit', (event) => {
    if (!signatureFile.files.length && !hiddenSignatureInput.value) {
        event.preventDefault(); // Prevent form submission if neither signature method is used
        alert('Please provide a signature using one of the methods.');
    }
});
</script>


<script>
// Automatically hide messages after 3 seconds
setTimeout(function() {
    var errorMsg = document.getElementById('error-msg');
    var successMsg = document.getElementById('success-msg');
    
    if (errorMsg) {
        errorMsg.style.display = 'none';
    }
    if (successMsg) {
        successMsg.style.display = 'none';
    }
}, 3000); // 3000 milliseconds = 3 seconds
</script>

<?php
include('./partials/footer.php');
?>
