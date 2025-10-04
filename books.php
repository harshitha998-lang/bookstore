<?php
session_start();
include 'db.php';

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$filter_category = isset($_GET['filter_category']) ? $_GET['filter_category'] : '';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';

// Wishlist data
$wishlist = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $wishQuery = mysqli_query($conn, "SELECT book_id FROM wishlist WHERE user_id = '$user_id'");
    while ($row = mysqli_fetch_assoc($wishQuery)) {
        $wishlist[] = $row['book_id'];
    }
}

// Fetch filter categories
$categoryResult = mysqli_query($conn, "SELECT * FROM categories");

// Build SQL
$sql = "SELECT * FROM books WHERE 1";
if ($category_id) {
    $sql .= " AND category_id = $category_id";
}
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (
        title LIKE '%$search%' OR 
        author LIKE '%$search%' OR 
        category_id IN (SELECT id FROM categories WHERE category_name LIKE '%$search%')
    )";
}
if (!empty($filter_category)) {
    $sql .= " AND category_id = '$filter_category'";
}
if ($price_min !== '' && $price_max !== '') {
    $sql .= " AND price BETWEEN $price_min AND $price_max";
}
if ($sort == 'low_high') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort == 'high_low') {
    $sql .= " ORDER BY price DESC";
}

$books = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        header {
            color: #004080;
            padding: 20px;
            text-align: center;
        }
        h1 {
            margin: 0;
        }
        .filters {
            text-align: center;
            padding: 20px;
        }
        .filters form input, .filters form select {
            padding: 8px 10px;
            margin: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 15px;
        }
        .filters form button {
            padding: 10px 20px;
            background: #004080;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .book-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            padding: 20px;
        }
        .book-card {
            position: relative;
            width: 220px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: 0.3s;
        }
        .book-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .book-card .info {
            padding: 15px;
        }
        .book-card h3 {
            margin: 0 0 5px;
            font-size: 18px;
        }
        .book-card p {
            margin: 5px 0;
            color: #555;
        }
        .wishlist-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #aaa;
            background: #fff;
            padding: 6px;
            border-radius: 50%;
            transition: 0.3s ease;
            font-size: 18px;
            z-index: 2;
        }
        .wishlist-icon:hover {
            color: #ffcc29;
            transform: scale(1.1);
        }
        .wishlist-icon.active {
            color: red;
        }
        .add-cart-btn {
            display: block;
            width: 80%;
            margin: 10px auto 15px;
            padding: 8px;
            background-color: #004080;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
        }
        .add-cart-btn:hover {
            background-color: #002b5e;
        }
        @media (max-width: 768px) {
            .book-card {
                width: 90%;
            }
        }
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
</head>
<body>

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

<header>
    <h1>Books</h1>
</header>

<!-- Search bar -->
<form method="GET" action="books.php" style="text-align:center; margin: 30px 0;">
    <input type="hidden" name="id" value="<?= $category_id ?>">
    <input type="text" name="search" placeholder="Search by title, author or category"
           value="<?= htmlspecialchars($search) ?>"
           style="padding: 10px 15px; width: 60%; max-width: 500px; border: 1px solid #ccc; border-radius: 25px; font-size: 16px;">
    <button type="submit" style="padding: 10px 20px; background-color: #004080; color: white; border: none; border-radius: 25px; margin-left: 10px; font-size: 16px; cursor: pointer;">
        Search
    </button>
</form>

<!-- Filters -->
<div class="filters">
    <form method="GET" action="books.php">
        <input type="hidden" name="id" value="<?= $category_id ?>">
        <select name="filter_category">
            <option value="">Filter by Category</option>
            <?php while ($cat = mysqli_fetch_assoc($categoryResult)) : ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $filter_category ? 'selected' : '' ?>>
                    <?= $cat['category_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <input type="number" name="price_min" placeholder="Min Price" value="<?= $price_min ?>">
        <input type="number" name="price_max" placeholder="Max Price" value="<?= $price_max ?>">
        <select name="sort">
            <option value="">Sort by</option>
            <option value="low_high" <?= $sort == 'low_high' ? 'selected' : '' ?>>Price: Low to High</option>
            <option value="high_low" <?= $sort == 'high_low' ? 'selected' : '' ?>>Price: High to Low</option>
        </select>
        <button type="submit">Apply</button>
    </form>
</div>

<!-- Book Listing -->
<div class="book-grid">
    <?php while ($book = mysqli_fetch_assoc($books)) : ?>
        <div class="book-card">
            <a href="book_details.php?id=<?= $book['book_id'] ?>">
                <img src="book_images/<?= $book['image'] ?>" alt="<?= $book['title'] ?>">
            </a>
            <div class="info">
                <h3><?= $book['title'] ?></h3>
                <p>By <?= $book['author'] ?></p>
                <p><strong>₹<?= $book['price'] ?></strong></p>
            </div>

            <!-- Wishlist Icon -->
            <?php if (in_array($book['book_id'], $wishlist)) : ?>
                <a href="add_wishlist.php?book_id=<?= $book['book_id'] ?>" class="wishlist-icon active" title="Added to Wishlist">
                    <i class="fas fa-heart"></i>
                </a>
            <?php else : ?>
                <a href="add_wishlist.php?book_id=<?= $book['book_id'] ?>" class="wishlist-icon" title="Add to Wishlist">
                    <i class="fas fa-heart"></i>
                </a>
            <?php endif; ?>

            
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>