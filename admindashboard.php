<?php
include 'db.php';

$tab = $_GET['tab'] ?? 'users';
$edit_id = $_GET['edit_id'] ?? null;
$view_user_id = $_GET['id'] ?? null;
$edit_data = null;

// HANDLE ADD / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['form_type'] === 'add_user') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $type = $_POST['user_type'];
        mysqli_query($conn, "INSERT INTO users (name, email, user_type) VALUES ('$name', '$email', '$type')");
    }

    if ($_POST['form_type'] === 'add_category') {
        $cat_name = $_POST['category_name'];
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "category_images/$image");
        mysqli_query($conn, "INSERT INTO categories (category_name, image) VALUES ('$cat_name', '$image')");
    }

    if ($_POST['form_type'] === 'add_book') {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "book_images/$image");
        mysqli_query($conn, "INSERT INTO books (title, author, price, category_id, image) 
                             VALUES ('$title', '$author', '$price', '$category', '$image')");
    }

    if ($_POST['form_type'] === 'edit_book') {
        $id = $_POST['book_id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $stock = $_POST['stock'];
        $image = $_FILES['image']['name'];
        if ($image !== '') {
            move_uploaded_file($_FILES['image']['tmp_name'], "book_images/$image");
            mysqli_query($conn, "UPDATE books SET title='$title', author='$author', price='$price', category_id='$category', image='$image', stock='$stock' WHERE book_id=$id");
        } else {
            mysqli_query($conn, "UPDATE books SET title='$title', author='$author', price='$price', category_id='$category', stock='$stock' WHERE book_id=$id");
        }
    }

    header("Location: admindashboard.php");
    exit();
}

// DELETE
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $table = $_GET['delete'];
    $id = $_GET['id'];
    $key = $table === 'books' ? 'book_id' : 'id';
    mysqli_query($conn, "DELETE FROM $table WHERE $key=$id");
    header("Location: admindashboard.php?tab=$table");
    exit();
}

// EDIT FETCH
if ($tab === 'books' && $edit_id) {
    $res = mysqli_query($conn, "SELECT * FROM books WHERE book_id=$edit_id");
    $edit_data = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        /* ====== GENERAL ====== */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            padding: 20px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        /* ====== TABS ====== */
        .tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .tab-btn {
            padding: 10px 25px;
            border-radius: 25px;
            background: #dcdde1;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 600;
            transition: 0.3s;
        }
        .tab-btn:hover, .tab-btn.active {
            background: #3498db;
            color: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        /* ====== FORMS ====== */
        form {
            background: white;
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            transition: border-color 0.2s;
        }
        input:focus, select:focus {
            border-color: #3498db;
            outline: none;
        }
        button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        button:hover {
            background: #2d83c3;
        }

        /* ====== TABLES ====== */
        table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #eee;
            padding: 12px 10px;
            text-align: center;
        }
        th {
            background: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background: #f8f9fb;
        }
        tr:hover {
            background: #eaf3fc;
        }
        img {
            max-height: 60px;
            border-radius: 6px;
        }

        /* ====== LINKS ====== */
        .action-btns a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            margin: 0 5px;
        }
        .action-btns a:hover {
            text-decoration: underline;
        }
        .back-link {
            margin: 10px 0;
            display: inline-block;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }

        /* ====== CARDS ====== */
        .user-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 900px) {
            .tabs {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }

        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            form {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 22px;
            }
            button {
                width: 100%;
            }
            .tab-btn {
                width: 90%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
<h2>Admin Dashboard</h2>
<div class="tabs">
    <a href="?tab=users" class="tab-btn <?= $tab === 'users' ? 'active' : '' ?>">Users</a>
    <a href="?tab=categories" class="tab-btn <?= $tab === 'categories' ? 'active' : '' ?>">Categories</a>
    <a href="?tab=books" class="tab-btn <?= $tab === 'books' ? 'active' : '' ?>">Books</a>
</div>

<?php if ($tab === 'users'): ?>
<form method="POST">
    <h3>Add User</h3>
    <input type="hidden" name="form_type" value="add_user">
    <label>Name:</label><input type="text" name="name" required>
    <label>Email:</label><input type="email" name="email" required>
    <label>Role:</label><select name="user_type"><option>user</option><option>admin</option></select>
    <button type="submit">Add</button>
</form>

<?php
$users = mysqli_query($conn, "SELECT * FROM users");
echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Type</th><th>Actions</th></tr>";
while ($row = mysqli_fetch_assoc($users)) {
    echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['email']}</td><td>{$row['user_type']}</td>
    <td class='action-btns'><a href='?tab=user_details&id={$row['id']}'>View</a> | <a href='?tab=users&delete=users&id={$row['id']}'>Delete</a></td></tr>";
}
echo "</table>";
endif;
?>

<?php if ($tab === 'user_details' && $view_user_id): ?>
<a href="?tab=users" class="back-link">← Back to Users</a>

<?php
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $view_user_id"));
?>
<div class="user-details">
    <h3>User Details (ID: <?= $view_user_id ?>)</h3>
    <p><strong>Name:</strong> <?= $user['name'] ?><br>
    <strong>Email:</strong> <?= $user['email'] ?></p>
</div>

<h4>Order History</h4>
<?php
$orders = mysqli_query($conn, "
    SELECT 
        o.id AS order_id,
        o.order_date,
        o.total_amount,
        o.payment_method,
        oi.book_title,
        oi.quantity,
        oi.price
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = $view_user_id
    ORDER BY o.order_date DESC
");

if (mysqli_num_rows($orders) > 0) {
    echo "<table><tr><th>Order ID</th><th>Date</th><th>Book Title</th><th>Quantity</th><th>Price</th><th>Total</th><th>Payment</th></tr>";
    while ($order = mysqli_fetch_assoc($orders)) {
        echo "<tr>
            <td>{$order['order_id']}</td>
            <td>{$order['order_date']}</td>
            <td>{$order['book_title']}</td>
            <td>{$order['quantity']}</td>
            <td>{$order['price']}</td>
            <td>{$order['total_amount']}</td>
            <td>{$order['payment_method']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No orders found for this user.</p>";
}
?>

<?php endif; ?>

<?php if ($tab === 'categories'): ?>
<form method="POST" enctype="multipart/form-data">
    <h3>Add Category</h3>
    <input type="hidden" name="form_type" value="add_category">
    <label>Category Name:</label><input type="text" name="category_name" required>
    <label>Image:</label><input type="file" name="image" required>
    <button type="submit">Add</button>
</form>

<?php
$categories = mysqli_query($conn, "SELECT * FROM categories");
echo "<table><tr><th>ID</th><th>Name</th><th>Image</th><th>Action</th></tr>";
while ($row = mysqli_fetch_assoc($categories)) {
    echo "<tr><td>{$row['id']}</td><td>{$row['category_name']}</td><td><img src='category_images/{$row['image']}'></td>
    <td><a href='?tab=categories&delete=categories&id={$row['id']}'>Delete</a></td></tr>";
}
echo "</table>";
endif;
?>

<?php if ($tab === 'books'): ?>
<form method="POST" enctype="multipart/form-data">
    <h3><?= $edit_id ? "Edit Book" : "Add Book" ?></h3>
    <input type="hidden" name="form_type" value="<?= $edit_id ? 'edit_book' : 'add_book' ?>">
    <?php if ($edit_id): ?><input type="hidden" name="book_id" value="<?= $edit_id ?>"><?php endif; ?>
    <label>Title:</label><input type="text" name="title" value="<?= $edit_data['title'] ?? '' ?>">
    <label>Author:</label><input type="text" name="author" value="<?= $edit_data['author'] ?? '' ?>" >
    <label>Price:</label><input type="number" name="price" step="0.01" value="<?= $edit_data['price'] ?? '' ?>">
    <label>Stock:</label><input type="number" name="stock" step="0.01" value="<?= $edit_data['stock'] ?? '' ?>" >
    <label>Category:</label>
    <select name="category" required>
        <?php
        $cat_list = mysqli_query($conn, "SELECT * FROM categories");
        while ($cat = mysqli_fetch_assoc($cat_list)) {
            $sel = ($edit_data && $edit_data['category_id'] == $cat['id']) ? 'selected' : '';
            echo "<option value='{$cat['id']}' $sel>{$cat['category_name']}</option>";
        }
        ?>
    </select>
    <label>Image:</label><input type="file" name="image" <?= $edit_id ? '' : 'required' ?>>
    <button type="submit"><?= $edit_id ? "Update" : "Add" ?></button>
</form>

<?php
$books = mysqli_query($conn, "SELECT b.*, c.category_name FROM books b JOIN categories c ON b.category_id = c.id");
echo "<table><tr><th>ID</th><th>Title</th><th>Author</th><th>Price</th><th>Category</th><th>Image</th><th>Stock</th><th>Actions</th></tr>";
while ($row = mysqli_fetch_assoc($books)) {
    echo "<tr>
    <td>{$row['book_id']}</td>
    <td>{$row['title']}</td>
    <td>{$row['author']}</td>
    <td>{$row['price']}</td>
    <td>{$row['category_name']}</td>
    <td><img src='book_images/{$row['image']}'></td>
    <td>{$row['stock']}</td>
    <td class='action-btns'>
        <a href='?tab=books&edit_id={$row['book_id']}'>Edit</a>
        <a href='?tab=books&delete=books&id={$row['book_id']}'>Delete</a>
    </td>
    </tr>";
}
echo "</table>";
endif;
?>
</body>
</html>
