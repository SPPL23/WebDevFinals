<?php
require_once "config.php";
session_start();
$success = '';
$error = '';
$email_error = '';
$password_error = '';

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
    <link rel="stylesheet" type="text/css" href="BookingStyle2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
    <script src="bookingFunction.js"></script>
</head>
<body>
<?php
if (!empty($success)) {
    echo "<p class='success'>$success</p>";
}
if (!empty($error)) {
    echo "<p class='error'>$error</p>";
}
?>
    <nav>
        <div class="navcontainer">
            <ul>
                <li id="Logo">
                    BookingName
                </li>
            </ul>
            <!--
            <ul>
                <li id="navlink">
                    <a href="">Home</a>
                    <a href="">Booking</a>
                    <a href="">MyBookings</a>
                    <a href="">Settings</a>
                    <a href="">Log Out</a>
                </li>
            </ul>
            -->
        </div>
    </nav>
    <div class="signupformcontainer">
        <form action="signup.php" method="POST">
            <label for="email">Email*</label>
            <input type="email" name="email" required>
            <br>
            <label for="name">First name</label>
            <input type="text" name="fname" required>
            <br>
            <label for="name">Last name</label>
            <input type="text" name="lname">
            <br>
            <label for="name">Username*</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Password*</label>
            <input type="password" name="password" id="showPW" required>
            <br>
            <label for="password">Confirm Password*</label>
            <input type="password" name="cpassword" id="showCPW" required>
            <label for="showPW">Show Passwords</label>
            <input type="checkbox" onclick="showPassword(); showConfirmPassword()">
            <br>
            <label for="phone">Phone</label>
            <input type="text" name="phone">
            <br>
            <div id="driverFields" style="display: none;">
                <label for="car">Vehicle Model</label>
                    <input type="text" name="vehicle" id="vehicle">
                <br>
                <label for="plate">Plate Number</label>
                    <input type="text" name="plate" id="plate">
                <br>
                <label for="vehicle">Vehicle Type</label>
                <select name="vehicletype" id="vehicletype">
                    <option value="Car 4 Seater" selected>Car 4-Seater</option>
                    <option value="Car 6 Seater">Car 6-Seater</option>
                    <option value="Car 10 Seater">Car 10-Seater</option>
                    <option value="Tricycle">Tricycle</option>
                    <option value="Motorcycle">Motorcycle</option>
                    </select>
            </div>
            <br>
            <label for="role">Role</label>
            <select name="role" id="role" onchange="toggleDriverFields()">
                <option value="user" selected>User</option>
                <option value="admin">Admin</option>
                <option value="driver">Driver</option>
            </select>
            <br>
            <button type="submit" name="submit" class="button-81" role="button">Sign Up</button>
            <br>
            <a href="signin.php" class="button-81" style="font-size: 13px;">Sign In</a>
        </form>
    </div>

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
</body>
</html>