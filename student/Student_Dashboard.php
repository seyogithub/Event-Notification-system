<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

$roles = ['Department head', 'Academic staff', 'Registrar staff', 'College Staff', 'Instructor', 'Research and community service staff'];
$userCounts = [];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

foreach ($roles as $role) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM event 
        JOIN user ON event.created_by = user.user_id 
        WHERE user.role = ?
    ");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $userCounts[$role] = $row['count'];
    $stmt->close();
}

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
    <h1>Academic Staff Dashboard</h1>
    <p id="current-time"></p>
</div>

<div class="sidebar">
    <a href="Student_Dashboard.php">Student_Dashboard</a>
    <a href="View_Event.php">View_Event</a>
    <a href="manage_profile.php">Manage Your Profile</a>
    <br><br><br>
    <a href="../Equip/logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="card-container">
        <?php foreach ($roles as $role): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($role); ?></h3>
                <p>Number of Events: <?php echo $userCounts[$role]; ?></p>
                <button onclick="showDetails('<?php echo htmlspecialchars($role); ?>')">See Details</button>
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

    function showDetails(role) {
        window.location.href = 'Event_Details.php?role=' + encodeURIComponent(role);
    }
</script>

</body>
</html>
