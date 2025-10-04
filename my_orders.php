<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all orders for this user
$orders_result = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .orders-container {
            width: 85%;
            margin: 40px auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        .order-card {
            background: #fff;
            margin-bottom: 25px;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
        .order-info {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .order-info p {
            margin: 5px 0;
            color: #444;
        }
        .book-list {
            margin-top: 15px;
        }
        .book-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .book-item img {
            height: 60px;
            margin-right: 15px;
            border-radius: 6px;
        }
        .book-details {
            flex-grow: 1;
        }
        .book-details h4 {
            margin: 0;
            font-size: 17px;
            color: #333;
        }
        .book-details p {
            margin: 3px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="orders-container">
        <h2>My Orders</h2>

        <?php if ($orders_result->num_rows > 0): ?>
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <div class="order-card">
                    <div class="order-info">
                        <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
                        <p><strong>Date:</strong> <?php echo date("d M Y, h:i A", strtotime($order['order_date'])); ?></p>
                        <p><strong>Total Amount:</strong> ₹<?php echo $order['total_amount']; ?></p>
                        <p><strong>Payment:</strong> <?php echo $order['payment_method']; ?></p>
                        <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
                    </div>

                    <!-- Fetch order items -->
                    <?php
                    $order_id = $order['id'];
                    $items_result = $conn->query("
                        SELECT oi.*, b.title, b.author, b.image 
                        FROM order_items oi 
                        JOIN books b ON oi.id = b.book_id 
                        WHERE oi.order_id = $order_id
                    ");
                    ?>

                    <div class="book-list">
                        <h4>Books:</h4>
                        <?php while ($item = $items_result->fetch_assoc()): ?>
                            <div class="book-item">
                                <img src="book_images/<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                                <div class="book-details">
                                    <h4><?php echo $item['title']; ?></h4>
                                    <p>Author: <?php echo $item['author']; ?></p>
                                    <p>Qty: <?php echo $item['quantity']; ?> | Price: ₹<?php echo $item['price']; ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center;">You haven't placed any orders yet.</p>
        <?php endif; ?>
    </div>	
</body>
</html>