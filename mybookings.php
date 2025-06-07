<?php
require_once "config.php";
session_start();

$username = $_SESSION['username'] ?? null;
if (!$username) {
    header("Location: signin.php");
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

$query = $db->prepare("SELECT id, address, destination, vehicletype, notes, price, booking_date, driver, time, driverstatus FROM users_bookings WHERE username = ?");
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
    <link rel="stylesheet" type="text/css" href="mybookings.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body style="background-color: rgb(218, 218, 218);">
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
                    <?php
                    $assignedDriverUsername = $row['driver'] ?? null;
                    $driver_result = null;
                    $driver_query = null;

                    if ($assignedDriverUsername) {
                        $driver_query = $db->prepare("
                            SELECT username, vehicletype, vehicle, plate 
                            FROM users 
                            WHERE username = ? 
                            LIMIT 1
                        ");
                        $driver_query->bind_param("s", $assignedDriverUsername);
                        $driver_query->execute();
                        $driver_result = $driver_query->get_result();
                    }

                    $driverStatus = $row['driverstatus'] ?? null;
                    ?>

                    <?php if ($driver_result !== null && $driver_result->num_rows > 0): ?>
                        <?php $driver = $driver_result->fetch_assoc(); ?>
                        <div class="driver-box">
                            <h3>Assigned Driver for <?php echo htmlspecialchars($driver['vehicletype']); ?>:</h3>
                            <p>
                                Name: <?php echo htmlspecialchars($driver['username']); ?><br />
                                Vehicle: <?php echo htmlspecialchars($driver['vehicle']); ?>, 
                                Plate: <?php echo htmlspecialchars($driver['plate']); ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="driver-box">
                            <p>No drivers currently available for this vehicle type.</p>
                        </div>
                    <?php endif; ?>

                    <?php
                    if ($driver_query !== null) {
                        $driver_query->close();
                    }
                    ?>

                    <div class="booking-box">
                        <table class="booking-table">
                            <tr>
                                <th>Date:</th>
                                <td><?php echo date("m/d/Y", strtotime($row['booking_date'])); ?></td>
                            </tr>
                            <tr>
                                <th>Vehicle Type:</th>
                                <td><?php echo htmlspecialchars($row['vehicletype']); ?></td>
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
                                <th>Pick Up Date:</th>
                                <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                            </tr>
                            <tr>
                                <th>Time:</th>
                                <td><?php echo htmlspecialchars($row['time']); ?></td>
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
                                <th>Driver Status:</th>
                                <td>
                                    <?php
                                    if ($driverStatus === null || $driverStatus === '') {
                                        echo "Pending";
                                    } else {
                                        echo ucfirst(htmlspecialchars($driverStatus));
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <?php if ($driverStatus === null || $driverStatus === 'pending'): ?>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');" style="display:inline;">
                                            <input type="hidden" name="cancel_booking_id" value="<?php echo (int)$row['id']; ?>" />
                                            <button type="submit" class="button-81" style="background-color: #e74c3c; border: none; color: white; padding: 8px 16px; cursor: pointer;">
                                                Cancel Booking
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <p style="color: gray; font-style: italic;">
                                            You cannot cancel this booking because the driver has <?php echo htmlspecialchars($driverStatus); ?> it.
                                        </p>
                                    <?php endif; ?>
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