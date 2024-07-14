<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user_id
    $user_id = $_POST['user_id'];
    
    // Validate other form inputs
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $college = $_POST['college'];
    $department = $_POST['department'];
    
    // Update user details in the database
    $sql = "UPDATE user SET fname=?, lname=?, gender=?, age=?, coll=?, department=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $fname, $lname, $gender, $age, $college, $department, $user_id);
    
    if ($stmt->execute()) {
        ?>
        <script>
            alert("You Have Successfully Updated the User Details");
            window.location.href = "Edit_user.php";
        </script>
        
        <?php
    } else {
        ?>
        <script>
            alert("Error updating user details:");
            window.location.href = "Edit_user.php";

        </script>
        <?php
    }
    
    $stmt->close();
    $conn->close();
} else {
    ?>
        <script>
            alert("Invali Request");
            window.location.href = "Edit_user.php";

        </script>
        <?php
}
?>
