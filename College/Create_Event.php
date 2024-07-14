<?php
session_start();
include("../Equip/Connection.php");

// Check if the session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if the session variable for user ID is set
    if (!isset($_SESSION['user_id'])) {
        die("User ID is not set in the session.");
    }

    $userId = $_SESSION['user_id'];
    $current_date = date('Y-m-d H:i:s');

    // Retrieve form data
    $event_title = $_POST['event_title'];
    $event_description = $_POST['event_description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    // Prepare the SQL query to insert the event data into the database
    $sql = "INSERT INTO event (title, description, event_date, event_time, location, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param("sssssis", $event_title, $event_description, $date, $time, $location, $userId, $current_date);

        // Execute the statement
        if ($stmt->execute()) {
            $message = "Event created successfully";
        } else {
            $message = "Error creating event: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error handling for statement preparation
        $message = "Error preparing statement: " . $conn->error;
    }
}
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

        .navbar h1,
        .navbar p {
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
            /* Center the card content */
        }

        .form-container {
            width: 80%;
            max-width: 600px;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            width: 200px;
            margin-right: 30px;
            text-align: right;
        }

        .form-control {
            width: 80%;
            max-width: 600px;
            /* Set a fixed width to ensure consistency */
            padding: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            /* Center the button */
            gap: 10px;
            /* Add space between buttons */
        }

        .btn {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #555;
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

            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-group label {
                width: 100%;
                margin-bottom: 5px;
                text-align: left;
            }

            .form-control {
                width: 100%;
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
        table {
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
        padding: 8px;
    }

    th {
        text-align: left;
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
            <div class="form-container">
                <?php if (isset($message)) : ?>
                    <p><?php echo $message; ?></p>
                <?php endif; ?>
                <form method="POST" action="">

                    <div class="form-group">
                        <label for="event_title">Event title:</label>
                        <input type="text" id="event_title" name="event_title" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="event_description">Event Description:</label>
                        <input type="text" id="event_description" name="event_description" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date">Event Date:</label>
                        <input type="date" id="date" name="date" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="time">Event Time:</label>
                        <input type="time" id="time" name="time" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location" required class="form-control">
                    </div>
                    
                    <div class="btn-container">
                        <input type="submit" value="Create Event" class="btn">
                        <a class="btn" href="instructor_Dashboard.php">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
    <div class = "form-container" >
    <h3>Events</h3>
    <table border="1">
        <tr>
            <th>S.No</th>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Time</th>
            <th>Location</th>
            <th>Created At</th>
        </tr>
        <?php
        include("../Equip/Connection.php");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to select events
        $sql = "SELECT title, description, event_date, event_time, location, created_by, created_at FROM event ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $count = 0;
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $count++;
                echo "<tr>
                        <td>" . $count . "</td>
                        <td>" . $row["title"] . "</td>
                        <td>" . $row["description"] . "</td>
                        <td>" . $row["event_date"] . "</td>
                        <td>" . $row["event_time"] . "</td>
                        <td>" . $row["location"] . "</td>
                        <td>" . $row["created_at"] . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>0 results</td></tr>";
        }
        ?>
    </table>

    </div>

   
</div>
    </div>


    <script>
        // Function to display the current date and time
        function displayCurrentTime() {
            const now = new Date();
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true
            };
            const formattedTime = now.toLocaleString('en-US', options);
            document.getElementById('current-time').textContent = formattedTime;
        }

        // Call the function to display the current time
        displayCurrentTime();

        // Update the current time every second
        setInterval(displayCurrentTime, 1000);
    </script>

</body>

</html>