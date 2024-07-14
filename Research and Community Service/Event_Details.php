<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

$role = $_GET['role'] ?? '';

// Fetch events by role
$events = [];
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT event.event_id, event.title, event.event_date, event.event_time, user.fname AS created_by 
FROM event 
JOIN user ON event.created_by = user.user_id WHERE user.role = ?");
$stmt->bind_param("s", $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$stmt->close();
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
    <h1>Academic Staff Dashboard</h1>
    <p id="current-time"></p>
</div>

<div class="sidebar">
    <a href="Acadamic_Dashboard.php">Dashboard</a>
    <a href="Create_Event.php">Create Event </a>
    <a href="Edit_Event_form.php">Edit and Delete Event</a>
    <a href="manage_profile.php">Manage Your Profile</a>
    <br><br><br>
    <a href="../Equip/logout.php">Logout</a>
</div>

<div class="main-content">
    <?php if (isset($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <table border="1">
        <thead>
            <tr>
                <th>Event ID</th>
                <th>Title</th>
                <th>Date</th>
                <th>Time</th>
                <th>Created By</th>
                <th>View Details </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['event_id']); ?></td>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($event['event_time']); ?></td>
                    <td><?php echo htmlspecialchars($event['created_by']); ?></td>
                    <td>
                        <button onclick='editEvent(<?php echo $event["event_id"]; ?>)'>View_Details_Event</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="editFormContainer">
    <!-- Edit form will be displayed here -->
</div>

<script>
    function editEvent(eventId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("editFormContainer").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "View_Details_Event.php?id=" + eventId, true);
        xhttp.send();
    }
</script>

</body>
</html>