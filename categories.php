<?php
// Include the database connection file
include('db.php');

// Fetch categories from the database
$query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Listing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .category-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }
        .category-item {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 15px;
            width: 200px;
            text-align: center;
            overflow: hidden;
        }
        .category-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .category-item h3 {
            padding: 10px;
            margin: 0;
        }
        .category-item a {
            display: inline-block;
            background-color:  #004080;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .category-item a:hover {
            background-color: #e5533b;
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
</head>
<body>
<!-- Navigation Bar -->
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


    <h1 style="text-align: center; margin-top: 20px;">Book Categories</h1>

    <div class="category-container">
        <?php
        // Loop through categories and display them
        while ($row = mysqli_fetch_assoc($category_result)) {
            ?>
            <div class="category-item">
                <img src="category_images/<?php echo $row['image']; ?>" alt="<?php echo $row['category_name']; ?>">
                <h3><?php echo $row['category_name']; ?></h3>
                
                <!-- Link to books.php with category_id as a query parameter -->
                <a href="books.php?category_id=<?php echo $row['id']; ?>">View Books</a>
            </div>
            <?php
        }
        ?>
    </div>


<footer>
<?php

include('footer.php');?>
</footer>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>