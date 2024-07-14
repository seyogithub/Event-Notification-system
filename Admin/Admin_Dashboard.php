<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch user counts by role
$roles = [
    'Student', 'Department head', 'Academic staff', 'Registrar staff',
    'College Staff', 'Instructor', 'Research and community service staff'
];
$userCounts = [];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

foreach ($roles as $role) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM user WHERE role = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $userCounts[$role] = $row['count'];
}

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
            text-align:center;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar .log{
            margin-top:100%;
        }
        .sidebar .logout{
          background-color:red;
          color:black;
          bottom:0;
        }
        .sidebar a:hover {
            background-color: white;
            color:black;
            font-weight:bolder;
            padding:10px;
        }
        .main-content {
            margin-left: 220px;
            padding: 80px 20px 20px 20px;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 0 0 calc(30% - 10px); /* Decreased width */
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
<div class="log">
    <a href="../Equip/logout.php" class="logout">Logout</a>
</div>
    
</div>

<div class="main-content">
    <div class="card-container">
        <?php foreach ($roles as $role): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($role); ?></h3>
                <p>Number of users: <?php echo $userCounts[$role]; ?></p>
                <button onclick="showDetails('<?php echo htmlspecialchars($role); ?>', <?php echo $userCounts[$role]; ?>)">See Details</button>
            </div>
        <?php endforeach; ?>
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

    function showDetails(role, count) {
        if (count > 0) {
            window.location.href = 'User_Details.php?role=' + encodeURIComponent(role);
        } else {
            alert('No users with the role ' + role);
        }
    }
</script>

</body>
</html>
