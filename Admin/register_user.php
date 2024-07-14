<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

function generateRandomString($length = 8) {
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function usernameExists($conn, $username) {
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Get form data
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$coll = $_POST['coll'];
$department = $_POST['department'];
$phone = $_POST['phone'];
$role = $_POST['role'];

// Generate a unique username
do {
    $username = 'user_' . generateRandomString(5);
} while (usernameExists($conn, $username));

// Generate password
$password = generateRandomString(10);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Get current date and time
$current_date = date('Y-m-d H:i:s');

// Prepare and bind SQL statement
$stmt = $conn->prepare("INSERT INTO user (fname, lname, gender, age, coll, department, username, password, phone, role, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssisssssss", $fname, $lname, $gender, $age, $coll, $department, $username, $hashed_password, $phone, $role, $current_date);

if ($stmt->execute()) {
    // Pass the credentials back to the form page
    header("Location: Add_new_user.php?username=$username&password=$password");
    exit();
} else {
    ?>
    <script>
        alert("Error registering the user");
        window.location.href = "Add_new_user.php";
    </script>
    <?php
}


?>
