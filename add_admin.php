<?php
include 'db.php'; // Update with your actual DB connection file name

$adminEmail = 'admin1@gmail.com';
$adminPassword = password_hash('admin123', PASSWORD_DEFAULT); // Secure hashed password
$username = 'admin1';
$role = 'admin';

// Check if admin already exists
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$adminEmail'");
if (mysqli_num_rows($check) == 0) {
    $insert = "INSERT INTO users (name, email, password,user_type)
               VALUES ('$username', '$adminEmail', '$adminPassword', '$role')";
    if (mysqli_query($conn, $insert)) {
        echo "Admin inserted successfully.";
    } else {
        echo "Error inserting admin: " . mysqli_error($conn);
    }
} else {
    echo "Admin already exists.";
}
?>