



<?php
include('./partials/header.php');
?>



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
            <div id="signature_pad" style="border: 1px solid #ccc; width: 100%; height: 200px; touch-action: none;margin:20px 0px;"></div>
            <button type="button" id="clear_signature" disabled>Clear</button>
            <input type="hidden" id="signature_data" name="signature_data">
        </div>

        <div class="form-group">
            <input type="submit" value="Register">
        </div>
    </form>
</div>




<script>const canvas = document.createElement('canvas');
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

<?php
include('./partials/footer.php');
?>
