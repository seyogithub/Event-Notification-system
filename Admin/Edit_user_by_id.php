<<?php
session_start();
include("../Equip/Connection.php");

if (!isset($_SESSION['username']) || !isset($_SESSION['password']) || !isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit();
}

// Check if user_id is provided
if (!isset($_GET['user_id'])) {
    header('Location: Admin_Dashboard.php');
    exit();
}

$user_id = $_GET['user_id'];

// Fetch user details by user_id
$sql = "SELECT * FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id,);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found";
    exit();
}

$row = $result->fetch_assoc();

// Fetch roles from the database
$sql_roles = "SELECT * FROM assRole WHERE role_name != 'Admin'";
$stmt_roles = $conn->prepare($sql_roles);
$stmt_roles->execute();
$result_roles = $stmt_roles->get_result();

// Store roles in an array
$roles = [];
while ($role_row = $result_roles->fetch_assoc()) {
    $roles[] = $role_row['role_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.header {
    background-color: #333;
    color: white;
    padding: 15px;
    text-align: right;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
}

.header h1 {
    margin: 0;
    padding-left: 10px;
    float: left;
    font-size: 18px;
}

#current-time {
    margin-right: 10px;
    float: right;
    font-size: 14px;
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

.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.container h2 {
    text-align: center;
}

form {
    margin-top: 20px;
}

input[type="text"], select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #333;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
}

input[type="submit"]:hover {
    background-color: #555;
}
    </style>
</head>
<body>
<div class="header">
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

<div class="container">
    <h2>Edit User Details</h2>
    <form action="edit_user_form.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
        
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" value="<?php echo $row['fname']; ?>" required>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" value="<?php echo $row['lname']; ?>" required>

        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value="Male" <?php if($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if($row['gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select>
        <label for="age">Age:</label>
        <input type="text" id="age" name="age" value="<?php echo $row['age']; ?>" required>

        <label for="college">College:</label>
        <select id="college" name="college">
            <option value="" disabled>Select College</option>
            <option value="College of Engineering and Technology" <?php if($row['coll'] == 'College of Engineering and Technology') echo 'selected'; ?>>College of Engineering and Technology</option>
            <option value="College of Health and Medicine" <?php if($row['coll'] == 'College of Health and Medicine') echo 'selected'; ?>>College of Health and Medicine</option>
            <option value="College of Natural and Computational Science" <?php if($row['coll'] == 'College of Natural and Computational Science') echo 'selected'; ?>>College of Natural and Computational Science</option>
            <option value="College of Agricultural Science" <?php if($row['coll'] == 'College of Agricultural Science') echo 'selected'; ?>>College of Agricultural Science</option>
            <option value="College of Social Science and Humanities" <?php if($row['coll'] == 'College of Social Science and Humanities') echo 'selected'; ?>>College of Social Science and Humanities</option>
            <option value="College of Business and Economics" <?php if($row['coll'] == 'College of Business and Economics') echo 'selected'; ?>>College of Business and Economics</option>
            <option value="Software Engineering" <?php if($row['coll'] == 'Software Engineering') echo 'selected'; ?>>Software Engineering</option>
        </select>

        <label for="department">Department:</label>
        <select id="department" name="department">
            <option value="" disabled>Select Department</option>
            <option value="Software Engineering" <?php if($row['department'] == 'Software Engineering') echo 'selected'; ?>>Software Engineering</option>
            <option value="Information Technology" <?php if($row['department'] == 'Information Technology') echo 'selected'; ?>>Information Technology</option>
                <option value="Information System" <?php if($row['department'] == 'Information System') echo 'selected'; ?>>Information System</option>
                <option value="Computer Science" <?php if($row['department'] == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                <option value="Civil Engineering" <?php if($row['department'] == 'Civil Engineering') echo 'selected'; ?>>Civil Engineering</option>
                <option value="Construction Technology and Management" <?php if($row['department'] == 'Construction Technology and Management') echo 'selected'; ?>>Construction Technology and Management</option>
                <option value="Electrical Engineering" <?php if($row['department'] == 'Electrical Engineering') echo 'selected'; ?>>Electrical Engineering</option>
                <option value="Geomatics" <?php if($row['department'] == 'Geomatics') echo 'selected'; ?>>Geomatics</option>
                <option value="Mechanical Engineering" <?php if($row['department'] == 'Mechanical Engineering') echo 'selected'; ?>>Mechanical Engineering</option>
                <option value="Electromechanical Engineering" <?php if($row['department'] == 'Electromechanical Engineering') echo 'selected'; ?>>Electromechanical Engineering</option>
                <option value="Architecture" <?php if($row['department'] == 'Architecture') echo 'selected'; ?>>Architecture</option>
                </select>

                </select>

                <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="" disabled selected>Select Role</option>
            <?php
            // Loop through roles and generate options
            foreach ($roles as $role) {
                $selected = ($row['role'] == $role) ? 'selected' : '';
                echo "<option value=\"$role\" $selected>$role</option>";
            }
            ?>
        </select>
     

                <input type="submit" value="Update">
                </form>
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
