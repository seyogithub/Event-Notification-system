<?php
session_start();
include("../Equip/Connection.php");

$roles = [];
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT role_name FROM assRole WHERE role_name != 'Admin' ORDER BY role_name");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row['role_name'];
    }
}
// $sql_roles = "SELECT * FROM assRole ";
// $stmt_roles = $conn->prepare($sql_roles);
// $stmt_roles->execute();
// $result_roles = $stmt_roles->get_result();

// // Store roles in an array
// $roles = [];
// while ($role_row = $result_roles->fetch_assoc()) {
//     $roles[] = $role_row['role_name'];
// }

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

        .card form {
            max-width: 400px;
            margin: 0 auto;
        }
        .card form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .card form input[type="text"],
        .card form input[type="password"],
        .card form select,
        .card form input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .card form input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .card form input[type="submit"]:hover {
            background-color: #555;
        }
        /* Flexbox layout for label and input */
        .card form div {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card form label {
            flex-basis: 30%;
            text-align: right;
            margin-right: 10px;
        }
        .card form input[type="text"],
        .card form input[type="password"],
        .card form select,
        .card form input[type="number"] {
            flex-basis: 70%;
            margin-right: 0;
        }

        @media (max-width: 480px) {
            .card form input[type="text"],
            .card form input[type="password"],
            .card form select,
            .card form input[type="number"] {
                width: 100%;
            }
        }
        .popup {
            display: none;
            position: fixed;
            left: 60%;
            top: 20%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1001;
        }
        .popup .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
        }
        /* Timer Styles */
        .timer {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
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
        <h3>Register New User</h3>
        <form action="register_user.php" method="POST">
        <div>
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required>
        </div>
        <div>
            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" required>
        </div>
        <div>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div>
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>
        </div>
        
       
        <div>
            <label for="college">College:</label>
            <select id="college" name="coll" required>
                <option value="" disabled selected>Select College</option>
                <option value="College of Engineering and Technology">College of Engineering and Technology</option>
                <option value="College of Health and Medicine">College of Health and Medicine</option>
                <option value="College of Natural and Computational Science">College of Natural and Computational Science</option>
                <option value="College of Agricultural Science">College of Agricultural Science</option>
                <option value="College of Social Science and Humanities">College of Social Science and Humanities</option>
                <option value="College of Business and Economics">College of Business and Economics</option>
                <option value="Software Engineering">Software Engineering</option>
            </select>
        </div>
        <div>
            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="" disabled selected>Select Department</option>
                <option value="Software Engineering">Software Engineering</option>
                <option value="Information Technology">Information Technology</option>
                <option value="Information System">Information System</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Civil Engineering">Civil Engineering</option>
                <option value="Construction Technology and Management">Construction Technology and Management</option>
                <option value="Electrical Engineering">Electrical Engineering</option>
                <option value="Geomatics">Geomatics</option>
                <option value="Mechanical Engineering">Mechanical Engineering</option>
                <option value="Electromechanical Engineering">Electromechanical Engineering</option>
                <option value="Architecture">Architecture</option>
            </select>
    </div>
            <div>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <div>
            <label for="role">Role:</label>
            <select id="role" name="role">
                            <option value="" disabled selected>Select Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo htmlspecialchars($role); ?>"><?php echo htmlspecialchars($role); ?></option>
                            <?php endforeach; ?>
            </select>
        </div>
        <input type="submit" value="Register">
    </form>
    </div>
    
    <?php if (isset($_GET['username']) && isset($_GET['password'])): ?>
    <div id="popup" class="popup">
        <div class="timer" id="countdown-timer">5:00</div>
        <button class="close-btn" onclick="closePopup()">Close</button>
        <p>New record created successfully.</p>
        <p>Username: <?= htmlspecialchars($_GET['username']) ?></p>
        <p>Password: <?= htmlspecialchars($_GET['password']) ?></p>
    </div>
    <script>
        document.getElementById('popup').style.display = 'block';
        var countdownTimer = document.getElementById('countdown-timer');
        var timeLeft = 300;
        var timer = setInterval(function() {
            timeLeft--;
            var minutes = Math.floor(timeLeft / 60);
            var seconds = timeLeft % 60;
            countdownTimer.textContent = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
            if (timeLeft <= 0) {
                clearInterval(timer);
                closePopup();
            }
        }, 1000);

        function closePopup() {
            clearInterval(timer);
            document.getElementById('popup').style.display = 'none';
            // Clear username and password from the URL
            var urlWithoutParams = window.location.href.split('?')[0];
            history.replaceState(null, null, urlWithoutParams);
            // Redirect to Add_new_user.php
            window.location.href = 'Add_new_user.php';
        }

        setTimeout(closePopup, 300000); // 300000 milliseconds = 5 minutes
    </script>
<?php endif; ?>


    <div class="card">
        <h3>Users</h3>
        <table border="1">
            <tr>
                <th>S.No</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>College</th>
                <th>Department</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Date</th>
            </tr>
            <?php
            include("../Equip/Connection.php");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to select users
            $sql = "SELECT fname, lname, gender, age, coll, department,phone, role,date FROM user ORDER BY fname, lname";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $count = 0;
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $count++;
                    echo "<tr>
                            <td>" . $count . "</td>
                            <td>" . $row["fname"] . "</td>
                            <td>" . $row["lname"] . "</td>
                            <td>" . $row["gender"] . "</td>
                            <td>" . $row["age"] . "</td>
                            <td>" . $row["coll"] . "</td>
                            <td>" . $row["department"] . "</td>
                            <td>" . $row["phone"] . "</td>
                            <td>" . $row["role"] . "</td>
                            <td>" . $row["date"] . "</td>
                            
                        </tr>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
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
