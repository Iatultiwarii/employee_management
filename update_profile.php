<?php
session_start();
require_once "commonfolder/config.php";

$error = "";
$conn = new Config();
print_r($_POST);
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access!";
    exit();
}

$user_id = $_SESSION['user_id'];

// Sanitize function
function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(stripslashes(trim($data))));
}

// Handle Profile Picture Upload
if (!empty($_FILES["profile_pic"]["name"])) {
    $targetDir = "Assets/images/";
    $fileName = basename($_FILES["profile_pic"]["name"]);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png"];

    if (in_array($fileExt, $allowedTypes)) {
        $profilePic = $targetDir . uniqid() . "." . $fileExt;
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profilePic);

        $sql = "UPDATE users SET profile_pic = '$profilePic' WHERE id = '$user_id'";
        if ($conn->query($sql)) {
            echo "Profile picture updated successfully!";
        } else {
            echo "Error updating profile picture: " . $conn->error;
        }
        exit();
    }
}

// Handle General Field Updates
if (isset($_POST['field']) && isset($_POST['value'])) {
    $field = sanitizeInput($_POST['field']);
    $value = $_POST['value'];

    // Prevent updating email
    if ($field === "email") {
        echo "Email cannot be updated!";
        exit();
    }

    // Handle qualifications update
    if ($field === "qualifications") {
        $qualifications = json_decode($value, true);

        if (is_array($qualifications)) {
            $conn->query("DELETE FROM qualifications WHERE user_id='$user_id'");

            foreach ($qualifications as $qualification) {
                $qualification = sanitizeInput($qualification);
                $conn->query("INSERT INTO qualifications (user_id, qualification) VALUES ('$user_id', '$qualification')");
            }

            echo "Qualifications updated successfully!";
            exit();
        } else {
            echo "Invalid qualifications data!";
            exit();
        }
    }

    // Handle experiences update
    if ($field === "experiences") {
        $experiences = json_decode($value, true);

        if (is_array($experiences)) {
            $conn->query("DELETE FROM experiences WHERE user_id='$user_id'");

            foreach ($experiences as $experience) {
                $experience = sanitizeInput($experience);
                $conn->query("INSERT INTO experiences (user_id, experience) VALUES ('$user_id', '$experience')");
            }

            echo "Experiences updated successfully!";
            exit();
        } else {
            echo "Invalid experiences data!";
            exit();
        }
    }
print_r($_POST);
    // General field update for other inputs
    $value = sanitizeInput($value);
    $sql = "UPDATE users SET $field = '$value' WHERE id = '$user_id'";

    if ($conn->query($sql)) {
        echo ucfirst($field) . " updated successfully!";
    } else {
        echo "Error updating " . $field . ": " . $conn->error;
    }

    exit();
}
?>
