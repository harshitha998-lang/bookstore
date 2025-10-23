<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginindex.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_wishlist'])) {
    $wishlist_id = $_POST['wishlist_id'];
    $query = "DELETE FROM wishlist WHERE id = $wishlist_id AND user_id = $user_id";
    mysqli_query($conn, $query);
}

$query = "SELECT w.id AS wishlist_id, b.title, b.author, b.price, b.image 
          FROM wishlist w 
          JOIN books b ON w.book_id = b.book_id 
          WHERE w.user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Wishlist</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 0; }
        .container { width: 90%; margin: 40px auto; }
        h2 { margin-bottom: 20px; }
        .wishlist-item {
            display: flex;
            align-items: center;
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .wishlist-item img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 5px;
        }
        .wishlist-info { flex-grow: 1; }
        .wishlist-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-remove { background: #e74c3c; color: white; }
        .btn-buy { background: #27ae60; color: white; }
        .btn:hover { opacity: 0.9; }
        .continue-btn {
            margin-top: 30px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .continue-btn:hover { background-color: #2980b9; }

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
            .wishlist-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .wishlist-item img {
                width: 100%;
                max-width: 150px;
                height: auto;
                margin-bottom: 15px;
            }

            .wishlist-actions {
                width: 100%;
                justify-content: flex-start;
                margin-top: 10px;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
                padding-top: 10px;
            }

            .logo {
                margin-bottom: 10px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 20px;
            }

            .btn {
                font-size: 14px;
                padding: 6px 12px;
            }

            .wishlist-info h3 {
                font-size: 16px;
            }

            .wishlist-info p {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
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

<div class="container">
    <h2>Your Wishlist</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="wishlist-item">
                <img src="book_images/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                <div class="wishlist-info">
                    <h3><?php echo $row['title']; ?></h3>
                    <p>by <?php echo $row['author']; ?></p>
                    <p>Price: ₹<?php echo $row['price']; ?></p>
                </div>
                <div class="wishlist-actions">
                    <form method="post" action="">
                        <input type="hidden" name="wishlist_id" value="<?php echo $row['wishlist_id']; ?>">
                        <button type="submit" name="remove_wishlist" class="btn btn-remove">Remove</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Your wishlist is empty.</p>
    <?php endif; ?>
</div>

<footer>
<?php include('footer.php'); ?>
</footer>
</body>
</html>
