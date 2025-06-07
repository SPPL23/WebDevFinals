<?php
require_once "config.php";
session_start();
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
                    $_SESSION["username"] = $row["username"];
                    $_SESSION['email'] = $row["email"];
                    $_SESSION['fname'] = $row["firstname"];
                    $_SESSION['lname'] = $row["lastname"];
                    $_SESSION['phone'] = $row["phone"];
                    $_SESSION['role'] = $row['role'];
                    
                    if ($row['role'] === 'admin') {
                        header("Location: usermanage.php");
                    } else if ($row['role'] === 'user') {
                        header("Location: dashboard.php");
                    } else {
                        header("Location: driverbookings.php");
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
    <link rel="stylesheet" type="text/css" href="Signin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
    <script src="bookingFunction.js"></script>
</head>
<body>
    <nav>
        <div class="grid1" style="transform: translateX(20rem);">
            <h1 class="logo">BookingName</h1>
        </div>
    </nav>
    <div class="progressbar"></div>
    <div class="signinformcontainer">
        <div class="formsign">
            <form action="signin.php" method="POST">
                <div class="spacing">
                    <label for="name">Username</label>
                    <input type="text" name="username" required>
                </div>
                <br>
                    <div class="spacing">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="showPW" required><br>
                </div>
                <div class="spacing">
                    <label for="showpassword">Show Password</label>
                    <input type="checkbox" name="showpassword" onclick="showPassword()">
                    <br>
                </div>
                <div class="spacing">
                    <button type="submit" name="submit" class="button-81" role="button">Sign In</button>
                    <a href="signup.php" class="button-81" style="font-size: 13px; background-color:rgb(50, 155, 172);">Sign Up</a>
                </div>
            </form>
        </div>
            <div class="picture"></div>
    </div>
</body>
</html>