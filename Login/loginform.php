<?php
session_start();
include("../Equip/Connection.php");

$roles = [];
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT role_name FROM assRole ORDER BY role_name");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row['role_name'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/signup.css">
    <style>
        input, textarea {
            height: 50px;
            margin: 4px 0;
            padding: 0 15px;
            border: none;
            border-radius: 25px;
            background-color: #f5f5f5;
            transition: background-color 0.3s, color 0.3s;
            outline: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        input::placeholder, textarea::placeholder {
            color: #aaa;
        }
        body {
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            background-color: #f4f4f4;
        }
        .error {
            color: red;
            visibility: hidden;
            height: 1em;
            margin-top: 4px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                height: auto;
                width: 94%;
            }
            .form-box {
                width: 100%;
            }
            .input-field {
                width: calc(100% - 20px);
            }
            .form-box {
                width: 80%;
                background: rgba(255, 255, 255, 0.9);
                box-shadow: 0 0px 15px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                text-align: center;
            }
            .input-group {
                padding: 1%;
            }
            .input-field {
                width: 100%;
                background: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: .35rem;
                outline: none;
                transition: border-color 0.3s;
                font-size: 1rem;
            }
        }
        @media (max-width: 500px) {
            .container {
                width: 94%;
            }
            .form-box {
                width: 90%;
                background: rgba(255, 255, 255, 0.9);
                box-shadow: 0 0px 15px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                text-align: center;
            }
        }
        @media (max-width: 400px) {
            .container {
                width: 94%;
            }
            .form-box {
                width: 98%;
                background: rgba(255, 255, 255, 0.9);
                box-shadow: 0 0px 15px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container" id="signin-form">
        <div class="form-box login">
            <h1 id="title">Login</h1>
            <?php if (isset($_GET['error'])): ?>
                <p class="error" style="visibility: visible;"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            <form id="myForm" action="login.php" method="post">
                <div class="input-group">
                    <input type="text" id="User-Name" placeholder="User Name" name="user-name">
                    <p class="error" id="User_Name">Please enter user name</p>
                    <input type="password" id="password" placeholder="Password" name="Password">
                    <p class="error" id="Password">Please enter password</p>
                    <p class="error" id="login-Message">User name or password is not correct!</p>
                    <div class="btn-field">
                        <select id="role" name="role">
                            <option value="" disabled selected>Login As</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo htmlspecialchars($role); ?>"><?php echo htmlspecialchars($role); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="error" id="role-error">Please select your role</p>
                        <input type="submit" name="login" value="Login">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('myForm').addEventListener('submit', function(event) {
            var userName = document.getElementById('User-Name').value.trim();
            var password = document.getElementById('password').value.trim();
            var role = document.getElementById('role').value;
            var valid = true;

            if (userName === "") {
                document.getElementById('User_Name').style.visibility = 'visible';
                valid = false;
            } else {
                document.getElementById('User_Name').style.visibility = 'hidden';
            }

            if (password === "") {
                document.getElementById('Password').style.visibility = 'visible';
                valid = false;
            } else {
                document.getElementById('Password').style.visibility = 'hidden';
            }

            if (role === "") {
                document.getElementById('role-error').style.visibility = 'visible';
                valid = false;
            } else {
                document.getElementById('role-error').style.visibility = 'hidden';
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    </script>
</body>


</html>
