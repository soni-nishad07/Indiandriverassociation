<?php
include('./partials/header.php');
?>
<div class="registration-container">
    <h2 style="text-align: center;">Registration Form</h2>
    <form class="registration-form" action="admin/register_driver.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="driver_name">Driver Name</label>
            <input type="text" id="driver_name" name="driver_name" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
            <label for="driver_photo">Driver Photo</label>
            <input type="file" id="driver_photo" name="driver_photo" required>
        </div>

        <div class="form-group">
            <label for="phone"> Driver Phone Number</label>
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
            <input type="file" id="signature_file" name="signature_file" accept=".jpg, .jpeg, .png" onchange="disableCanvas()" required>
            <p>Or sign directly on your mobile device:</p>
            <div id="signature_pad" style="border: 1px solid #ccc; width: 100%; height: 200px; touch-action: none;"></div>
            <button type="button" id="clear_signature" disabled>Clear</button>
            <input type="hidden" id="signature_data" name="signature_data">
        </div>

        <div class="form-group">
            <input type="submit" value="Register">
        </div>
    </form>
</div>

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

    // Drawing events
    canvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        [lastX, lastY] = [e.offsetX, e.offsetY];
    });

    canvas.addEventListener('mousemove', (e) => {
        if (!isDrawing) return;
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        [lastX, lastY] = [e.offsetX, e.offsetY];
    });

    canvas.addEventListener('mouseup', () => {
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

<?php
include('./partials/footer.php');
?>
