<?php
require_once "config.php";
require_once "session.php";
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        $error .= '<script>alert("Please enter your username.")</script>';
    }
    if (empty($password)) {
        $error .= '<script>alert("Please enter your password.")</script>';
    }

    if (empty($error)) {
        if ($query = $db->prepare("SELECT * FROM users WHERE username = ?")) {
            $query->bind_param('s', $username);
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION["username"] = $row["userName"];
                    $_SESSION['email'] = $row["email"];
                    $_SESSION['fname'] = $row["firstName"];
                    $_SESSION['lname'] = $row["lastName"];
                    $_SESSION['phone'] = $row["phone"];
                    $_SESSION['role'] = $row['role'];
                    
                    if ($row['role'] === 'admin') {
                        header("Location: usermanage.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit;
                    
                } else {
                    $error .= '<script>alert("Invalid password.")</script>';
                }
            } else {
                $error .= '<script>alert("No account found with that username.")</script>';
            }
        } else {
            $error .= '<script>alert("There was an error processing this request.")</script>';
        }
    }
    if (isset($query)) {
        $query->close();
    }
}
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="BookingStyle2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
    <script src="bookingFunction.js"></script>
</head>
<body>
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
    <div class="signinformcontainer">
        <form action="signin.php" method="POST">
            <label for="name">Username</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Password</label>
            <input type="password" name="password" id="showPW" required>
            <label for="showpassword">Show Password</label>
            <input type="checkbox" name="showpassword" onclick="showPassword()">
            <br>
            <button type="submit" name="submit" class="button-81" role="button">Sign In</button>
            <a href="signup.php" class="button-81" style="font-size: 13px;">Sign Up</a>
        </form>
    </div>
</body>
</html>