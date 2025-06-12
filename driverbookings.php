<?php
require_once "config.php";
session_start();

$driverUsername = $_SESSION['username'] ?? null;
if (!$driverUsername) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = intval($_POST['booking_id']);
    $action = $_POST['action'];

    if ($action === 'accept') {
        $status = 'accepted';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } elseif ($action === 'ended') {
        $status = 'ended';
    } else {
        $status = null;
    }

    if ($status) {
        $update_status = $db->prepare("UPDATE users_bookings SET driverstatus = ? WHERE id = ? AND driver = ?");
        $update_status->bind_param("sis", $status, $booking_id, $driverUsername);
        $update_status->execute();
        $update_status->close();
    }

    header("Location: driverbookings.php");
    exit();
}

$query = $db->prepare("SELECT * FROM users_bookings WHERE driver = ?");
$query->bind_param("s", $driverUsername);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Bookings</title>
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

        .container {
            margin-top: 7rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .container h1 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 2rem;
        }

        .booking-card {
            background: #bbe4e9;
            border-radius: 1.5rem;
            box-shadow: 0 4px 10px rgba(83, 168, 182, 0.3);
            padding: 20px 25px;
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
            color: #1e293b;
            font-weight: 600;
            transition: box-shadow 0.3s ease;
        }

        .booking-card:hover {
            box-shadow: 0 6px 14px rgba(30, 41, 59, 0.6);
        }

        .booking-card p {
            margin: 6px 0;
            font-weight: 700;
        }

        .booking-card strong {
            color: #3a7983;
        }

        form {
            display: inline;
            margin: 0 5px;
        }

        button {
            background-color: #53a8b6;
            border: none;
            border-radius: 1.5rem;
            padding: 0.6rem 1.4rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(166, 175, 195, 0.25);
            transition: background-color 0.3s ease, color 0.3s ease;
            user-select: none;
        }

        button:hover {
            background-color: #1e293b;
            color: #fff;
        }

        .no-bookings {
            text-align: center;
            color: #555;
            font-size: 1.2rem;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .booking-card {
                width: 100%;
                padding: 15px 20px;
            }
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
                <li><a href="driverbookings.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="grid3">
            <span class="material-symbols-outlined">menu</span>
            <div class="dropdowncontent">
                <ul>
                    <li><a href="driverbookings.php">Home</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="container">
            <h1>Bookings Assigned to You</h1>

            <?php if ($result->num_rows > 0): ?>
                <?php while ($booking = $result->fetch_assoc()): ?>
                    <div class="booking-card">
                        <p><strong>Booking ID:</strong> <?php echo $booking['id']; ?></p>
                        <p><strong>Pick Up:</strong> <?php echo htmlspecialchars($booking['address']); ?></p>
                        <p><strong>Destination:</strong> <?php echo htmlspecialchars($booking['destination']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($booking['booking_date']); ?></p>
                        <p><strong>Price:</strong> ₱<?php echo number_format($booking['price'], 2); ?></p>
                        <p><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($booking['driverstatus'] ?? 'pending')); ?></p>

                        <?php if (empty($booking['driverstatus']) || $booking['driverstatus'] === 'pending'): ?>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>" />
                                <button type="submit" name="action" value="accept">Accept</button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>" />
                                <button type="submit" name="action" value="reject">Reject</button>
                            </form>
                        <?php elseif ($booking['driverstatus'] === 'accepted'): ?>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>" />
                                <button type="submit" name="action" value="ended">Mark as Ended</button>
                            </form>
                        <?php else: ?>
                            <p><em>No further actions available.</em></p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-bookings">No bookings assigned to you currently.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php
$query->close();
$db->close();
?>
