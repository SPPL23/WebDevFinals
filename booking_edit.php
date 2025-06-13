<?php
require_once "config.php";
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

$username = $_GET['username'] ?? null;
$booking_date = $_GET['booking_date'] ?? null;

if (!$username || !$booking_date) {
    die("Missing username or booking date.");
}

$stmt = $db->prepare("SELECT * FROM users_bookings WHERE username = ? AND booking_date = ?");
$stmt->bind_param("ss", $username, $booking_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Booking not found.");
}

$booking = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $address = trim($_POST['address']);
    $destination = trim($_POST['destination']);
    $vehicletype = trim($_POST['vehicletype']);
    $notes = trim($_POST['notes']);
    $price = trim($_POST['price']);

    $update = $db->prepare("UPDATE users_bookings SET address = ?, destination = ?, vehicletype = ?, notes = ?, price = ? WHERE username = ? AND booking_date = ?");
    $update->bind_param("ssssdss", $address, $destination, $vehicletype, $notes, $price, $username, $booking_date);

    if ($update->execute()) {
        $success = "Booking updated successfully!";
        header("Refresh:2; url=bookingmanage.php");
    } else {
        $error = "Failed to update booking.";
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
    <title>Edit Booking</title>
    <link rel="stylesheet" href="universal.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 0;
            margin: 0;
        }

        nav {
            position: fixed;
            width: 100%;
            top: 0;
            background-color: #1e293b;
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 1rem 2rem;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
        }

        .nav-links ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .form-container {
            max-width: 600px;
            margin: 9rem auto 3rem auto;
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

        input[type="text"],
        input[type="number"],
        textarea,
        select {
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
            margin: 2rem auto 0 auto;
        }

        .back-button a {
            background: #53a8b6;
            color: white;
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .back-button a:hover {
            background: #1e293b;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">BookingName</div>
        <div class="nav-links">
            <ul>
                <li><a href="usermanage.php">User Management</a></li>
                <li><a href="bookingmanage.php">Booking Management</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="form-container">
        <h2>Edit Booking</h2>
        <?php if ($success) echo "<div class='success'>$success</div>"; ?>
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <label>Address</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($booking['address']); ?>" required>

            <label>Destination</label>
            <input type="text" name="destination" value="<?php echo htmlspecialchars($booking['destination']); ?>" required>

            <label>Vehicle Type</label>
            <select name="vehicletype" required>
                <option value="Car 4 Seater" <?php if ($booking['vehicletype'] == "Car 4 Seater") echo "selected"; ?>>Car 4 Seater</option>
                <option value="Car 6 Seater" <?php if ($booking['vehicletype'] == "Car 6 Seater") echo "selected"; ?>>Car 6 Seater</option>
                <option value="Car 10 Seater" <?php if ($booking['vehicletype'] == "Car 10 Seater") echo "selected"; ?>>Car 10 Seater</option>
                <option value="Tricycle" <?php if ($booking['vehicletype'] == "Tricycle") echo "selected"; ?>>Tricycle</option>
                <option value="Motorcycle" <?php if ($booking['vehicletype'] == "Motorcycle") echo "selected"; ?>>Motorcycle</option>
            </select>

            <label>Notes</label>
            <textarea name="notes"><?php echo htmlspecialchars($booking['notes']); ?></textarea>

            <label>Price</label>
            <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($booking['price']); ?>" required>

            <button type="submit" name="update">Update Booking</button>
        </form>
    </div>

    <div class="back-button">
        <a href="bookingmanage.php">← Return to Booking Management</a>
    </div>
</body>
</html>
