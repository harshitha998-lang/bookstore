<?php
include 'db.php';

$cart_id = $_POST['cart_id'];

if (isset($_POST['increase'])) {
    mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE id = $cart_id");
} elseif (isset($_POST['decrease'])) {
    mysqli_query($conn, "UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = $cart_id");
}

header("Location: cart.php");
exit;