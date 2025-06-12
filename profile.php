<?php
require_once "config.php";
session_start();

$fname = $_SESSION['fname'] ?? '';
$lname = $_SESSION['lname'] ?? '';
$email = $_SESSION['email'] ?? '';
$phone = $_SESSION['phone'] ?? '';
$username = $_SESSION['username'] ?? '';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $new_fname = trim($_POST['fname']);
    $new_lname = trim($_POST['lname']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']);
    $new_password = trim($_POST['password']);

    if (!empty($new_password)) {
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $query = $db->prepare("UPDATE users SET firstName=?, lastName=?, email=?, phone=?, password=? WHERE userName=?");
        $query->bind_param("ssssss", $new_fname, $new_lname, $new_email, $new_phone, $password_hash, $username);
    } else {
        $query = $db->prepare("UPDATE users SET firstName=?, lastName=?, email=?, phone=? WHERE userName=?");
        $query->bind_param("sssss", $new_fname, $new_lname, $new_email, $new_phone, $username);
    }

    if ($query->execute()) {
        $_SESSION['fname'] = $new_fname;
        $_SESSION['lname'] = $new_lname;
        $_SESSION['email'] = $new_email;
        $_SESSION['phone'] = $new_phone;

        $fname = $new_fname;
        $lname = $new_lname;
        $email = $new_email;
        $phone = $new_phone;

        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile.";
    }
    $query->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(218, 218, 218);
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("https://i.ytimg.com/vi/E7OLBAfSLp0/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLCTpfn3iEJvLFr6IQ9NjmRz8jpx5g");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            z-index: -1;
            filter: blur(5px);
        }

        .profilecontainer {
            margin-top: 7rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .profilecontainer h1 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 2rem;
        }

        .profilecontainer form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }

        .profilecontainer label {
            font-weight: bold;
            color: #1e293b;
        }

        .profilecontainer input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        .profilecontainer button {
            background-color: #53a8b6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .profilecontainer button:hover {
            background-color: #1e293b;
        }

        .profilecontainer .success {
            color: green;
            text-align: center;
        }

        .profilecontainer .error {
            color: red;
            text-align: center;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="universal.css" />
    <link rel="stylesheet" type="text/css" href="navbar.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <div class="background"></div>
    <nav>
        <div class="grid1">
            <h1 class="logo">BookingName</h1>
        </div>
        <div class="grid2">
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="mybookings.php">MyBookings</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="grid3">
            <span class="material-symbols-outlined">menu</span>
            <div class="dropdowncontent">
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="booking.php">Booking</a></li>
                    <li><a href="mybookings.php">MyBookings</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="profilecontainer">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
            <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="POST" action="">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required />

                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>" required />

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />

                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" />

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep current password" />

                <button type="submit" name="submit">Save Changes</button>
            </form>
        </div>
    </main>
</body>
</html>