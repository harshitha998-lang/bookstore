<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add to Cart request and reduce stock
if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $id = intval($_GET['book_id']);
    $quantity = intval($_GET['quantity']);

    // Ensure product exists and has enough stock
    $checkStock = $conn->prepare("SELECT stock FROM  books WHERE book_id = ?");
    $checkStock->bind_param("i", $id);
    $checkStock->execute();
    $result = $checkStock->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['stock'] >= $quantity) {
            // Reduce stock
            $updateStock = $conn->prepare("UPDATE books SET stock = stock - ? WHERE book_id = ?");
            $updateStock->bind_param("ii", $quantity, $id);
            $updateStock->execute();
        }
    }
    $checkStock->close();
    header("Location: cart.php");
    exit();
}

// Get id from URL
$id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($product['name']); ?></title>
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
        /* CSS remains unchanged */
 /* [Your full original CSS block remains here] */ ?>
    </style>
</head>
<body>

<a href="SS.php" class="back-btn">&larr; Back to Products</a>

<div class="product-container">
    <div class="product-image">
        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
    <div class="product-details">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>

        <div class="stars">
            ★★★★★ <span>(4.8/5)</span>
        </div>

        <div class="price">₹<?php echo number_format($product['price'], 2); ?></div>

        <div class="delivery">Delivery by <?php echo date('l, M j', strtotime('+' . rand(2,5) . ' days')); ?></div>

        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

        <div class="availability">
            <?php echo ($product['stock'] > 0) ? 'In Stock (' . $product['stock'] . ' left)' : 'Out of Stock'; ?>
        </div>

        <?php if ($product['stock'] > 0): ?>
        <div class="quantity-control">
            <button onclick="changeQty(-1)">-</button>
            <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
            <button onclick="changeQty(1)">+</button>
        </div>

        <div class="total">
            Total: ₹<span id="total"><?php echo $product['price']; ?></span>
        </div>

        <div class="actions">
            <button class="add-cart" onclick="addToCart()">Add to cart</button>
            <button class="buy-now" onclick="buyNow()">Buy Now</button>
        </div>
        <?php endif; ?>

        <div class="ingredients">
            <h3>Ingredients</h3>
            <p><?php echo nl2br(htmlspecialchars($product['ingredients'])); ?></p>
        </div>
    </div>
</div>

<div class="toast" id="toast">Added to Cart!</div>

</body>
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
</html>

<?php
    } else {
        echo "<p>Product not found.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Invalid product ID.</p>";
}

$conn->close();
?>