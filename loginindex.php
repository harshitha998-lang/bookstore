<?php
session_start();
include 'db.php'; // Database connection

// LOGIN
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['user_type']; // using correct column name

                // Redirect based on user_type
                if ($user['user_type'] == 'admin') {
                    header("Location: admindashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "No user found with this email!";
        }
    } else {
        $error = "All fields are required!";
    }
}

// SIGNUP
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $user_type = 'user'; // default role

    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            $check_email = "SELECT * FROM users WHERE email='$email'";
            $result = mysqli_query($conn, $check_email);
            
            if (mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO users (name, email, password, user_type) VALUES ('$name', '$email', '$hashed_password', '$user_type')";
                if (mysqli_query($conn, $query)) {
                    $_SESSION['user_id'] = mysqli_insert_id($conn);
                    $_SESSION['user_type'] = $user_type;
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Something went wrong. Please try again!";
                }
            } else {
                $error = "Email is already registered!";
            }
        } else {
            $error = "Passwords do not match!";
        }
    } else {
        $error = "All fields are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="loginstyle.css" />
  </head>
  <body>
    <div class="container">
<!-- login form -->
      <div class="form-box login">
        <form action=" " method="post">
            <h1>login</h1>
            <div class="error">
             <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" 
                required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="password" 
                required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="forgot-link">
                <a href="reset_password.php">forgot password?</a>
            </div>
            <button type="submit" class="btn" name="login">login</button>
            <p>or login with social platforms</p>
            <div class="social-icons">
                <a href="#"><i class='bx bxl-google' ></i></a>
                <a href="#"><i class='bx bxl-facebook' ></i></a>
                <a href="#"><i class='bx bxl-github' ></i></a>
                <a href="#"><i class='bx bxl-linkedin' ></i></a>
            </div>
        </form>
      </div>

<!--Registration-->

      <div class="form-box register">
        <form action="" method="post">
            <h1>Registration</h1>
<div class="error">
</div>
           <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <div class="input-box">
                <input type="text" name="name" placeholder="Username" 
                required pattern="[A-Za-z\s]+" title="Only letter ans spaces allowed">
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" 
                required>
                <i class='bx bxs-envelope'></i>
            </div>
           
            <div class="input-box">
                <input type="password" name="password" placeholder="password" 
                required minlength="7" title="Password must be atleast 7 characters long">
                <i class='bx bxs-lock-alt'></i>
            </div>
           <div class="input-box">
                <input type="password" name="confirm_password" placeholder="password" 
                required minlength="7" title="Password must be atleast 7 characters long">
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button type="submit" class="btn"name="signup">Register</button>
            <p>or Register with social platforms</p>
            <div class="social-icons">
                <a href="#"><i class='bx bxl-google' ></i></a>
                <a href="#"><i class='bx bxl-facebook' ></i></a>
                <a href="#"><i class='bx bxl-github' ></i></a>
                <a href="#"><i class='bx bxl-linkedin' ></i></a>
            </div>
        </form>
      </div>

      
    <!--toggle-->
    <div class="toggle-box">
       <div class="toggle-panel toggle-left">
        <h1> Welcome Back!</h1>
        <p>Don't have an account?</p>
        <button class="btn register-btn">Register</button>
       </div>
       <div class="toggle-panel toggle-right">
        <h1>Hello,Welcome</h1>
        <p>Already have an account?</p>
        <button class="btn login-btn">Login</button>
       </div>
    </div>

    </div>


    <script src="loginscript.js"></script>
  </body>
</html>
