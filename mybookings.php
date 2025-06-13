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

$query = $db->prepare("
    SELECT ub.id, ub.address, ub.destination, ub.vehicletype, ub.notes, ub.price, ub.booking_date, ub.driver, ub.time, ub.driverstatus,
           u.firstname AS driver_firstname, u.lastname AS driver_lastname, u.phone AS driver_phone, u.vehicle AS driver_vehicle, u.plate AS driver_plate
    FROM users_bookings ub
    LEFT JOIN users u ON ub.driver = u.username
    WHERE ub.username = ?
");
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
    <style>
        body {
            background-color: rgb(218, 218, 218);
            font-family: Arial, sans-serif;
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

        .mycontainer {
            margin-top: 7rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .mycontainer h1 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 2rem;
        }

        .booking-box {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .booking-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .booking-box th, .booking-box td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .booking-box th {
            background-color: #53a8b6;
            color: white;
            text-align: center;
        }

        .booking-box td {
            color: #333;
        }

        .booking-box td p {
            margin: 0;
        }

        .driver-box {
            margin-top: 1rem;
            padding: 1rem;
            background: #e8f5e9;
            border-radius: 10px;
            color: #2e7d32;
        }

        .driver-box p {
            margin: 0.3rem 0;
        }

        .cancel-button {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cancel-button:hover {
            background-color: #c0392b;
        }

        .no-bookings {
            text-align: center;
            color: #555;
            font-size: 1.2rem;
            margin-top: 2rem;
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
        <div class="mycontainer">
            <h1>My Bookings</h1>

            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="booking-box">
                        <table>
                            <tr>
                                <th>Date</th>
                                <td><?php echo date("m/d/Y", strtotime($row['booking_date'])); ?></td>
                            </tr>
                            <tr>
                                <th>Vehicle Type</th>
                                <td><?php echo htmlspecialchars($row['vehicletype']); ?></td>
                            </tr>
                            <tr>
                                <th>Pick Up / Address</th>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                            </tr>
                            <tr>
                                <th>Destination</th>
                                <td><?php echo htmlspecialchars($row['destination']); ?></td>
                            </tr>
                            <tr>
                                <th>Pick Up Date</th>
                                <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                            </tr>
                            <tr>
                                <th>Time</th>
                                <td><?php echo htmlspecialchars($row['time']); ?></td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td><?php echo htmlspecialchars($row['notes']); ?></td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                            </tr>
                            <tr>
                                <th>Driver Status</th>
                                <td><?php echo ucfirst(htmlspecialchars($row['driverstatus'] ?? 'Pending')); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <?php if ($row['driverstatus'] === 'pending'): ?>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');" style="display:inline;">
                                            <input type="hidden" name="cancel_booking_id" value="<?php echo (int)$row['id']; ?>" />
                                            <button type="submit" class="cancel-button">Cancel Booking</button>
                                        </form>
                                    <?php else: ?>
                                        <p style="color: gray; font-style: italic;">
                                            You cannot cancel this booking because the driver has <?php echo htmlspecialchars($row['driverstatus']); ?> it.
                                        </p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>

                        <?php if ($row['driverstatus'] === 'accepted' && $row['driver']): ?>
                            <div class="driver-box">
                                <p><strong>Driver Name:</strong> <?php echo htmlspecialchars($row['driver_firstname'] . ' ' . $row['driver_lastname']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['driver_phone']); ?></p>
                                <p><strong>Vehicle:</strong> <?php echo htmlspecialchars($row['driver_vehicle']); ?></p>
                                <p><strong>Plate Number:</strong> <?php echo htmlspecialchars($row['driver_plate']); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-bookings">No bookings found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php
$query->close();
$db->close();
?>
