<?php
require_once "config.php";
require_once "session.php";
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

    if ($query = $db->prepare("SELECT * FROM users WHERE username = ?")) {
        $query->bind_param('s', $username);
        $query->execute();
        $query->store_result();

        if ($query->num_rows > 0) {
            $error .= 'The username is already taken.<br>';
        } else {
            $insertQuery = $db->prepare("INSERT INTO users (email,firstName,lastName,userName,password,phone,role) VALUES (?,?,?,?,?,?,?)");

            if ($insertQuery) {
                $insertQuery->bind_param("sssssss", $email, $firstname, $lastname, $username, $password_hash, $phone, $role);
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
            <label for="role">Role</label>
            <select name="role">
                <option value="user" selected>User</option>
                <option value="admin">Admin</option>
            </select>
            <br>
            <button type="submit" name="submit" class="button-81" role="button">Sign Up</button>
            <br>
            <a href="signin.php" class="button-81" style="font-size: 13px;">Sign In</a>
        </form>
    </div>
</body>
</html>