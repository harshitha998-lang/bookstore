<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <meta http-equiv="refresh" content="10;url=index.php">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0fff0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .success-box {
            text-align: center;
            background: white;
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .checkmark {
            font-size: 60px;
            color: green;
        }
        h2 {
            margin-top: 20px;
            font-size: 28px;
            color: #333;
        }
        p {
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="success-box">
        <div class="checkmark">✅</div>
        <h2>Order Placed Successfully!</h2>
        <p>You will be redirected to the homepage shortly...</p>
    </div>
</body>
</html>