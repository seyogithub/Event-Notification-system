<?php
session_start();
include("../Equip/Connection.php");

// Redirect to index.php if session variables are not set
if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['change_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (!empty($old_password) && !empty($new_password) && !empty($confirm_password)) {
            $current_username = $_SESSION['username'];

            // Retrieve the current password from the database
            $query = "SELECT password FROM user WHERE username = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("s", $current_username);
                $stmt->execute();
                $stmt->bind_result($current_password);
                $stmt->fetch();
                $stmt->close();

                // Verify the old password
                if ($old_password == $current_password) {
                    // Update the password in the database
                    $update_query = "UPDATE user SET password = ? WHERE username = ?";
                    if ($update_stmt = $conn->prepare($update_query)) {
                        $update_stmt->bind_param("ss", $new_password, $current_username);

                        if ($update_stmt->execute()) {
                            $message = "Password changed successfully.";
                            echo '<script>alert("Password changed successfully.")</script>';
                        } else {
                            $message = "Error updating password: " . htmlspecialchars($update_stmt->error);
                            echo '<script>alert("Error updating password: ' . htmlspecialchars($update_stmt->error) . '")</script>';
                        }
                        $update_stmt->close();
                    } else {
                        $message = "Error preparing the update statement: " . htmlspecialchars($conn->error);
                        echo '<script>alert("Error preparing the update statement: ' . htmlspecialchars($conn->error) . '")</script>';
                    }
                } else {
                    $message = "Incorrect old password.";
                    echo '<script>alert("Incorrect old password.")</script>';
                }
            } else {
                $message = "Error preparing the select statement: " . htmlspecialchars($conn->error);
                echo '<script>alert("Error preparing the select statement: ' . htmlspecialchars($conn->error) . '")</script>';
            }
        } else {
            $message = "All password fields are required.";
            echo '<script>alert("All password fields are required.")</script>';
        }
    }


    // Handle form submission for changing username
    if (isset($_POST['change_username'])) {
        $new_username = $_POST['new_username'];
        $current_username = $_SESSION['username'];

        // Update the username in the database
        $update_query = "UPDATE user SET username = ? WHERE username = ?";
        if ($update_stmt = $conn->prepare($update_query)) {
            $update_stmt->bind_param("ss", $new_username, $current_username);

            if ($update_stmt->execute()) {
                $_SESSION['username'] = $new_username; // Update the session variable
                $message = "Username changed successfully.";
                echo '<script>alert("Username changed successfully.")</script>';
            } else {
                $message = "Error updating username: " . htmlspecialchars($update_stmt->error);
                echo '<script>alert("Error updating username: ' . htmlspecialchars($update_stmt->error) . '")</script>';
            }
            $update_stmt->close();
        } else {
            $message = "Error preparing the update statement: " . htmlspecialchars($conn->error);
            echo '<script>alert("Error preparing the update statement: ' . htmlspecialchars($conn->error) . '")</script>';
        }
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Staff Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;z
        }

        .navbar {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1,
        .navbar p {
            margin: 0;
        }

        .sidebar {
            width: 200px;
            background-color: #444;
            color: white;
            position: fixed;
            top: 60px;
            left: 0;
            height: calc(100% - 60px);
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        .main-content {
            margin-left: 220px;
            margin-right: 20px;
            padding: 80px 20px 20px 20px;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            top: 60px;
            width: calc(100% - 220px);
            margin-left: 220px;
            z-index: 999;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            width: 200px;
            margin-right: 10px;
            text-align: right;
        }

        .form-control {
            flex: 1;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #555;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }

            .sidebar a {
                float: left;
                text-align: center;
                width: 100%;
            }

            .main-content {
                margin-left: 0;
                padding-top: 140px;
            }

            .header {
                width: 100%;
                margin-left: 0;
                top: 60px;
            }

            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-group label {
                width: 100%;
                margin-bottom: 5px;
                text-align: left;
            }

            .form-control {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 10px;
                flex-direction: column;
            }

            .navbar h1 {
                font-size: 18px;
            }

            .header h2 {
                font-size: 16px;
            }

            .card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h1>Student Dashboard</h1>
        <p id="current-time"></p>
    </div>
    <div class="sidebar">
    <div class="sidebar">
    <a href="Student_Dashboard.php">Student_Dashboard</a>
    <a href="View_Event.php">View_Event</a>
    <a href="manage_profile.php">Manage Your Profile</a>
    <br><br><br>
    <a href="../Equip/logout.php">Logout</a>

</div>
    </div>
    <div class="main-content">
        <div class="card">
            <h2>Manage Profile</h2>
            <?php if (!empty($message)) : ?>
                <p><?= $message ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="old_password">Old Password:</label>
                    <input type="password" id="old_password" name="old_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" name="change_password" class="btn">Change Password</button>
            </form>
            <form method="post" action="">
                <div class="form-group">
                    <label for="new_username">New Username:</label>
                    <input type="text" id="new_username" name="new_username" class="form-control" required>
                </div>
                <button type="submit" name="change_username" class="btn">Change Username</button>
            </form>
        </div>
    </div>
    <script>
        function updateTime() {
            const currentTimeElement = document.getElementById("current-time");
            const now = new Date();
            const formattedTime = now.toLocaleString();
            currentTimeElement.textContent = formattedTime;
        }

        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>

</html>