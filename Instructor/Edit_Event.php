<?php
session_start();
include("../Equip/Connection.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['event_title'];
    $description = $_POST['event_description'];
    $event_date = $_POST['date'];
    $event_time = $_POST['time'];
    $location = $_POST['location'];
    $created_by = $_POST['created_by'];
    $updated_at = date('Y-m-d H:i:s'); // Set updated_at to current time

    $sql = "UPDATE event SET title = ?, description = ?, event_date = ?, event_time = ?, location = ?, created_by = ?, created_at = created_at, updated_at = ? WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $title, $description, $event_date, $event_time, $location, $created_by, $updated_at, $event_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event updated successfully";
    } else {
        $_SESSION['message'] = "Error updating event: " . $stmt->error;
    }

    $stmt->close();
    header("Location: index.php");
    exit();
}

// Retrieve event details for editing
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $sql = "SELECT event_id, title, description, event_date, event_time, location, created_by, created_at FROM event WHERE event_id = ?";
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
        <h1>Edit Event</h1>
        <?php if (isset($_SESSION['message'])) : ?>
            <p class="message"><?php echo $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_id); ?>">
            <label for="title">Event Title:</label>
            <input type="text" id="title" name="event_title" value="<?php echo htmlspecialchars($title); ?>" required><br>

            <label for="description">Event Description:</label>
            <textarea id="description" name="event_description" required><?php echo htmlspecialchars($description); ?></textarea><br>

            <label for="event_date">Date:</label>
            <input type="date" id="event_date" name="date" value="<?php echo htmlspecialchars($event_date); ?>" required><br>

            <label for="event_time">Time:</label>
            <input type="time" id="event_time" name="time" value="<?php echo htmlspecialchars($event_time); ?>" required><br>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" required><br>

            <label for="created_by">Created By:</label>
            <input type="text" id="created_by" name="created_by" value="<?php echo htmlspecialchars($created_by); ?>" required><br>

            <label for="created_at">Created At:</label>
            <input type="text" id="created_at" name="created_at" value="<?php echo htmlspecialchars($created_at); ?>" required><br>

            <button type="submit" name="update_event">Update Event</button>
        </form>
    </div>
</body>
</html>
