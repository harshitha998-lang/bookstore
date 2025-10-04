<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNest| Home</title>
    <link rel="stylesheet" href="indexstyles.css">
    <style>
        /* Additional styles for slideshow */
        .hero {
            position: relative;
            height: 400px;
            overflow: hidden;
        }

        .hero-slide {
            width: 100%;
            height: 100%;
            position: absolute;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .hero-slide.active {
            opacity: 1;
        }

        .hero-content {
            position: absolute;
            z-index: 2;
            width: 100%;
            text-align: center;
            top: 50%;
            transform: translateY(-50%);
            color: white;
        }

        .hero-content h1 {
            font-size: 36px;
        }

        .dots {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            z-index: 3;
        }

        .dot {
            height: 12px;
            width: 12px;
            margin: 0 5px;
            background-color: #ccc;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .dot.active {
            background-color: #ff9800;
        }

        .category {
            text-align: center;
            text-decoration: none;
            color: inherit;
        }

        .category img {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .category:hover img {
            transform: scale(1.05);
        }

        .section-title {
            text-align: center;
            margin-top: 40px;
        }

        /* Cart Button Styling */
        .cart-button {
            background-color: #00587a;
            padding: 10px 20px;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cart-button:hover {
            background-color: #e68900;
        }

        /* Discount Section */
        .discount-section {
            background-color: #f7f7f7;
            padding: 40px 0;
            text-align: center;
            margin-top: 40px;
        }

        .discount-section h2 {
            font-size: 30px;
            color: #007bff;
        }

        .discount-section p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .discount-button {
            background-color: #ff9800;
            padding: 15px 30px;
            text-decoration: none;
            color: white;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .discount-button:hover {
            background-color: #e68900;
        }
.discount-image {
    max-width: 30%;
    height: 0%;
    margin: 20px 0;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
  /* Testimonials Section */
    .testimonial-section {
  background-color: #f0f8ff;
  padding: 60px 20px;
  text-align: center;
  margin-top: 60px; /* Adjust the top margin to avoid overlapping with other sections */
}

.testimonial-section h2 {
  font-size: 28px;
  color: #333;
  margin-bottom: 40px;
}

.testimonial-container {
  overflow: hidden;
  width: 100%;
  max-width: 800px;
  margin: auto;
  position: relative;
}

.testimonial-slider {
  display: flex;
  width: 300%; /* 100% * number of testimonials */
  transition: transform 1s ease-in-out;
}

.testimonial {
  flex: 0 0 100%;
  padding: 30px;
  font-size: 20px;
  color: #555;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  margin: 0 10px; /* Space between testimonials */
}
.testimonials {
      
      max-width: 900px;
      margin: auto;
      text-align: center;
      padding: 20px;
    }
    .testimonial {
      display: none;
      font-style: italic;
      
    }
    .stars {
      color: gold;
      margin-top: 10px;
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

<!-- Hero Section -->
<section class="hero">
    <div class="hero-slide active" style="background-image: url('images/hero1.jpeg');"></div>
    <div class="hero-slide" style="background-image: url('images/hero3.jpeg');"></div>
    <div class="hero-slide" style="background-image: url('images/hero4.jpeg');"></div>
    <div class="hero-content">
        <h1>Welcome to Our Online Bookstore</h1>
        <p>Discover your next great read today!</p>
        <a href="categories.php" class="btn">Shop Now</a>
    </div>
    <div class="dots">
        <span class="dot active"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>
</section>



<!-- Category Section -->
<section class="categories">
    <h2 class="section-title">Shop by Category</h2>
    <div class="category-container">
        <?php
        $query = "SELECT * FROM categories LIMIT 6";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<a href='books.php?category_id={$row['id']}' class='category'>
                    <img src='category_images/{$row['image']}' alt='{$row['category_name']}'>
                    <p>{$row['category_name']}</p>
                  </a>";
        }
        ?>
    </div>
    <a href="categories.php" class="btn">View All Categories</a>
</section>

<!-- Best Selling Books -->
<section class="best-selling">
    <h2 class="section-title">Best Selling Books</h2>
    <div class="book-container">
        <?php
        $query = "SELECT * FROM books WHERE is_best_seller=1 LIMIT 6";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<div class='book'>
                    <img src='book_images/{$row['image']}' alt='{$row['title']}'>
                    <h3>{$row['title']}</h3>
                    <p>Author: {$row['author']}</p>
                    <p>₹{$row['price']}</p>
                    <form action='add_to_cart.php' method='POST'>
                        <input type='hidden' name='book_id' value='{$row['book_id']}'>
                        <button type='submit' class='cart-button'>Add to Cart</button>
                    </form>
                  </div>";
        }
        ?>
    </div>
</section>

<!-- New Arrival Books -->
<section class="new-arrivals">
    <h2 class="section-title">New Arrivals</h2>
    <div class="book-container">
        <?php
        $query = "SELECT * FROM books WHERE is_new_arrival=1 LIMIT 6";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<div class='book'>
                    <img src='book_images/{$row['image']}' alt='{$row['title']}'>
                    <h3>{$row['title']}</h3>
                    <p>Author: {$row['author']}</p>
                    <p>₹{$row['price']}</p>
                    <form action='add_to_cart.php' method='POST'>
                        <input type='hidden' name='book_id' value='{$row['book_id']}'>
                        <button type='submit' class='cart-button'>Add to Cart</button>
                    </form>
                  </div>";
        }
        ?>
    </div>
</section>

<!-- Discount Section (at the bottom of the page) -->
<section class="discount-section">
    <h2>Special Discount on Bestsellers!</h2>
    <p>Get 10% off on all best-selling books. Don't miss out!</p>
    <img src="images/discount.jpeg" alt="Discount Banner" class="discount-image">
    <center><a href="categories.php" class="discount-button">Shop Now</a></center>
</section>

</div>  <div class="testimonials">
    <h2>Testimonials</h2>
    <h4>
    <div class="testimonial" style="display:block">"BookNest is my favoritr place to find and buy books effortlrssly" –Diya<div class="stars">★★★★★</div></div>
    <div class="testimonial">"Amazing selections" – Raksha R<div class="stars">★★★★★</div></div>
    <div class="testimonial">" A true paradise for book lovers" – Sandy<div class="stars">★★★★★</div></div>
    <div class="testimonial">"Easy shopping,great books,fast service " –Dhanyatha <div class="stars">★★★★★</div></div>
    <div class="testimonial">"BookNest makes discovering new reads simple and enjoyable." – Aishwarya R<div class="stars">★★★★★</h4></div></div><br><br><br>

<!-- Slideshow Script -->
<script>
    let slides = document.querySelectorAll(".hero-slide");
    let dots = document.querySelectorAll(".dot");
    let current = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove("active");
            dots[i].classList.remove("active");
        });
        slides[index].classList.add("active");
        dots[index].classList.add("active");
    }

    setInterval(() => {
        current = (current + 1) % slides.length;
        showSlide(current);
    }, 3000);
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const slider = document.querySelector(".testimonial-slider");
    const testimonials = document.querySelectorAll(".testimonial");
    let index = 0;

    setInterval(function() {
      // Set the transition for the next slide
      slider.style.transition = "transform 1s ease-in-out";
      // Move the slider to the next testimonial
      index = (index + 1) % testimonials.length;
      slider.style.transform = "translateX(-" + (index * 100) + "%)";
    }, 6000); // Change every 6 seconds
  });
</script>
<footer>
<?php include 'footer.php'; ?>
</footer>
</script>
  <script src="glowscript1.js"></script>
</body>
</html>