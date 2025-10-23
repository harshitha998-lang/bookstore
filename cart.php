<?php
session_start();
include 'db.php'; // DB connection
if (!isset($_SESSION['user_id'])) {
    header("Location: loginindex.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Assume user is logged in

// Fetch cart items for this user
$sql = "SELECT c.id as cart_id, b.book_id as book_id, b.title, b.author, b.price, b.image, c.quantity
        FROM cart c
        JOIN books b ON c.book_id = b.book_id
        WHERE c.user_id = $user_id";
$result = mysqli_query($conn, $sql);

// Calculate subtotal
$subtotal = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fdfdfd;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            padding: 30px;
            gap: 40px;
        }
        .cart-items {
            flex: 2;
        }
        .cart-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
        }
        .cart-item img {
            width: 130px;
            height: auto;
            margin-right: 20px;
            border-radius: 6px;
        }
        .cart-item-details {
            flex: 1;
        }
        .cart-item-details h3 {
            margin: 0;
            font-size: 18px;
        }
        .cart-item-details p {
            margin: 6px 0;
            font-size: 14px;
            color: #555;
        }
        .cart-item-price,
        .cart-item-qty,
        .cart-item-subtotal {
            width: 100px;
            text-align: center;
        }
        .cart-totals {
            flex: 1;
            border: 1px solid #ddd;
            padding: 25px;
            border-radius: 10px;
            background: #fff;
            height: fit-content;
        }
        .cart-totals h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .checkout-btn {
            background: goldenrod;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        .qty-box {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .qty-box button {
            padding: 5px 10px;
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 20px;
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .cart-item img {
                width: 100%;
                max-width: 200px;
                margin-bottom: 15px;
            }

            .cart-item-price,
            .cart-item-qty,
            .cart-item-subtotal {
                width: 100%;
                text-align: left;
                margin-top: 10px;
            }

            .cart-totals {
                width: 100%;
                margin-top: 30px;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
                padding-top: 10px;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .logo {
                margin-bottom: 10px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 22px;
            }

            .checkout-btn {
                font-size: 14px;
                padding: 10px 20px;
            }

            .cart-item-details h3 {
                font-size: 16px;
            }

            .cart-item-details p {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
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

<h1 style="padding: 30px 30px 0; font-size: 28px;"><center>🛒 Your Cart</center></h1>
<div class="container">
    <div class="cart-items">
        <?php while($row = mysqli_fetch_assoc($result)): 
            $item_total = $row['price'] * $row['quantity'];
            $subtotal += $item_total;
        ?>
        <div class="cart-item">
            <img src="book_images/<?php echo $row['image']; ?>" alt="Book Image">
            <div class="cart-item-details">
                <h3><?php echo $row['title']; ?></h3>
                <p>by <?php echo $row['author']; ?></p>
            </div>
            <div class="cart-item-price">₹<?php echo number_format($row['price'], 2); ?></div>
            <div class="cart-item-qty">
                <div class="qty-box">
                    <form action="update_quantity.php" method="POST">
                        <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                        <button name="decrease">-</button>
                        <span><?php echo $row['quantity']; ?></span>
                        <button name="increase">+</button>
                    </form>
                </div>
            </div>
            <div class="cart-item-subtotal">₹<?php echo number_format($item_total, 2); ?></div>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="cart-totals">
        <h3>Cart totals</h3>
        <div class="totals-row">
            <span>Subtotal</span>
            <span>₹<?php echo number_format($subtotal, 2); ?></span>
        </div>
        <div class="totals-row">
            <span>Shipping</span>
            <span>Free</span>
        </div>
        <hr>
        <div class="totals-row" style="font-weight: bold;">
            <span>Total</span>
            <span>₹<?php echo number_format($subtotal, 2); ?></span>
        </div>
        <form action="checkout.php">
            <button class="checkout-btn">Proceed to checkout</button>
        </form>
    </div>
</div>

<footer>
<?php include 'footer.php'; ?>
</footer>
</body>
</html>
