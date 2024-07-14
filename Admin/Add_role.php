<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch roles from the database
$roles = [];
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT rol_id, role_name FROM assRole");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .card .button_edit{
            margin-top: 10px;
            padding: 10px 15px;
            background-color:green;
            color: black;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .card .button_del{
            margin-top: 10px;
            padding: 10px 15px;
            background-color: red;
            color: black;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .card button:hover {
            background-color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
        <h2>Add New Role</h2>
        <form action="add_role1.php" method="POST">
            <label for="role_name">Role Name:</label>
            <input type="text" id="role_name" name="role_name" required>
            <button type="submit">Add Role</button>
        </form>
    </div>

    <div class="card">
        <h2>Roles</h2>
        <table>
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Role Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; ?>
                <?php foreach ($roles as $role): ?>
                    <?php $count++; ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo htmlspecialchars($role['role_name']); ?></td>
                        <td style = "text-align:center"><a href="edit_role.php?rol_id=<?php echo $role['rol_id']; ?>"> <button class = 'button_edit'>Edit</button> </a></td>
                        <td style = "text-align:center"><a href="delete_role.php?rol_id=<?php echo $role['rol_id']; ?>"> <button class = 'button_del'>Delete</button> </a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($roles)): ?>
                    <tr>
                        <td colspan="4">No roles found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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
