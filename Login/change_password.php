<?php
session_start();
include("../Equip/Connection.php");

// Check if the user is not logged in or it's not their first login
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: loginform.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new-password']) && isset($_POST['confirm-password'])) {
    $newPassword = mysqli_real_escape_string($conn, $_POST['new-password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm-password']);

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match";
    } elseif (!preg_match('/[a-zA-Z0-9!@#$%^&*]{8,}/', $newPassword)) {
        $error = "Please enter a valid password (at least 8 characters)";
    } else {
        // Get the user_id from the session
        $userId = $_SESSION['user_id'];
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update password in the user table
        
        $updatePasswordQuery = "UPDATE user SET password='$hashedPassword' WHERE user_id='$userId'";
        if (mysqli_query($conn, $updatePasswordQuery)) {
           
            // If password update is successful, display success message and redirect to the appropriate page
            echo "<script>alert('Password changed successfully');</script>";
            // Redirect based on the user's role
            switch ($_SESSION['role']) {
                case 'Student':
                    echo "<script>window.location.href = '../Student/Student_Dashboard.php';</script>";
                    break;
                case 'Instructor':
                    echo "<script>window.location.href = '../Instructor/Instructor_Dashboard.php';</script>";
                    break;
                case 'College Staff':
                    echo "<script>window.location.href = '../College/College_Dashboard.php';</script>";
                    break;
                case 'Registrar Staff':
                    echo "<script>window.location.href = '../Registrar/Registrar_Dashboard.php';</script>";
                    break;
                case 'Department Head':
                    echo "<script>window.location.href = '../Department Head/Department_Dashboard.php';</script>";
                    break;
                case 'Research and Community Service Staff':
                    echo "<script>window.location.href = '../Research and Community Service/Research and Community_Dashboard.php';</script>";
                    break;
                case 'Academic Staff':
                    echo "<script>window.location.href = '../Academic Staff/Acadamic_Dashboard.php';</script>";
                    break;
                default:
                    echo "<script>window.location.href = '../User/User_Dashboard.php';</script>";
                    break;
            }
            exit();
        } else {
            $error = "Failed to update password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../css/signup.css">
    <style>
        /* CSS styles remain unchanged */
    </style>
</head>
<body>
    <div class="container" id="password-change-form">
        <div class="form-box">
            <h1 id="title">Change Password</h1>
            <form method="POST" action="">
                <div class="input-group">
                    <input type="password" id="new-password" placeholder="New Password" name="new-password">
                    <p class="error" id="New_Password">Please enter a new password</p>
                    <input type="password" id="confirm-password" placeholder="Confirm Password" name="confirm-password">
                    <p class="error" id="Confirm_Password">Please confirm your password</p>
                    <?php if (isset($error)) { ?>
                        <p class="error"><?php echo $error; ?></p>
                    <?php } ?>
                    <p class="error" id="password-Message"></p>
                    <div class="btn-field">
                        <input type="submit" name="change-password" value="Change Password">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('passwordChangeForm').addEventListener('submit', function(event) {
            var newPassword = document.getElementById('new-password').value.trim();
            var confirmPassword = document.getElementById('confirm-password').value.trim();
            var valid = true;

            if (newPassword === "") {
                document.getElementById('New_Password').innerHTML = "Please enter a new password";
                document.getElementById('New_Password').style.visibility = "visible";
                valid = false;
            } else if (!/[a-zA-Z0-9!@#$%^&*]{8,}/.test(newPassword)) {
                document.getElementById('New_Password').innerHTML = "Please enter a valid password (at least 8 characters)";
                document.getElementById('New_Password').style.visibility = "visible";
                valid = false;
            } else {
                document.getElementById('New_Password').style.visibility = "hidden";
            }

            if (confirmPassword === "") {
                document.getElementById('Confirm_Password').innerHTML = "Please confirm your password";
                document.getElementById('Confirm_Password').style.visibility = "visible";
                valid = false;
            } else if (confirmPassword !== newPassword) {
                document.getElementById('Confirm_Password').innerHTML = "Passwords do not match";
                document.getElementById('Confirm_Password').style.visibility = "visible";
                valid = false;
            } else {
                document.getElementById('Confirm_Password').style.visibility = "hidden";
            }

            if (!valid) event.preventDefault();
        });
    </script>
</body>
</html>
