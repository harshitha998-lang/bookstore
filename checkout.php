<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$query = "SELECT cart.id, cart.quantity, books.title, books.price 
          FROM cart 
          JOIN books ON cart.book_id = books.book_id 
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$grand_total = 0;
while ($row = $result->fetch_assoc()) {
    $row['total'] = $row['price'] * $row['quantity'];
    $grand_total += $row['total'];
    $cart_items[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    if ($cart_items && $fullname && $email && $phone && $address) {
        // Insert into orders table
        $order_query = "INSERT INTO orders (user_id, fullname, email, phone, address, total_amount, payment_method, order_date) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("issssis", $user_id, $fullname, $email, $phone, $address, $grand_total, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Insert each order item
        foreach ($cart_items as $item) {
            $item_query = "INSERT INTO order_items (order_id, book_title, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($item_query);
            $stmt->bind_param("isid", $order_id, $item['title'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Clear cart
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");

        header("Location: order_sucess.php?order_id=" . $order_id);
        exit();
    } else {
        $error = "Please fill all the details!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 40px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        .billing, .summary {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .billing {
            flex: 2;
        }
        .summary {
            flex: 1;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        h2 {
            margin-top: 0;
        }
        .btn {
            background: #00587a;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn:hover {
            background: #27ae60;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        @media(max-width: 768px) {
            .container {
                flex-direction: column;
            }
        }
/* Navigation Bar */
    .navbar {
      background-color: #0f3057;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .logo {
      color: #fff;
      font-size: 1.8rem;
      font-weight: bold;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 25px;
    }

    .nav-links li a {
      text-decoration: none;
      color: #fff;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .nav-links li a:hover {
      color: #ffcc29;
    }


    </style>
    </style>
</head>
<body>
<nav class="navbar">
  <div class="logo">BookNest</div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="categories.php">Categories</a></li>
     <li><a href="cart.php">About Us</a></li>
    <li><a href="cart.php">🛒</a></li>
    <li><a href="wishlist.php">❤️</a></li>
    <li><a href="loginindex.php">👤</a></li>
  </ul>
</nav>

<h2>Checkout</h2>

<form method="POST">
<div class="container">

    <div class="billing">
        <h3>Billing Details</h3>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <label>Full Name</label>
        <input type="text" name="fullname" required pattern="[A-Za-z\s]+" title="Only letter ans spaces allowed">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone</label>
        <input type="text" name="phone" required minlength="10" maxlength="10">

        <label>Address</label>
        <textarea name="address" rows="4" required></textarea>

        <label>Payment Method</label>
        <select name="payment_method" required>
            <option value="COD">Cash on Delivery</option>
            <option value="Online">Online Payment</option>
        </select>

        <button type="submit" name="place_order" class="btn">Place Order</button>

    </div>


    <div class="summary">
        <h3>Order Summary</h3>
        <?php if ($cart_items): ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="summary-item">
                    <span><?php echo $item['title']; ?> x<?php echo $item['quantity']; ?></span>
                    <span>₹<?php echo $item['total']; ?></span>
                </div>
            <?php endforeach; ?>
            <div class="summary-item total">
                <span>Total</span>
                <span>₹<?php echo $grand_total; ?></span>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
<?php
// UPI payment URL (no fixed amount)
$paymentUrl = "upi://pay?pa=sohal.mjs@okicici&pn=Sohal&cu=INR";

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
    </div>

</div>


</body>
</html>