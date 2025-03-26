<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();
$route = isset($_GET['route']) ? $_GET['route'] : 'login';
try {
    switch ($route) {
        case 'login':
            if (file_exists('./models/login.php')) {
                include './models/login.php';
            } else {
                throw new Exception("Login file not found.");
            }
            break;
        case 'signup':
            if (file_exists('./models/signup.php')) {
                include './models/signup.php';
            } else {
                throw new Exception("Signup file not found.");
            }
            break;
        case 'profile':
            if (file_exists('./models/profile.php')) {
                include './models/profile.php';
            } else {
                throw new Exception('profile not found');
            }
            break;
        default:
            include './view/404.php'; // Include a 404 error page
            break;
    }
} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage();
}

?>