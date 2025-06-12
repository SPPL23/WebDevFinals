<?php
require_once "config.php";
session_start();
$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $firstname = trim($_POST['fname']);
    $lastname = trim($_POST['lname']);
    $username = trim($_POST['username']);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];

    $vehiclename = '';
    $plate = '';
    $vehicletype = '';
    if ($role === 'driver') {
        $vehiclename = trim($_POST['vehicle']);
        $plate = trim($_POST['plate']);
        $vehicletype = trim($_POST['vehicletype']);
    }

    if ($query = $db->prepare("SELECT * FROM users WHERE username = ?")) {
        $query->bind_param('s', $username);
        $query->execute();
        $query->store_result();

        if ($query->num_rows > 0) {
            $error .= 'The username is already taken.<br>';
        } else {
            $insertQuery = $db->prepare("INSERT INTO users (email,firstname,lastname,username,password,phone,role,vehicle,plate,vehicletype) VALUES (?,?,?,?,?,?,?,?,?,?)");

            if ($insertQuery) {
                $insertQuery->bind_param("ssssssssss", $email, $firstname, $lastname, $username, $password_hash, $phone, $role, $vehiclename, $plate, $vehicletype);
                $result = $insertQuery->execute();

                if ($result) {
                    $success = 'Your registration is successful.';
                } else {
                    $error .= 'Something went wrong. Please try again later.<br>';
                }
                $insertQuery->close();
            } else {
                $error .= 'Failed to prepare the SQL insert query.<br>';
            }
        }
        $query->close();
    } else {
        $error .= 'Failed to prepare the SQL select query.<br>';
    }
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #1e293b, #53a8b6);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signup-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin-top: 5rem;
        }

        .signup-container h1 {
            margin-bottom: 1.5rem;
            color: #1e293b;
        }

        .signup-container form {
            display: flex;
            flex-direction: column;
        }

        .signup-container label {
            text-align: left;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #1e293b;
        }

        .signup-container input, .signup-container select {
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .signup-container button {
            background: #53a8b6;
            color: #fff;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .signup-container button:hover {
            background: #1e293b;
        }

        .signup-container a {
            color: #53a8b6;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 1rem;
            display: inline-block;
        }

        .signup-container a:hover {
            text-decoration: underline;
        }

        .driver-fields {
            display: none;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="navbarsign.css" />
    <script>
        function toggleDriverFields() {
            const roleSelect = document.getElementById("role");
            const driverFields = document.getElementById("driverFields");

            if (roleSelect.value === "driver") {
                driverFields.style.display = "block";
            } else {
                driverFields.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <nav>
        <div class="grid1">
            <h1 class="logo">BookingName</h1>
        </div>
        <div class="grid2">
            <ul>
            </ul>
        </div>
        <div class="grid3">
            <span class="material-symbols-outlined">menu</span>
            <div class="dropdowncontent">
            <ul>
            </ul>
            </div>
        </div>
    </nav>
    <div class="progressbar"></div>
    <div class="signup-container">
        <h1>Sign Up</h1>
        <?php
        if (!empty($success)) {
            echo "<p class='success'>$success</p>";
        }
        if (!empty($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>
        <form action="signup.php" method="POST">
            <label for="email">Email*</label>
            <input type="email" name="email" required>

            <label for="fname">First Name*</label>
            <input type="text" name="fname" required>

            <label for="lname">Last Name</label>
            <input type="text" name="lname">

            <label for="username">Username*</label>
            <input type="text" name="username" required>

            <label for="password">Password*</label>
            <input type="password" name="password" required>

            <label for="phone">Phone</label>
            <input type="text" name="phone">

            <label for="role">Role</label>
            <select name="role" id="role" onchange="toggleDriverFields()">
                <option value="user" selected>User</option>
                <option value="admin">Admin</option>
                <option value="driver">Driver</option>
            </select>

            <div id="driverFields" class="driver-fields">
                <label for="vehicle">Vehicle Model</label>
                <input type="text" name="vehicle">

                <label for="plate">Plate Number</label>
                <input type="text" name="plate">

                <label for="vehicletype">Vehicle Type</label>
                <select name="vehicletype">
                    <option value="Car 4 Seater">Car 4-Seater</option>
                    <option value="Car 6 Seater">Car 6-Seater</option>
                    <option value="Car 10 Seater">Car 10-Seater</option>
                    <option value="Tricycle">Tricycle</option>
                    <option value="Motorcycle">Motorcycle</option>
                </select>
            </div>

            <button type="submit" name="submit">Sign Up</button>
            <a href="signin.php">Already have an account? Sign In</a>
        </form>
    </div>
</body>
</html>