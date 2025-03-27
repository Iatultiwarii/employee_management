<?php
ob_start();
session_start();
require_once "commonfolder/config.php";

$conn = new Config();
$error = ""; 
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = sanitizeInput($_POST["fullname"]);
    $email = sanitizeInput($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash password
    $dob = sanitizeInput($_POST["dob"]); // ✅ Store as DATE (YYYY-MM-DD)
    $perm_address1 = sanitizeInput($_POST["perm_address1"]);
    $perm_address2 = sanitizeInput($_POST["perm_address2"]);
    $perm_city = sanitizeInput($_POST["perm_city"]);
    $perm_state = sanitizeInput($_POST["perm_state"]);
    $curr_address1 = sanitizeInput($_POST["curr_address1"]);
    $curr_address2 = sanitizeInput($_POST["curr_address2"]);
    $curr_city = sanitizeInput($_POST["curr_city"]);
    $curr_state = sanitizeInput($_POST["curr_state"]);
    $profilePic = "";

    
    if (!empty($_FILES["profile_pic"]["name"])) {
        $targetDir = "Assets/images/";
        $fileName = basename($_FILES["profile_pic"]["name"]);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($fileExt, $allowedTypes)) {
            die("Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
        }

        if ($_FILES["profile_pic"]["size"] > $maxFileSize) {
            die("Error: File size must be less than 2MB.");
        }

        $profilePic = $targetDir . uniqid() . "." . $fileExt;

        if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profilePic)) {
            die("Error uploading profile picture.");
        }
    }

    $sql = "SELECT id FROM users WHERE email = '$email'";
    $stmt = $conn->query($sql);
    if (!$stmt) {
        die("Error in email check query: " . $conn->error);
    }

    if ($stmt->num_rows > 0) {
        echo "Error: Email already registered.";
        exit();
    }


    $sql = "INSERT INTO users (fullname, email, password, dob, perm_address1, perm_address2, perm_city, perm_state, curr_address1, curr_address2, curr_city, curr_state, profile_pic) 
            VALUES ('$fullname', '$email', '$password', '$dob', '$perm_address1', '$perm_address2', '$perm_city', '$perm_state', '$curr_address1', '$curr_address2', '$curr_city', '$curr_state', '$profilePic')";
    
    $stmt = $conn->query($sql);
    
    if ($stmt) {
        $userId = $conn->getInsertId(); 
    } else {
        die("Insert failed: " . $conn->error);
    }

    if (!empty($_POST["qualifications"])) {
        foreach ($_POST["qualifications"] as $qualification) {
            $qualification = sanitizeInput($qualification);
            $conn->query("INSERT INTO qualifications (user_id, qualification) VALUES ('$userId', '$qualification')");
        }
    }
    if (!empty($_POST["experiences"])) {
        foreach ($_POST["experiences"] as $experience) {
            $experience = sanitizeInput($experience);
            $conn->query("INSERT INTO experiences (user_id, experience) VALUES ('$userId', '$experience')");
        }
    }
    header("Location: index.php?route=login");
    echo "<script>window.location.href='index.php?route=login';</script>";
    exit();
}
?>
