<?php
session_start();
require_once "commonfolder/config.php";


error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access!']);
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new Config();
header('Content-Type: application/json'); 


function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


if (!empty($_FILES["profile_pic"]["name"])) {
    $targetDir = "Assets/images/";
    $fileName = basename($_FILES["profile_pic"]["name"]);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png"];
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($fileExt, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, PNG files are allowed.']);
        exit();
    }

    if ($_FILES["profile_pic"]["size"] > $maxFileSize) {
        echo json_encode(['status' => 'error', 'message' => 'File size must be less than 2MB.']);
        exit();
    }

    $profilePic = $targetDir . uniqid() . "." . $fileExt;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profilePic)) {
        
        $result = $conn->query("SELECT profile_pic FROM users WHERE id = '$user_id'");
        $oldPic = $result->fetch_assoc()['profile_pic'];
        if ($oldPic && file_exists($oldPic)) {
            unlink($oldPic);
        }
        $conn->query("UPDATE users SET profile_pic = '$profilePic' WHERE id = '$user_id'");
        echo json_encode(['status' => 'success', 'message' => 'Profile picture updated successfully!', 'newPath' => $profilePic]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
    }
    exit();
}

if (!empty($_POST['field']) && isset($_POST['value'])) {
    $field = sanitizeInput($_POST['field']);
    $value = $_POST['value'];

    
    if ($field === "email") {
        echo json_encode(['status' => 'error', 'message' => 'Email cannot be updated!']);
        exit();
    }

    
    if ($field === "qualifications" || $field === "experiences") {
        $data = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data format!']);
            exit();
        }

        $table = ($field === "qualifications") ? "qualifications" : "experiences";
        $column = ($field === "qualifications") ? "qualification" : "experience";

        
        $conn->query("DELETE FROM $table WHERE user_id = '$user_id'");
        foreach ($data as $entry) {
            $cleanEntry = sanitizeInput($entry);
            if (!empty($cleanEntry)) {
                $conn->query("INSERT INTO $table (user_id, $column) VALUES ('$user_id', '$cleanEntry')");
            }
        }

        echo json_encode(['status' => 'success', 'message' => ucfirst($field) . ' updated successfully!']);
        exit();
    }

    
    $allowedFields = ['fullname', 'dob', 'perm_address1', 'perm_address2', 'perm_city', 
                      'curr_address1', 'curr_address2', 'curr_city'];

    if (in_array( $field,  $allowedFields)) {
        $conn->query("UPDATE users SET `$field` = '$value' WHERE id = '$user_id'");
        echo json_encode(['status' => 'success', 'message' => ucfirst($field) . ' updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid field specified!']);
    }
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request!']);
?>
