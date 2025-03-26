<?php
session_start();
require_once "commonfolder/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$conn=new Config();
$error='';

$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$qualifications = [];
$sql = "SELECT qualification FROM qualifications WHERE user_id = '$user_id'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $qualifications[] = $row['qualification'];
}


$experiences = [];
$sql = "SELECT experience FROM experiences WHERE user_id = '$user_id'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $experiences[] = $row['experience'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>User Profile</h2>
    <div id="profile">
    <img id="profilePic" src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="120" style="cursor: pointer;">
    <input type="file" id="profilePicUpload" accept="image/*" style="display: none;">

        <form id="profileForm">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

            <label>Full Name:</label>
            <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" class="auto-save">

            <label>Email:</label>
            <input type="email" value="<?php echo $user['email']; ?>" disabled>

            <label>Age:</label>
            <input type="number" name="age" value="<?php echo $user['age']; ?>" class="auto-save">

            <label>Permanent Address:</label>
            <input type="text" name="perm_address1" value="<?php echo $user['perm_address1']; ?>" class="auto-save">
            <input type="text" name="perm_address2" value="<?php echo $user['perm_address2']; ?>" class="auto-save">
            <input type="text" name="perm_city" value="<?php echo $user['perm_city']; ?>" class="auto-save">

            <label>Current Address:</label>
            <input type="text" name="curr_address1" value="<?php echo $user['curr_address1']; ?>" class="auto-save">
            <input type="text" name="curr_address2" value="<?php echo $user['curr_address2']; ?>" class="auto-save">
            <input type="text" name="curr_city" value="<?php echo $user['curr_city']; ?>" class="auto-save">

            <label>Qualifications:</label>
            <div id="qualifications">
                <?php foreach ($qualifications as $q) { ?>
                    <input type="text" name="qualifications[]" value="<?php echo $q; ?>" class="auto-save">
                <?php } ?>
                <button type="button" id="addQualification">Add More</button>
            </div>

            <label>Experiences:</label>
            <div id="experiences">
                <?php foreach ($experiences as $e) { ?>
                    <input type="text" name="experiences[]" value="<?php echo $e; ?>" class="auto-save">
                <?php } ?>
                <button type="button" id="addExperience">Add More</button>
            </div>
        </form>
    </div>

    <script>
       $(document).ready(function () {
    // Auto-save individual text fields
    $(".auto-save").on("change", function () {
        var field = $(this).attr("name");
        var value = $(this).val();

        $.ajax({
            url: "update_profile.php",
            type: "POST",
            data: { field: field, value: value },
            success: function (response) {
                console.log(response);
            },
            error: function () {
                alert("Error updating details.");
            }
        });
    });

    // Handle Qualifications Save
    $("#qualifications").on("change", "input", function () {
        var qualifications = [];
        $("#qualifications input").each(function () {
            qualifications.push($(this).val());
        });

        $.ajax({
            url: "update_profile.php",
            type: "POST",
            data: { field: "qualifications", value: JSON.stringify(qualifications) },
            success: function (response) {
                console.log(response);
            },
            error: function () {
                alert("Error updating qualifications.");
            }
        });
    });

    // Handle Experiences Save
    $("#experiences").on("change", "input", function () {
        var experiences = [];
        $("#experiences input").each(function () {
            experiences.push($(this).val());
        });

        $.ajax({
            url: "update_profile.php",
            type: "POST",
            data: { field: "experiences", value: JSON.stringify(experiences) },
            success: function (response) {
                console.log(response);
            },
            error: function () {
                alert("Error updating experiences.");
            }
        });
    });

    // Add New Qualification Field
    $("#addQualification").click(function () {
        $("#qualifications").append('<input type="text" name="qualifications[]" placeholder="Enter Qualification" class="auto-save">');
    });

    // Add New Experience Field
    $("#addExperience").click(function () {
        $("#experiences").append('<input type="text" name="experiences[]" placeholder="Enter Experience" class="auto-save">');
    });
});

    </script>
</body>
</html>
