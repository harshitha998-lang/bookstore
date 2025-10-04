<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    // Show a popup message instead of redirecting
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login Required</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: rgba(0, 0, 0, 0.4);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .popup {
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0,0,0,0.2);
                text-align: center;
                animation: fadeIn 0.4s ease-in-out;
            }
            .popup a {
                display: inline-block;
                margin-top: 15px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: scale(0.9); }
                to { opacity: 1; transform: scale(1); }
            }
        </style>
    </head>
    <body>
        <div class="popup">
            <h2>You are not logged in</h2>
            <p>Please login to add items to your cart.</p>
            <a href="loginindex.php">Go to Login Page</a>
        </div>
    </body>
    </html>';
    exit;
}

// If user is logged in, proceed with cart logic
if (isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $user_id = $_SESSION['user_id'];
    $quantity = 1;

    // Check if the item already exists in cart
    $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
    $check->bind_param("ii", $user_id, $book_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update quantity
        $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND book_id = ?");
        $update->bind_param("ii", $user_id, $book_id);
        $update->execute();
    } else {
        // Insert new entry
        $insert = $conn->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $book_id, $quantity);
        $insert->execute();
    }

    // Decrease stock by 1
    $decreaseStock = $conn->prepare("UPDATE books SET stock = stock - 1 WHERE book_id = ? AND stock > 0");
    $decreaseStock->bind_param("i", $book_id);
    $decreaseStock->execute();

    header("Location: book_details.php?id=$book_id&added=1");
    exit;
} else {
    echo "Invalid request!";
}
?>