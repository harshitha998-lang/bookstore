<?php
include 'db.php'; // Database connection file

// Check if book ID is passed
if (isset($_GET['id'])) {
    $book_id = intval($_GET['id']);
    
    // Fetch book details
    $query = "SELECT books.*, categories.category_name FROM books 
              JOIN categories ON books.category_id = categories.id 
              WHERE book_id = ?";
       $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        echo "<h2>Book not found!</h2>";
        exit;
    }
} else {
    echo "<h2>Invalid book selection!</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Book Details</title>
    <link rel="stylesheet" href="bookdetailstyles.css"> <!-- Your CSS file -->
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
         body {
            font-family: 'Poppins', sans-serif;
            margin: 30px;
            background: #f8f8f8;
            font-size: 18px;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }
        .product-container {
            display: flex;
            gap: 40px;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .product-image img {
            width: 350px;
            border-radius: 12px;
            transition: 0.3s ease;
        }
        .product-image img:hover {
            transform: scale(1.05);
        }
        .product-details {
            flex: 1;
        }
        .product-details h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .stars {
            color: #ffc107;
            margin-bottom: 10px;
            font-size: 22px;
        }
        .stars span {
            color: #555;
            font-size: 16px;
        }
        .price {
            color: #e53935;
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .delivery {
            margin: 10px 0;
            color: #555;
            font-size: 16px;
        }
        .availability {
            margin: 10px 0;
            font-weight: bold;
            font-size: 18px;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }
        .quantity-control button {
            padding: 10px 16px;
            font-size: 22px;
            border: none;
            background: #333;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        .quantity-control input {
            width: 70px;
            text-align: center;
            font-size: 22px;
            margin: 0 10px;
        }
        .total {
            margin: 12px 0;
            font-size: 28px;
            font-weight: bold;
        }
        .actions button {
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            margin-right: 10px;
        }
        .add-cart {
            background: #333;
        }
        .buy-now {
            background: #e53935;
        }
        .ingredients {
            margin-top: 30px;
        }
        .ingredients h3 {
            margin-bottom: 10px;
        }
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #333;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            opacity: 0;
            transition: 0.4s ease;
        }
        .toast.show {
            opacity: 1;
        }
.realted{
text-decoratin:none;
}

        /* CSS remains unchanged */
 /* [Your full original CSS block remains here] */ ?>
    </style>
</head>
<body>

<!-- Navigation Bar -->
<header>
    <!-- Navigation Bar -->
<nav class="navbar">
  <div class="logo">BookNest</div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="categories.php">Categories</a></li>
     <li><a href="aboutus.html">About Us</a></li>
    <li><a href="cart.php">🛒</a></li>
    <li><a href="wishlist.php">❤️</a></li>
    <li><a href="loginindex.php">👤</a></li>
  </ul>
</nav>

</header>

<!-- Book Details Section -->
<div class="product-container">
    <div class="product-image">
        <img src="book_images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
    </div>
    <div class="product-deatails">
        <h1><?php echo htmlspecialchars($book['title']); ?></h1>
        <h3>By <?php echo htmlspecialchars($book['author']); ?></h3>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($book['category_name']); ?></p>
        <p><strong>Price:</strong> ₹<?php echo number_format($book['price'], 2); ?></p>
<div class="stars">
            ★★★★★ <span>(4.8/5)</span>
        </div>
<div class="price">₹<?php echo number_format($book['price'], 2); ?></div>
<div class="delivery">Delivery by <?php echo date('l, M j', strtotime('+' . rand(2,5) . ' days')); ?></div>

<p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>

<div class="availability">
            <?php echo ($book['stock'] > 0) ? 'In Stock (' . $book['stock'] . ' left)' : 'Out of Stock'; ?>
        </div>



<?php if($book['stock']>0):?>
<div class="actions">
            <!-- Buttons -->
<?php if($book['stock']>0)?>
        <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
        </form>
      <form action="checkout.php" method="POST">

        <button type="submit" class="btn buy-now">Buy Now</button>

</form>
        </div>
<?php endif;?>
        
  </div>
</div>
<div class="toast" id="toast">Added to Cart!</div>

        
        
      
        
        
    </div>
</div>

<!-- Related Books Section -->
<div class="related-books">
    <h2>Related Products</h2>
<div class="related">
    <div class="book-list">
        <?php
        $category_id = $book['category_id'];
        $related_query = "SELECT * FROM books WHERE category_id = ? AND book_id != ? LIMIT 4";
        
        $stmt = $conn->prepare($related_query);
        $stmt->bind_param("ii", $category_id, $book_id);
        $stmt->execute();
        $related_result = $stmt->get_result();

        while ($related = $related_result->fetch_assoc()) {
            echo '<div class="book-card">';
            echo '<a href="book_details.php?id=' . $related['book_id'] . '">';
            echo '<img src="book_images/' . htmlspecialchars($related['image']) . '" alt="' . htmlspecialchars($related['title']) . '">';
            echo '<h3>' . htmlspecialchars($related['title']) . '</h3>';
            echo '<p>₹' . number_format($related['price'], 2) . '</p>';
            echo '</a>';
            echo '</div>';
        }
        ?>
    </div>
</div>
</div>
<?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">×</span>
        <img src="book_images/<?php echo htmlspecialchars($book['image']); ?>" alt="Book" width="100">
        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
        <p>₹<?php echo number_format($book['price'], 2); ?> added to your cart.</p>
        <div class="popup-buttons">
            <a href="books.php" class="btn">Continue Shopping</a>
            <a href="cart.php" class="btn">View Cart</a>
        </div>
    </div>
</div>

<style>
.popup {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.popup-content {
    position: relative;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    animation: fadeIn 0.5s ease;
}
.popup-buttons .btn {
    margin: 10px;
    padding: 10px 20px;
    background: #0077cc;
    color: #fff;
    border: none;
    border-radius: 6px;
    text-decoration: none;
}
.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    color: #555;
    cursor: pointer;
}
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1); }
}
 

</style>

<script>
    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }

    setTimeout(closePopup, 10000); // Auto close after 5 seconds

</script>

<?php endif; ?>
<script>
    var price = <?php echo $product['price']; ?>;
    var stock = <?php echo $product['stock']; ?>;

    function addToCart() {
        var qty = document.getElementById('quantity').value;
        var id = <?php echo $product['id']; ?>;
        window.location.href = "add_to_cart.php?id=" + id + "&quantity=" + qty;
    }

    function changeQty(amount) {
        var qty = document.getElementById('quantity');
        var current = parseInt(qty.value);
        var newQty = current + amount;
        if (newQty >= 1 && newQty <= stock) {
            qty.value = newQty;
            updateTotal();
        } else if (newQty > stock) {
            alert("Only " + stock + " in stock.");
        }
    }

    function updateTotal() {
        var qty = document.getElementById('quantity').value;
        document.getElementById('total').innerText = (price * qty).toFixed(2);
    }

    function buyNow() {
        let qty = document.getElementById('quantity').value;
        if (qty > stock) {
            alert("Quantity exceeds stock.");
            return;
        }
        alert("Proceeding to checkout!");
    }

    function showToast(msg) {
        var toast = document.getElementById('toast');
        toast.innerText = msg;
        toast.classList.add('show');
        setTimeout(function(){
            toast.classList.remove('show');
        }, 2000);
    }
</script>
<!-- Footer -->



</body>
<footer>
<?php
include 'footer.php';?>
</html>