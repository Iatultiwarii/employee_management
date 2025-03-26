<?php
session_start();
require_once "commonfolder/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    
    <form id="signupForm" action="signup_process.php" method="POST" enctype="multipart/form-data">
    <h2>Signup</h2>
    <div class="form-row">
    <div class="column">
        <div>
        <label>Full Name:</label>
        <input type="text" style=" width: 400px;" name="fullname" required>
    </div>
    <div>
        <label>Date Of Birth:</label>
        <input type="date" style=" width: 400px;" name="dob" required>
    </div>
</div>

<!-- Profile Picture Upload Section -->
<div id="profilePicContainer">
    <label for="uploadButton">
        <img src="default-profile.png" alt="Profile Picture" id="profilePreview">
    </label>
    <input type="file" id="uploadButton" name="profile_pic" accept="image/*">
    <button id="customUploadButton">Upload Pic</button>
</div>

</div>




        <label>Email:</label>
        <input type="email" style=" width: 570px;" name="email"  required>
        <label>Password:</label>
        <input type="password" style=" width: 570px;" name="password" required>
        <!-- Qualifications (Dynamic Fields) -->
        <label>Qualifications:</label>
        <div id="qualifications">
            <input type="text" name="qualifications[]" placeholder="Enter Qualification" required>
        </div>
        <button type="button" id="addQualification">Add More</button>

        <!-- Experiences (Dynamic Fields) -->
        <label>Experiences:</label>
        <div id="experiences">
            <input type="text" name="experiences[]" placeholder="Enter Experience" required>
        </div>
        <button type="button"  style="style:none" id="addExperience">Add More</button>

        <!-- Permanent Address -->
        <label>Permanent Address:</label>
        <input type="text" name="perm_address1" placeholder="Address Line 1" required>
        <input type="text" name="perm_address2" placeholder="Address Line 2">
        <input type="text" name="perm_city" placeholder="City" required>
        <select name="perm_state" required>
            <option value="">Select State</option>
            <option value="State1">UTTAR PRADESH</option>
            <option value="State2">DELHI</option>
            <option value="State3">UTTRAKHAND</option>
        </select>

        <!-- Current Address -->
        <label>Current Address:</label>
        <input type="text" name="curr_address1" placeholder="Address Line 1" required>
        <input type="text" name="curr_address2" placeholder="Address Line 2">
        <input type="text" name="curr_city" placeholder="City" required>
        <select name="curr_state" required>
        <option value="">Select State</option>
            <option value="State1">UTTAR PRADESH</option>
            <option value="State2">DELHI</option>
            <option value="State3">UTTRAKHAND</option>
        </select>
        <!-- Profile Picture -->
        <p>Already have an account? <a href="index.php?route=login">Login here</a></p>
        <a href="index.php?route=login"><button type="submit">Sign Up</button></a>
    </form>

    <script>
        $(document).ready(function () {
            // Add Qualification Field
            $("#addQualification").click(function () {
                $("#qualifications").append('<input type="text" name="qualifications[]" placeholder="Enter Qualification">');
            });

            // Add Experience Field
            $("#addExperience").click(function () {
                $("#experiences").append('<input type="text" name="experiences[]" placeholder="Enter Experience">');
            });
        });
    </script>

</body>
</html>
