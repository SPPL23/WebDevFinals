<?php
require_once "config.php";
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$is_booked = false;

$check = $db->prepare("SELECT driverstatus FROM users_bookings WHERE username = ? AND driverstatus IN ('pending', 'accepted') LIMIT 1");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $is_booked = true;
}
$check->close();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book']) && !$is_booked) {
    $address = trim($_POST['address']);
    $destination = trim($_POST['destination']);
    $vehicletype = $_POST['vehicletype'];
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
    $price = floatval($_POST['price']);
    $pickupdate = $_POST['pickupdate'] ?? null;
    $time = $_POST['time'] ?? null;

    if (empty($address) || empty($destination) || empty($vehicletype) || empty($price) || empty($pickupdate) || empty($time)) {
        echo "All required fields must be filled.";
        exit();
    }

    $query = $db->prepare("INSERT INTO users_bookings (username, address, destination, vehicletype, notes, price, booking_date, time, driverstatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $query->bind_param("ssssssss", $username, $address, $destination, $vehicletype, $notes, $price, $pickupdate, $time);

    if ($query->execute()) {
        $booking_id = $db->insert_id;

        $driver_query = $db->prepare("
            SELECT username FROM users 
            WHERE role = 'driver' AND vehicletype = ? 
            ORDER BY RAND() LIMIT 1
        ");

        $driver_query->bind_param("s", $vehicletype);
        $driver_query->execute();
        $driver_result = $driver_query->get_result();

        if ($driver_result->num_rows > 0) {
            $driver = $driver_result->fetch_assoc();
            $assignedDriver = $driver['username'];

            $update_driver = $db->prepare("UPDATE users_bookings SET driver = ? WHERE id = ?");
            $update_driver->bind_param("si", $assignedDriver, $booking_id);
            $update_driver->execute();
            $update_driver->close();
        }
        $driver_query->close();

        header("Location: mybookings.php");
        exit();
    } else {
        echo "Booking error: " . $query->error;
    }

    $query->close();
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Booking</title>
    <link rel="stylesheet" type="text/css" href="booking.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
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
        <div class="bookingbg"></div>

        <div class="bookformcontainer">
            <?php if ($is_booked): ?>
                <p style="text-align: center; font-size: 18px; color: red;">
                    You already have a booking in progress (pending or accepted).<br>
                    Please complete or cancel it before making a new booking.<br>
                    Visit <a href="mybookings.php" id="is_booked">My Bookings</a> to view or manage it.
                </p>
            <?php else: ?>
                <form action="booking.php" method="POST">
                    <ul>
                        <li>
                            <div class="column1">
                                <label for="address">Address*</label>
                                <input type="text" name="address" required />
                                <br />
                                <label for="destination">Destination*</label>
                                <input type="text" name="destination" required />
                                <br />
                                <label for="pickupdate">Scheduled Pickup*</label>
                                <input type="date" name="pickupdate" id="pickupdate" style="text-align: center;" required />
                                <label for="time">Time*</label>
                                <input type="time" name="time" id="time" style="text-align: center;" required />
                            </div>
                        </li>
                        <li>
                            <div class="column2">
                                <label for="vehicle">Vehicle*</label>
                                <select name="vehicletype" id="vehicletype" style="text-align: center;">
                                    <option value="Car 4 Seater" selected>Car 4-Seater</option>
                                    <option value="Car 6 Seater">Car 6-Seater</option>
                                    <option value="Car 10 Seater">Car 10-Seater</option>
                                    <option value="Tricycle">Tricycle</option>
                                    <option value="Motorcycle">Motorcycle</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="column3">
                                <label for="notes">Notes</label>
                                <textarea name="notes" style="line-height: 15px;" rows="4" cols="25"></textarea>
                            </div>
                        </li>
                        <li>
                            <div class="column4">
                                <label for="price">Price</label>
                                <p id="priceDisplay">₱0.00</p>
                                <input type="hidden" name="price" id="priceInput" />
                            </div>
                        </li>
                    </ul>
                    <button type="submit" name="book" class="button-81" role="button">Book</button>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <script>
    const priceMap = {
        "Car 4 Seater": 500,
        "Car 6 Seater": 700,
        "Car 10 Seater": 1000,
        "Tricycle": 150,
        "Motorcycle": 100
    };

    const vehicletypeSelect = document.getElementById('vehicletype');
    const priceDisplay = document.getElementById('priceDisplay');
    const priceInput = document.getElementById('priceInput');

    function updatePrice() {
        const selectedVehicle = vehicletypeSelect.value;
        const price = priceMap[selectedVehicle] || 0;
        priceDisplay.textContent = `₱${price.toFixed(2)}`;
        priceInput.value = price;
    }

    vehicletypeSelect?.addEventListener('change', updatePrice);
    updatePrice();

    // Set min date to today
    const dateInput = document.getElementById('pickupdate');
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const minDate = `${yyyy}-${mm}-${dd}`;
    if (dateInput) {
        dateInput.setAttribute('min', minDate);
    }
    </script>
</body>
</html>
