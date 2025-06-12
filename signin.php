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

        .login-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 1.5rem;
            color: #1e293b;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        .login-container label {
            text-align: left;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #1e293b;
        }

        .login-container input {
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .login-container button {
            background: #53a8b6;
            color: #fff;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-container button:hover {
            background: #1e293b;
        }

        .login-container a {
            color: #53a8b6;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 1rem;
            display: inline-block;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: blur(10px);
            opacity: 0.5;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="navbarsign.css" />
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
    <div class="login-container">
        <h1>Sign In</h1>
        <form action="signin.php" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit" name="submit">Sign In</button>
            <a href="signup.php">Don't have an account? Sign Up</a>
        </form>
    </div>
</body>
</html>