<?php
// UPI payment URL (no fixed amount)
$paymentUrl = "upi://pay?pa=harshithashetty486-1@okaxis&pn=Harshitha Shetty&cu=INR";

// Encode URL
$encodedUrl = urlencode($paymentUrl);

// Generate QR code using api.qrserver.com
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=$encodedUrl";

// Output HTML with styles
echo '
<div style="text-align: center; padding: 30px; font-family: Arial, sans-serif; background: #f9f9f9; border-radius: 15px; width: 300px; margin: 50px auto; box-shadow: 0 6px 14px rgba(0,0,0,0.1);">
    <h2 style="color: #222; margin-bottom: 20px;">Scan to Pay</h2>
    <img id="qrImage" src="' . $qrCodeUrl . '" alt="QR Code" style="width: 250px; height: 250px; border-radius: 12px; border: 4px solid #0077cc;">
    <br><br>
    <a href="' . $qrCodeUrl . '" download="upi_qr.png">
        <button style="padding: 10px 20px; background-color: #0077cc; color: white; border: none; border-radius: 5px; font-size: 15px; cursor: pointer; transition: background 0.3s;">
            Download QR
        </button>
    </a>
</div>';
?>