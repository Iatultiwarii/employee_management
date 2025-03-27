<?php
session_start();
require './commonfolder/config.php'; // Database connection
$error = "";
$conn = new Config();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            $stmt = $conn->query("SELECT id, password FROM users WHERE email = '$email'");
            if ($stmt->num_rows == 1) {
                $data = mysqli_fetch_assoc($stmt);
                $hashed_password = $data['password'];
                $user_id = $data['id'];
                if (password_verify($password, $hashed_password)) {
                    
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['email'] = $email;
                  
                    header("Location: index.php?route=profile");
                    exit();
                } else {
                  
                    $error = "Invalid email or password.";
                }
            } else {
               
                $error = "Invalid email or password.";
            }
           
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <form id="loginForm" action="index.php?route=login" method="post">
            <h2>Login</h2>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <input type="email" id="email" name="email" placeholder="Email" required>
            <span id="emailError" class="error-message"></span>

            <input type="password" id="password" name="password" placeholder="Password" required>
            <span id="passwordError" class="error-message"></span>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="index.php?route=signup">Create New Account</a></p>
        </form>
    </div>
    <script src="js/validation.js"></script>
</body>
</html>