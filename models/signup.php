<span?php
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
<div id="profilePicContainer">
    <label for="uploadButton">
        <img src="Assets/images/default-profile.png" alt="Profile Picture" id="profilePreview">
        <button id="upload-btn">Upload Profile pic</button>
    </label>
    <input type="file" id="uploadButton" name="profile_pic" accept="image/*">
</div>
</div>
        <label>Email:</label>
        <input type="email" style=" width: 570px;" name="email" id="email" required>
        <div class="password-container">
    <div class="input-group">
        <label>Password:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div class="input-group">
        <label>Retype Password:</label>
        <input type="password" id="confirmPassword" name="confirm_password" required>
    </div>
</div>

<p id="passwordMessage" class="error-message"></p>
<p id="passwordPolicy" class="info-message">Allowed: a-z, A-Z, 0-9, $%@&*</p>
        <label>Qualifications:</label>
        <div id="qualifications">
            <input type="text" name="qualifications[]" placeholder="Enter Qualification" required>
        </div>
        <button type="button" id="addQualification">Add More</button>

        
        <label>Experiences:</label>
        <div id="experiences">
            <input type="text" name="experiences[]" placeholder="Enter Experience" required>
        </div>
        <button type="button"  style="style:none" id="addExperience">Add More</button>

        
        <label>Permanent Address:</label>
        <input type="text" name="perm_address1" placeholder="Address Line 1" required>
        <input type="text" name="perm_address2" placeholder="Address Line 2">
        <input type="text" name="perm_city" placeholder="City" required>
        <select name="perm_state" required>
            <option value="">Select State</option>
            <option value="UTTAR PRADESH">UTTAR PRADESH</option>
            <option value="DELHI">DELHI</option>
            <option value="UK">UTTRAKHAND</option>
        </select>       
        <label>Current Address:</label>
        <input type="text" name="curr_address1" placeholder="Address Line 1" required>
        <input type="text" name="curr_address2" placeholder="Address Line 2">
        <input type="text" name="curr_city" placeholder="City" required>
        <select name="curr_state" required>
        <option value="">Select State</option>
            <option value="UTTAR PRADESH">UTTAR PRADESH</option>
            <option value="DELHI">DELHI</option>
            <option value="UK">UTTRAKHAND</option>
        </select>
        <p>Already have an account? <a href="index.php?route=login">Login here</a></p>
        <a href="index.php?route=login"><button type="submit">Sign Up</button></a>
    </form>
    <script>
       $(document).ready(function () {
    $("#addQualification").click(function () {
        $("#qualifications").append('<input type="text" name="qualifications[]" placeholder="Enter Qualification">');
    });
    $("#addExperience").click(function () {
        $("#experiences").append('<input type="text" name="experiences[]" placeholder="Enter Experience">');
    });
    $("#signupForm").submit(function (event) {
        var email = $("#email").val().trim();
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            event.preventDefault(); 
            return false;
        }
    });
});
$("#uploadButton").change(function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $("#profilePreview").attr("src", e.target.result);
            $("#uploadText").text("Image selected");
        }  
        reader.readAsDataURL(file);
    }
});
$("#profilePicContainer").click(function() {
    $("#uploadButton").click();
});
document.addEventListener("DOMContentLoaded", function () {
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const message = document.getElementById("passwordMessage");
    const passwordPattern = /^[a-zA-Z0-9$%@&*]+$/;
    function validatePasswords() {
        const passValue = password.value;
        const confirmValue = confirmPassword.value;
        if (!passwordPattern.test(passValue)) {
            message.textContent = "Invalid characters in password. Allowed: a-z, A-Z, 0-9, $%@&*";
            message.style.display = "block";
            password.style.borderColor = "red";
            return;
        }
        password.style.borderColor = "#ccc"; // Reset border if valid
        if (passValue !== confirmValue && confirmValue.length > 0) {
            message.textContent = "Passwords do not match!";
            message.style.display = "block";
            confirmPassword.style.borderColor = "red";
        } else {
            message.style.display = "none";
            confirmPassword.style.borderColor = "#ccc";
        }
    }
    password.addEventListener("input", validatePasswords);
    confirmPassword.addEventListener("input", validatePasswords);
});
    </script>

</body>
</html>
