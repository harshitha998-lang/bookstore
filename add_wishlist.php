<?php
session_start();
include('db.php'); // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If user is not logged in, show a login popup
    echo '<script type="text/javascript">
            alert("You are not logged in. Please log in to add to the wishlist.");
            window.location.href = "login.php"; // Redirect to login page
          </script>';
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book_id']; // Get book_id from the URL

// Check if the book is already in the wishlist
$query = "SELECT * FROM wishlist WHERE user_id = ? AND book_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$result = $stmt->get_result();

// If the book is not already in the wishlist, add it
if ($result->num_rows == 0) {
    $query = "INSERT INTO wishlist (user_id, book_id, added_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();

    // Show popup modal for book added to wishlist
    echo '<script type="text/javascript">
            alert("Book added to your wishlist!");
           window.location.href = "books.php"; 
           
          </script>';
} else {
    // If already in the wishlist, show a message (optional)
    echo '<script type="text/javascript">
            alert("This book is already in your wishlist.");
            window.location.href = "books.php"; // Redirect to books page
          </script>';
}

$stmt->close();
$conn->close();
?>