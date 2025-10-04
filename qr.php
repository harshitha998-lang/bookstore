<?php
$upi_id = "harshithashetty486-1@okaxis"; // Your UPI ID
$payee_name = "Harshitha Shetty";        // Your name

// Generate UPI URL
$upi_url = "upi://pay?pa={$upi_id}&pn=" . urlencode($payee_name);

// Generate QR code using Google Chart API
$qr_code_url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" . urlencode($upi_url);

// Output QR image
echo '<img src="' . $qr_code_url . '" alt="UPI QR Code">';
?>