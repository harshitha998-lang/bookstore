<?php
session_start();

// DB connection
$host = 'localhost';
$dbname = 'bookstore_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$show_otp_form = false;
$message = "";
$success = false;

// Step 1: Request OTP
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && !isset($_POST['otp'])) {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_expiry'] = time() + 600;

        $message = "Your OTP (for testing): <strong>$otp</strong>";
        $show_otp_form = true;
    } else {
        $message = "<span class='error'>Email not found!</span>";
    }
}

// Step 2: Verify OTP & Reset Password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['otp']) && isset($_POST['new_password'])) {
    $entered_otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $email = $_SESSION['otp_email'] ?? '';

    if (
        isset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['otp_email']) &&
        $entered_otp == $_SESSION['otp'] &&
        time() < $_SESSION['otp_expiry']
    ) {
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $email]);

        unset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['otp_email']);
        $success = true;
        echo "<script>
                alert('Password reset successful!');
                window.location.href = 'loginindex.php';
              </script>";
        exit();
    } else {
        $message = "<span class='error'>Invalid or expired OTP.</span>";
        $show_otp_form = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
<link rel="stylesheet" href="forgot.css">
    <style>
        body {
           background: linear-gradient(90deg, #e2e2e2, #c9d6ff);
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 10px;
            color: #333;
            text-align: center;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2><?php echo $show_otp_form ? "Reset Password" : "Forgot Password"; ?></h2>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!$show_otp_form): ?>
        <form method="POST">
            <label>Email Address</label>
            <input type="email" name="email" required>
            <button type="submit">Send OTP</button>
        </form>
    <?php else: ?>
        <form method="POST">
            <label>Enter OTP</label>
            <input type="text" name="otp" required>
            <label>New Password</label>
            <input type="password" name="new_password" required>
            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>