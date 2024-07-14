<?php
session_start();
include("../Equip/Connection.php");

// Handle form submission for updating event
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : '';
    $title = isset($_POST['event_title']) ? $_POST['event_title'] : '';
    $description = isset($_POST['event_description']) ? $_POST['event_description'] : '';
    $event_date = isset($_POST['date']) ? $_POST['date'] : '';
    $event_time = isset($_POST['time']) ? $_POST['time'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : '';
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : '';

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

// SQL query to select events
$sql = "SELECT event_id, title, description, event_date, event_time, location, created_by, created_at FROM event";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Dashboard</title>
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
    <h1>College Dashboard</h1>
    <p id="current-time"></p>
</div>

<div class="sidebar">
    <a href="College_Dashboard.php"> College Dashboard</a>
    <a href="Create_Event.php">Create Event</a>
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
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
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
