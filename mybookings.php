<?php
require_once "config.php";
session_start();

$username = $_SESSION['username'];

$query = $db->prepare("SELECT address, destination, vehicle, notes, price, booking_date FROM users_bookings WHERE username = ? ORDER BY booking_date DESC");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" type="text/css" href="BookingStyle2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>
<body>
    <nav>
        <div class="navcontainer">
            <div class="logocontainer">
                <ul>
                    <li id="Logo">
                        BookingName
                    </li>
                </ul>
            </div>
                <div class="linkcontainer">
                    <ul id="links">
                        <li id="link">
                            <a href="dashboard.php">Home</a>
                        </li>
                        <li id="link">
                            <a href="booking.php">Booking</a>
                        </li>
                        <li id="link">
                            <a href="mybookings.php">MyBookings</a>
                        </li>
                        <li id="link">
                            <a href="profile.php">Profile</a>
                        </li>
                        <!--echo "<li id="link><a href="admin.php>Admin</a></li>"-->
                        <li id="link">
                            <a href="logout.php">Log Out</a>
                        </li>
                    </ul>
                </div>
            <div class="dropdowncontainer">
                <p class="dropdown">
                    <span class="material-symbols-outlined">
                        menu
                    </span>
                </p>
                <div class="sidelink">
                    <ul id="links">
                        <li id="sidelink">
                            <a href="dashboard.php">Home</a>
                        </li>
                        <li id="sidelink">
                            <a href="booking.php">Booking</a>
                        </li>
                        <li id="sidelink">
                            <a href="mybookings.php">MyBookings</a>
                        </li>
                        <li id="sidelink">
                            <a href="profile.php">Profile</a>
                        </li>
                        <!--echo "<li id="sidelink><a href="admin.php>Admin</a></li>"-->
                        <li id="sidelink">
                            <a href="logout.php">Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div class="mycontainer">
            <h1>My Bookings</h1>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="booking-box">
                        <table class="booking-table">
                            <tr>
                                <th>Date:</th>
                                <td><?php echo date("m/d/Y", strtotime($row['booking_date'])); ?></td>
                            </tr>
                            <tr>
                                <th>Vehicle Type:</th>
                                <td><?php echo htmlspecialchars($row['vehicle']); ?></td>
                            </tr>
                            <tr>
                                <th>Pick Up / Address:</th>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                            </tr>
                            <tr>
                                <th>Destination:</th>
                                <td><?php echo htmlspecialchars($row['destination']); ?></td>
                            </tr>
                            <tr>
                                <th>Notes:</th>
                                <td><?php echo htmlspecialchars($row['notes']); ?></td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                            </tr>
                        </table>
                    </div>
                    <br>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php
$query->close();
$db->close();
?>