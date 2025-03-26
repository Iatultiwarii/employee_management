<?php
session_start();

require './commonfolder/config.php'; // Database connection

$error = "";

// Rate-limiting login attempts
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
            // // $stmt->bind_param("s", $email);
            // $stmt->execute();
            // $stmt->store_result();
            // print_r($stmt) ;
            if ($stmt->num_rows == 1) {
                // $stmt->bind_result($id, $hashed_password);
                // $stmt->fetch();
                $data = mysqli_fetch_assoc($stmt);
                // $password = $_REQUEST['password'];
                $hashed_password = $data['password'];
                $user_id = $data['id'];
                // print_r($data);
                // die();
                if (password_verify($password, $hashed_password)) {
                    // Rehash if needed (PHP security upgrade)
                    // if (password_needs_rehash($hashed_password, PASSWORD_DEFAULT)) {
                    //     $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    //     $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    //     $update_stmt->bind_param("si", $new_hashed_password, $id);
                    //     $update_stmt->execute();
                    //     $update_stmt->close();
                    // }
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['email'] = $email;
                    // $_SESSION['login_attempts'] = 0; // Reset attempts on success
                    header("Location: index.php?route=profile");
                    exit();
                } else {
                    // $_SESSION['login_attempts']++;
                    $error = "Invalid email or password.";
                }
            } else {
                // $_SESSION['login_attempts']++;
                $error = "Invalid email or password.";
            }
            // $stmt->close();
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