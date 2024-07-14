<?php
session_start();
include("../Equip/Connection.php");

// Debugging: Check session role
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Handle form submission for updating event
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : '';
    $title = isset($_POST['event_title']) ? $_POST['event_title'] : '';
    $description = isset($_POST['event_description']) ? $_POST['event_description'] : '';
    $event_date = isset($_POST['date']) ? $_POST['date'] : '';
    $event_time = isset($_POST['time']) ? $_POST['time'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : '';
    $created_at = date("Y-m-d H:i:s"); // Set created_at to current time

    $sql = "UPDATE event SET title = ?, description = ?, event_date = ?, event_time = ?, location = ?, created_by = ?, created_at = ? WHERE event_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssi", $title, $description, $event_date, $event_time, $location, $created_by, $created_at, $event_id);
        if ($stmt->execute()) {
            $message = "Event updated successfully";
        } else {
            $message = "Error updating event: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }
}

// Debugging: Output the role value
echo "Role: " . $role;

// SQL query to select events
$sql = "SELECT event.event_id, event.title, event.event_date, event.event_time, event.description, event.location, user.fname AS created_by, event.created_at 
FROM event 
JOIN user ON event.created_by = user.user_id WHERE user.role = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    } else {
        echo "No events found for role: " . $role;
    }
    $stmt->close();
} else {
    $message = "Error preparing statement: " . $conn->error;
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
            display: flex;
            justify-content: center;
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
    <h1>Department  dashboard</h1>
    <p id="current-time"></p>
</div>

<div class="sidebar">
  <a href="Department_Dashboard.php">Department Dashboard</a>
    <a href="Create_Event.php">Create Event </a>
    <a href="Edit_Event_form.php">Edit and Delete Event</a>
    <a href="manage_profile.php">Manage Your Profile</a>
    <br><br><br>
    <a href="../Equip/logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="card">
        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <table border="1">
            <tr>
                <th>Event ID</th>
                <th>Event Title</th>
                <th>Event Description</th>
                <th>Event Date</th>
                <th>Event Time</th>
                <th>Event Location</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php
            if (!empty($events)) {
                foreach ($events as $row) {
                    echo "<tr>
                            <td>" . $row["event_id"] . "</td>
                            <td>" . $row["title"] . "</td>
                            <td>" . $row["description"] . "</td>
                            <td>" . $row["event_date"] . "</td>
                            <td>" . $row["event_time"] . "</td>
                            <td>" . $row["location"] . "</td>
                            <td>" . $row["created_by"] . "</td>
                            <td>" . $row["created_at"] . "</td>
                            <td>
                                <button onclick='editEvent(" . $row["event_id"] . ")'>Edit</button>
                                <button onclick='confirmDelete(" . $row["event_id"] . ")'>Delete</button>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No results found</td></tr>";
            }
            ?>
        </table>
    </div>
    <div id="editFormContainer"></div>
</div>

<script>
    function editEvent(eventId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("editFormContainer").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "Edit_Event.php?id=" + eventId, true);
        xhttp.send();
    }

    function confirmDelete(eventId) {
        if (confirm("Are you sure you want to delete this event?")) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                    // Refresh the page after deletion
                    window.location.href = 'Edit_Event_form.php';
                }
            };
            xhttp.open("POST", "Delete_Event.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("event_id=" + eventId);
        }
    }
</script>
</body>
</html>
