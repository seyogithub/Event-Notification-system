<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

$selectedRole = isset($_POST['role']) ? $_POST['role'] : '';

// Fetch available roles for the search form
$roles = [];
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT DISTINCT role FROM user");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row['role'];
    }
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
        .card .button_edit {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: green;
            color: black;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .card .button_del {
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
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
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
        <h3>Search Users by Role</h3>
        <form method="POST" action="">
            <label for="role">Select Role:</label>
            <select id="role" name="role">
                <option value="">All Roles</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo htmlspecialchars($role); ?>" <?php if ($role == $selectedRole) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($role); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="card">
        <h3>List of Users</h3>
        <table>
            <tr>
                <th>S.No</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>College</th>
                <th>Department</th>
                <th>Username</th>
                <th>Role</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM user";
            if ($selectedRole) {
                $stmt = $conn->prepare($sql . " WHERE role = ?");
                $stmt->bind_param("s", $selectedRole);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $conn->query($sql . " ORDER BY fname, lname");
            }

            if ($result->num_rows > 0) {
                $count = 0;
                while ($row = $result->fetch_assoc()) {
                    $count++;
                    echo "<tr>
                            <td>" . $count . "</td>
                            <td>" . htmlspecialchars($row["fname"]) . "</td>
                            <td>" . htmlspecialchars($row["lname"]) . "</td>
                            <td>" . htmlspecialchars($row["gender"]) . "</td>
                            <td>" . htmlspecialchars($row["age"]) . "</td>
                            <td>" . htmlspecialchars($row["coll"]) . "</td>
                            <td>" . htmlspecialchars($row["department"]) . "</td>
                            <td>" . htmlspecialchars($row["username"]) . "</td>
                            <td>" . htmlspecialchars($row["role"]) . "</td>
                            <td>
                                <a href='Edit_user_by_id.php?user_id=" . urlencode($row["user_id"]) . "'>
                                    <button class='button_edit'>Edit</button>
                                </a>
                            </td>
                            <td>
                                <a href='Delete_user.php?user_id=" . urlencode($row["user_id"]) . "'>
                                    <button class='button_del'>Delete</button>
                                </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No results found</td></tr>";
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
