<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve role name from form
    $role_name = $_POST['role_name'];

    // Prepare and bind SQL statement
   

    $sql = "Select role_name from assRole WHERE role_name = '$role_name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    ?>
    <script>
        alert("This role already exists, Please try using another role");
        window.location.href = "Add_role.php";
    </script>
    <?php
    
   
}else{
     // Execute SQL statement
     $stmt = $conn->prepare("INSERT INTO assRole (role_name) VALUES (?)");
    $stmt->bind_param("s", $role_name);

     if ($stmt->execute()) {
        ?>
        <script>
            alert("You Have Successfully added new Role");
            window.location.href = "Add_role.php";
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Error adding role");
            window.location.href = "Add_role.php";

        </script>
        <?php
    }

}


   


}
?>
