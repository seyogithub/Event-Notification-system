<?php
session_start();
include("../Equip/Connection.php");

// Retrieve event details for viewing
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $sql = "SELECT event.event_id, event.title, event.description, event.event_date, event.event_time, event.location, user.fname AS created_by, event.created_at 
    FROM event 
    JOIN user ON event.created_by = user.user_id 
    WHERE event.event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $description = $row['description'];
        $event_date = $row['event_date'];
        $event_time = $row['event_time'];
        $location = $row['location'];
        $created_by = $row['created_by'];
        $created_at = $row['created_at'];
    } else {
        echo "Event not found.";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="date"],
        input[type="time"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #555;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: green;
        }
    </style>
</head>
<body>
<div class="container">
        <h1>View Event Details</h1>
        <?php if (isset($_SESSION['message'])) : ?>
            <p class="message"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <label for="title">Event Title:</label>
        <input type="text" id="title" name="event_title" value="<?php echo htmlspecialchars($title); ?>" disabled><br>

        <label for="description">Event Description:</label>
        <textarea id="description" name="event_description" rows="12" cols="50" disabled><?php echo htmlspecialchars($description); ?></textarea><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($event_date); ?>" disabled><br>

        <label for="time">Time:</label>
        <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($event_time); ?>" disabled><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" disabled><br>

        <label for="created_by">Created By:</label>
        <input type="text" id="created_by" name="created_by" value="<?php echo htmlspecialchars($created_by); ?>" disabled><br>

        <label for="created_at">Created At:</label>
        <input type="text" id="created_at" name="created_at" value="<?php echo htmlspecialchars($created_at); ?>" disabled><br>
    </div>
</body>
</html>
