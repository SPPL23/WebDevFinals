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

// Select *all* bookings assigned to this driver (no status filtering)
$query = $db->prepare("SELECT * FROM users_bookings WHERE driver = ?");
$query->bind_param("s", $driverUsername);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Driver Bookings</title>
    <link rel="stylesheet" type="text/css" href="driverbookings.css">
</head>
<body>
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
                    <!-- Show Accept / Reject buttons only if pending -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>" />
                        <button type="submit" name="action" value="accept">Accept</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>" />
                        <button type="submit" name="action" value="reject">Reject</button>
                    </form>

                <?php elseif ($booking['driverstatus'] === 'accepted'): ?>
                    <!-- Show 'Mark as Ended' button only if accepted -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>" />
                        <button type="submit" name="action" value="ended">Mark as Ended</button>
                    </form>
                <?php else: ?>
                    <!-- For rejected or ended, no buttons -->
                    <p><em>No further actions available.</em></p>
                <?php endif; ?>
            </div>
            <hr>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No bookings assigned to you currently.</p>
    <?php endif; ?>

    <div class="logout-container" style="text-align: right; margin: 10px;">
        <form action="logout.php" method="POST" style="display:inline;">
            <button type="submit" class="button-81">Logout</button>
        </form>
    </div>
</body>
</html>

<?php
$query->close();
$db->close();
?>
