<?php
require_once "config.php";
session_start();

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    $booking_id = intval($_POST['cancel_booking_id']);

    $delQuery = $db->prepare("DELETE FROM users_bookings WHERE id = ? AND username = ?");
    $delQuery->bind_param("is", $booking_id, $username);
    $delQuery->execute();
    $delQuery->close();

    header("Location: mybookings.php");
    exit();
}

$query = $db->prepare("SELECT id, address, destination, vehicle, notes, price, booking_date FROM users_bookings WHERE username = ? ORDER BY booking_date DESC");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Bookings</title>
    <link rel="stylesheet" type="text/css" href="BookingStyle2.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <nav>
        <div class="navcontainer">
            <div class="logocontainer">
                <ul>
                    <li id="Logo">BookingName</li>
                </ul>
            </div>
            <div class="linkcontainer">
                <ul id="links">
                    <li id="link"><a href="dashboard.php">Home</a></li>
                    <li id="link"><a href="booking.php">Booking</a></li>
                    <li id="link"><a href="mybookings.php">MyBookings</a></li>
                    <li id="link"><a href="profile.php">Profile</a></li>
                    <li id="link"><a href="logout.php">Log Out</a></li>
                </ul>
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
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');" style="display:inline;">
                                        <input type="hidden" name="cancel_booking_id" value="<?php echo (int)$row['id']; ?>" />
                                        <button type="submit" class="button-81" style="background-color: #e74c3c; border: none; color: white; padding: 8px 16px; cursor: pointer;">
                                            Cancel Booking
                                        </button>
                                    </form>
                                </td>
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