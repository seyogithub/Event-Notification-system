<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

// Get the role ID from the URL parameter
$role_id = $_GET['rol_id'];

// Fetch the existing role name from the database
$role_name = '';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT role_name FROM assRole WHERE rol_id = ?");
$stmt->bind_param("i", $role_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $role_name = $row['role_name'];
} else {
    echo '<script>alert("Role not found"); window.location.href = "Add_role.php";</script>';
    exit();
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_role_name = $_POST['role_name'];

    $sql = "Select role_name from assRole WHERE role_name = '$new_role_name'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        ?>
        <script>
            alert("This role already exists, Please try using another role");
            window.location.href = "Add_role.php";
        </script>
        <?php
    }
    else{
        $stmt = $conn->prepare("UPDATE assRole SET role_name = ? WHERE rol_id = ?");
        $stmt->bind_param("si", $new_role_name, $role_id);
    
        if ($stmt->execute()) {
            echo '<script>alert("Role updated successfully"); window.location.href = "Add_role.php";</script>';
        } else {
            echo '<script>alert("Error updating role");</script>';
        }
    }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
        .navbar h1, .navbar p {
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
            padding: 80px 20px 20px 20px;
        }
        .card {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card button {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .card button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Welcome, Admin</h1>
    <p id="current-time"></p>
</div>

<div class="sidebar">
    <a href="Admin_Dashboard.php">Dashboard</a>
    <a href="Add_new_user.php">Add new User</a>
    <a href="Edit_user.php">Edit User Detail</a>
    <a href="Add_role.php">Add and Edit Role</a>
    <br><br><br>
    <a href="../Equip/logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="card">
        <h2>Edit Role</h2>
        <form action="edit_role.php?rol_id=<?php echo $role_id; ?>" method="POST">
            <label for="role_name">Role Name:</label>
            <input type="text" id="role_name" name="role_name" value="<?php echo htmlspecialchars($role_name); ?>" required>
            <button type="submit">Update Role</button>
        </form>
    </div>
</div>

<script>
    function updateTime() {
        var now = new Date();
        var formattedTime = now.toLocaleString();
        document.getElementById('current-time').textContent = formattedTime;
    }
    setInterval(updateTime, 1000);
    updateTime();
</script>

</body>
</html>
