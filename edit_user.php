<?php
require_once "config.php";
session_start();

// Check if admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Validate user ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$user_id = intval($_GET['id']);

// Fetch user info
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("User not found.");
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);

    $update = $db->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ?, username = ?, role = ? WHERE id = ?");
    $update->bind_param("ssssssi", $firstName, $lastName, $email, $phone, $username, $role, $user_id);

    if ($update->execute()) {
        $success = "User updated successfully!";
        header("Refresh:2; url=usermanage.php");
    } else {
        $error = "Failed to update user.";
    }

    $update->close();
}

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="universal.css">
    <link rel="stylesheet" href="navbar.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
        }

        .form-container {
            max-width: 600px;
            margin: 10rem auto 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="tel"], select {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1.2rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #1e293b;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 1rem;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #1e293b;
            text-decoration: underline;
        }

        nav {
            background-color: #1e293b;
            color: white;
        }

        .grid1 h1 {
            color: white;
        }

        nav ul {
            list-style-type: none;
            display: flex;
            gap: 1rem;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        .dropdowncontent ul {
            padding: 0;
            margin: 0;
        }

        .dropdowncontent ul li {
            list-style: none;
            margin: 0.5rem 0;
        }

        .dropdowncontent ul li a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="dashbg"></div>
    <nav>
        <div class="grid1">
            <h1 class="logo">Admin Panel</h1>
        </div>
        <div class="grid2">
            <ul>
                <li><a href="usermanage.php">User Management</a></li>
                <li><a href="bookingmanage.php">Booking Management</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="grid3">
            <span>Welcome, Admin</span>
            <div class="dropdowncontent">
                <ul>
                    <li><a href="usermanage.php">User Management</a></li>
                    <li><a href="bookingmanage.php">Booking Management</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="form-container">
        <h2>Edit User Details</h2>
        <?php if ($success) echo "<div class='success'>$success</div>"; ?>
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <label>First Name</label>
            <input type="text" name="firstName" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>

            <label>Last Name</label>
            <input type="text" name="lastName" value="<?php echo htmlspecialchars($user['lastname']); ?>">

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Phone</label>
            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label>Role</label>
            <select name="role" required>
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="driver" <?php if ($user['role'] == 'driver') echo 'selected'; ?>>Driver</option>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>

            <button type="submit" name="update">Update User</button>
        </form>
        <a href="usermanage.php" class="back-button">← Back to User Management</a>
    </div>
</body>
</html>
