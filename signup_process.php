<?php
ob_start();
session_start();
require_once "commonfolder/config.php";
$error ="";
$conn = new Config();
// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
print_r($_POST);
echo 11;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize user inputs
    $fullname = sanitizeInput($_POST["fullname"]);
    $email = sanitizeInput($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash password
    $age = intval($_POST["age"]);
    // Address details
    $perm_address1 = sanitizeInput($_POST["perm_address1"]);
    $perm_address2 = sanitizeInput($_POST["perm_address2"]);
    $perm_city = sanitizeInput($_POST["perm_city"]);
    $perm_state = sanitizeInput($_POST["perm_state"]);
    
    $curr_address1 = sanitizeInput($_POST["curr_address1"]);
    $curr_address2 = sanitizeInput($_POST["curr_address2"]);
    $curr_city = sanitizeInput($_POST["curr_city"]);
    $curr_state = sanitizeInput($_POST["curr_state"]);
echo 99;
    // Profile picture upload handling
    $profilePic = "";
    if (!empty($_FILES["profile_pic"]["name"])) {
        $targetDir = "Assets/images/";
        $fileName = basename($_FILES["profile_pic"]["name"]);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        // Allowed file types
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (in_array($fileExt, $allowedTypes)) {
            $profilePic = $targetDir . uniqid() . "." . $fileExt;
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profilePic);
        }
    }
echo 77;
    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = '$email'";
$stmt = $conn->query($sql);
if (!$stmt) {
    die("Error in email check query: " . $conn->error);
}
if ($stmt->num_rows > 0) {
    echo "Error: Email already registered.";
    echo"0";
    exit();
}
print_r($_POST);
echo 12;
    // Insert user details into `users` table
    $sql = "INSERT INTO users (fullname, email, password, age, perm_address1, perm_address2, perm_city, perm_state, curr_address1, curr_address2, curr_city, curr_state, profile_pic) 
            VALUES ('$fullname', '$email', '$password', '$age', '$perm_address1', '$perm_address2', '$perm_city', '$perm_state', '$curr_address1', '$curr_address2', '$curr_city', '$curr_state', '$profilePic')";
    $stmt = $conn->query($sql);       
        if ($stmt) {
            $userId = $conn->getInsertId(); // Use the method to get last inserted ID
            echo "User ID: " . $userId; // Debugging
        } else {
            die("Insert failed: " . $conn->error());
        }
        
        // Insert qualifications
        if (!empty($_POST["qualifications"])) {
            foreach ($_POST["qualifications"] as $qualification) {
                $qualification = sanitizeInput($qualification);
                $sql = "INSERT INTO qualifications (user_id, qualification) VALUES ('$userId', '$qualification')";
                $stmt = $conn->query($sql);
            }
        }
        // Insert experiences
        if (!empty($_POST["experiences"])) {
            foreach ($_POST["experiences"] as $experience) {
                $experience = sanitizeInput($experience);
                $sql = "INSERT INTO experiences (user_id, experience) VALUES ('$userId', '$experience')";
                $stmt = $conn->query($sql);
            }
        }
        // Redirect to login page
        header("Location:index.php?route=login");
        echo "<script>window.location.href='index.php?route=login';</script>";
exit();
       
    } 
    else 
    {
        echo "Error: " . $conn->error;
    }


?>
