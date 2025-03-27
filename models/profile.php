<?php
session_start();
require_once "commonfolder/config.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$conn = new Config();
$error = '';
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

    <div id="profile">
        <h2>Employee Profile</h2>
        <img id="profilePic" src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="120"
            style="cursor: pointer;">
        <input type="file" id="profilePicUpload" accept="image/*" style="display: none;">
        <form id="profileForm">
            <div class="profile-container">
                <div class="editable-container">
                    <div class="profile-container">
                        <input type="text" name="fullname" id="fullnameInput"
                            value="<?php echo htmlspecialchars($user['fullname']); ?>" class="editable-name auto-save">
                    </div>
                </div>
                <h3 class="non-editable"><?php echo htmlspecialchars($user['email']); ?></h3>
                <div class="form-group">
                    <label>DOB</label>
                    <span class="dob-display" onclick="editDOB(this)">
                        <?php echo date('d M Y', strtotime($user['dob'])); ?>
                    </span>
                    <input type="date" name="dob" class="editable-input auto-save" value="<?php echo $user['dob']; ?>"
                        style="display: none;" onkeydown="handleKeyPress(event, this)">
                </div>
            </div>
        </form>
        <div class="column-container">
            <div class="column">
                <label>Qualifications:</label>
                <div id="qualifications">
                    <?php foreach ($qualifications as $q) { ?>
                        <div class="input-group">
                            <input type="text" name="qualifications[]" value="<?php echo htmlspecialchars($q); ?>"
                                class="auto-save">
                        </div>
                    <?php } ?>
                    <button type="button" id="addQualification" class="add-btn">Add Qualification</button>
                </div>
            </div>
            <div class="column">
                <label>Experiences:</label>
                <div id="experiences">
                    <?php foreach ($experiences as $e) { ?>
                        <div class="input-group">
                            <input type="text" name="experiences[]" value="<?php echo htmlspecialchars($e); ?>"
                                class="auto-save">
                        </div>
                    <?php } ?>
                    <button type="button" id="addExperience" class="add-btn">Add Experience</button>
                </div>
            </div>
        </div>
        <div class="column-container">
            <div class="column address">
                <label>Permanent Address:</label>
                <div class="input-group">
                    <input type="text" name="perm_address1" value="<?php echo $user['perm_address1']; ?>"
                        class="auto-save" placeholder="Address Line 1">
                    <input type="text" name="perm_address2" value="<?php echo $user['perm_address2']; ?>"
                        class="auto-save" placeholder="Address Line 2">
                    <input type="text" name="perm_city" value="<?php echo $user['perm_city']; ?>" class="auto-save"
                        placeholder="City">
                        <input type="text" name="perm_state" value="<?php echo $user['perm_state']; ?>" class="auto-save"
                        placeholder="State">
                </div>
            </div>
            <div class="column address">
                <label>Current Address:</label>
                <div class="input-group">
                    <input type="text" name="curr_address1" value="<?php echo $user['curr_address1']; ?>"
                        class="auto-save" placeholder="Address Line 1">
                    <input type="text" name="curr_address2" value="<?php echo $user['curr_address2']; ?>"
                        class="auto-save" placeholder="Address Line 2">
                    <input type="text" name="curr_city" value="<?php echo $user['curr_city']; ?>" class="auto-save"
                        placeholder="City">
                        <input type="text" name="curr_state" value="<?php echo $user['curr_state']; ?>" class="auto-save"
                        placeholder="State">
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </form>
    </div>
    <script>
          $(document).ready(function() {
           $(document).on("blur", ".auto-save", function() {
        const $field = $(this);
        const fieldName = $field.attr("name");
        const value = $field.val().trim();
        if ((fieldName === 'qualifications[]' || fieldName === 'experiences[]') && value === "") {
            $field.closest('.input-group').remove();
        }
        if (fieldName === 'qualifications[]') {
            saveQualifications();
        } 
        else if (fieldName === 'experiences[]') {
            saveExperiences();
        } 
       
        else {
            saveField(fieldName, value);
        }
    });
    $("#addQualification").click(function() {
        const newField = $(`
            <div class="input-group">
                <input type="text" name="qualifications[]" 
                       placeholder="Enter Qualification" 
                       class="auto-save">
            </div>
        `);
        newField.insertBefore(this);
        newField.find('input').focus();
    });
    $("#addExperience").click(function() {
        const newField = $(`
            <div class="input-group">
                <input type="text" name="experiences[]" 
                       placeholder="Enter Experience" 
                       class="auto-save">
            </div>
        `);
        newField.insertBefore(this);
        newField.find('input').focus();
    });
});
function saveField(field, value) {
    $.ajax({
        url: "update_profile.php",
        type: "POST",
        data: { field, value },
        success: function(response) {
            console.log("Saved:", field, response);
        },
        error: function(xhr, status, error) {
            console.error("Error saving", field, error);
        }
    });
}
function saveQualifications() {
    const qualifications = [];
    $("input[name='qualifications[]']").each(function() {
        const val = $(this).val().trim();
        if (val) qualifications.push(val);
    });
    saveField("qualifications", JSON.stringify(qualifications));
}

function saveExperiences() {
    const experiences = [];
    $("input[name='experiences[]']").each(function() {
        const val = $(this).val().trim();
        if (val) experiences.push(val);
    });
    saveField("experiences", JSON.stringify(experiences));
}
        function editDOB(element) {
            let input = element.nextElementSibling;
            element.style.display = "none";
            input.style.display = "inline-block";
            input.focus();
        }

        function handleKeyPress(event, input) {
            if (event.key === "Enter" || event.key === "Tab") {
                event.preventDefault();
                saveDOB(input);
            }
        }

        function saveDOB(input) {
            let span = input.previousElementSibling;

            if (input.value) {
                let date = new Date(input.value);
                let formattedDate = date.toLocaleDateString('en-GB', {
                    day: '2-digit', month: 'short', year: 'numeric'
                });
                span.textContent = formattedDate;
            }

            span.style.display = "inline-block";
            input.style.display = "none";
            $.ajax({
                url: "update_profile.php",
                type: "POST",
                data: { field: "dob", value: input.value },
                success: function (response) {
                    console.log("DOB updated:", response);
                },
                error: function () {
                    alert("Error updating DOB.");
                }
            });
        }
        $(document).ready(function () {
            console.log("Profile picture upload initialized");
            $(document).on('click', '#profilePic', function (e) {
                e.preventDefault();
                console.log("Profile picture clicked");
                $('#profilePicUpload').trigger('click');
            });
            $(document).on('change', '#profilePicUpload', function () {
                if (this.files && this.files[0]) {
                    console.log("File selected:", this.files[0].name);

                    var formData = new FormData();
                    formData.append('profile_pic', this.files[0]);
                    $.ajax({
                        url: 'update_profile.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            try {
                                var res = typeof response === 'string' ? JSON.parse(response) : response;
                                console.log("Upload response:", res);

                                if (res.status === 'success') {
                                    var newSrc = res.newPath + '?' + new Date().getTime();
                                    $('#profilePic').attr('src', newSrc);
                                    console.log("Image updated to:", newSrc);
                                } else {
                                    alert(res.message || "Upload failed");
                                }
                            } catch (e) {
                                console.error("Response parse error:", e, response);
                                alert("Error processing upload");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Upload error:", status, error);
                            alert("Upload failed: " + error);
                        }
                    });
                }
            });
        });

    </script>
</body>

</html>