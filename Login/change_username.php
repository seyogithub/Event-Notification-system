<?php
session_start();
include("../Equip/Connection.php");

// Check if the user is not logged in or it's not their first login
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: loginform.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new-username'])) {
    $newUsername = mysqli_real_escape_string($conn, $_POST['new-username']);

    // Get the user_id from the session
    $userId = $_SESSION['user_id'];

    // Update username in the user table
    $updateUserQuery = "UPDATE user SET username='$newUsername' WHERE user_id='$userId'";
    if (mysqli_query($conn, $updateUserQuery)) {
        // If username update is successful, display success message and redirect to change_password.php
        echo "<script>alert('Username changed successfully');</script>";
        echo "<script>window.location.href = 'change_password.php';</script>";
        exit();
    } else {
        $error = "Failed to update username";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Username</title>
    <link rel="stylesheet" href="../css/signup.css">
    <style>
    </style>
</head>
<body>
    <div class="container" id="username-change-form">
        <div class="form-box">
            <h1 id="title">Change Username</h1>
            <form method="POST" action="">
                <div class="input-group">
                    <input type="text" id="new-username" placeholder="New Username" name="new-username">
                    <?php if (isset($error)) { ?>
                        <p class="error"><?php echo $error; ?></p>
                    <?php } ?>
                    <p class="error" id="New_Username">Please enter a new username</p>
                    <p class="error" id="username-Message"></p>
                    <div class="btn-field">
                        <input type="submit" name="change-username" value="Change Username">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('usernameChangeForm').addEventListener('submit', function(event) {
            var newUsername = document.getElementById('new-username').value.trim();
            var valid = true;

            if (newUsername === "") {
                document.getElementById('New_Username').innerHTML = "Please enter a new username";
                document.getElementById('New_Username').style.visibility = "visible";
                valid = false;
            } else {
                document.getElementById('New_Username').style.visibility = "hidden";
            }

            if (!valid) event.preventDefault();
        });
    </script>
</body>
</html>
