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
            mysqli_query($conn, "UPDATE books SET title='$title', author='$author', price='$price', category_id='$category', image='$image' ,stock='$stock' WHERE book_id=$id");
        } else {
            mysqli_query($conn, "UPDATE books SET title='$title', author='$author', price='$price', category_id='$category' ,stock='$stock' WHERE book_id=$id");
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
?><!DOCTYPE html><html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 20px; }
        .tabs { display: flex; gap: 10px; justify-content: center; margin-bottom: 20px; }
        .tab-btn { padding: 10px 20px; background: #ccc; text-decoration: none; color: black; }
        .tab-btn.active { background: #3498db; color: white; }
        table { width: 100%; margin: 20px 0; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        img { max-height: 50px; }
        form { background: white; padding: 20px; margin: 20px auto; width: 90%; border: 1px solid #ccc; }
        label { display: block; margin: 10px 0 5px; }
        input, select { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #3498db; color: white; border: none; cursor: pointer; }
        .action-btns a { margin: 0 5px; }
        .back-link { margin: 10px 0; display: inline-block; }
    </style>
</head>
<body><h2>Admin Dashboard</h2><div class="tabs">
    <a href="?tab=users" class="tab-btn <?= $tab === 'users' ? 'active' : '' ?>">Users</a>
    <a href="?tab=categories" class="tab-btn <?= $tab === 'categories' ? 'active' : '' ?>">Categories</a>
    <a href="?tab=books" class="tab-btn <?= $tab === 'books' ? 'active' : '' ?>">Books</a>
</div><?php if ($tab === 'users'): ?><form method="POST">
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
    <td><a href='?tab=user_details&id={$row['id']}'>View</a> | <a href='?tab=users&delete=users&id={$row['id']}'>Delete</a></td></tr>";
}
echo "</table>";
endif; ?><?php if ($tab === 'user_details' && $view_user_id): ?><a href="?tab=users" class="back-link">← Back to Users</a>

<h3>User Details (ID: <?= $view_user_id ?>)</h3>
<?php
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $view_user_id"));
echo "<p><strong>Name:</strong> {$user['name']}<br><strong>Email:</strong> {$user['email']}</p>";// Cart Items $cart = mysqli_query($conn, "SELECT c.*, b.title, b.image, b.price FROM cart c JOIN books b ON c.book_id = b.book_id WHERE c.user_id = $view_user_id"); echo "<h4>Cart Items</h4><table><tr><th>Book</th><th>Image</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>"; $cart_total = 0; while ($item = mysqli_fetch_assoc($cart)) { $subtotal = $item['qty'] * $item['price']; $cart_total += $subtotal; echo "<tr><td>{$item['title']}</td><td><img src='book_images/{$item['image']}'></td><td>{$item['qty']}</td><td>${$item['price']}</td><td>${$subtotal}</td></tr>"; } echo "<tr><td colspan='4'><strong>Total</strong></td><td><strong>${$cart_total}</strong></td></tr></table>";

// Orders $orders = mysqli_query($conn, "SELECT o.*, b.title, b.image FROM orders o JOIN books b ON o.book_id = b.book_id WHERE o.user_id = $view_user_id"); echo "<h4>Orders</h4><table><tr><th>Book</th><th>Image</th><th>Qty</th><th>Amount</th><th>Date</th></tr>"; while ($order = mysqli_fetch_assoc($orders)) { echo "<tr><td>{$order['title']}</td><td><img src='book_images/{$order['image']}'></td><td>{$order['quantity']}</td><td>${$order['total_amount']}</td><td>{$order['order_date']}</td></tr>"; } echo "</table>"; ?>

<?php endif; ?><?php if ($tab === 'categories'): ?><form method="POST" enctype="multipart/form-data">
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
endif; ?><?php if ($tab === 'books'): ?><form method="POST" enctype="multipart/form-data">
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
endif; ?>
</body>
</html>