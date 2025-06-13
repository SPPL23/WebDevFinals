<?php
require_once "config.php";
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$is_booked = false;

// Check if user already has a pending or accepted booking
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

    $query = $db->prepare("INSERT INTO users_bookings (username, address, destination, vehicletype, notes, price, booking_date, time, driverstatus, driver) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', '')");
    $query->bind_param("ssssssss", $username, $address, $destination, $vehicletype, $notes, $price, $pickupdate, $time);

    if ($query->execute()) {
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
    <style>
        main {
            margin-top: 7rem;
        }

        .bookingbg {
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

        .bookformcontainer {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            width: 800px;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
        }

        .bookformcontainer h2 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .bookformcontainer label {
            font-weight: bold;
            color: #1e293b;
        }

        .bookformcontainer input, .bookformcontainer select, .bookformcontainer textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .bookformcontainer button {
            background-color: #53a8b6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }

        .bookformcontainer button:hover {
            background-color: #1e293b;
        }

        .bookformcontainer p {
            color: red;
            text-align: center;
        }

        .bookformcontainer a {
            color: #1e293b;
            padding: 0;
        }

        a#is_booked {
            background-color: transparent;
        }

        .button-group {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="universal.css" />
    <link rel="stylesheet" type="text/css" href="navbar.css" />
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
            <p>
                You already have a booking in progress (pending or accepted).<br>
                Please complete or cancel it before making a new booking.<br>
                Visit <a href="mybookings.php" id="is_booked">My Bookings</a> to view or manage it.
            </p>
        <?php else: ?>
            <h2>Book a Ride</h2>
            <form action="booking.php" method="POST" id="bookingForm">
                <label for="address">Address*</label>
                <input type="text" name="address" required />

                <label for="destination">Destination*</label>
                <input type="text" name="destination" required />

                <label for="pickupdate">Scheduled Pickup*</label>
                <input type="date" name="pickupdate" id="pickupdate" required />

                <label for="time">Time*</label>
                <input type="time" name="time" id="time" required />

                <label for="vehicle">Vehicle*</label>
                <select name="vehicletype" id="vehicletype">
                    <option value="Car 4 Seater" selected>Car 4-Seater</option>
                    <option value="Car 6 Seater">Car 6-Seater</option>
                    <option value="Car 10 Seater">Car 10-Seater</option>
                    <option value="Tricycle">Tricycle</option>
                    <option value="Motorcycle">Motorcycle</option>
                </select>

                <label for="notes">Notes</label>
                <textarea name="notes" rows="4" cols="25"></textarea>

                <label for="price">Price</label>
                <p id="priceDisplay">₱0.00</p>
                <input type="hidden" name="price" id="priceInput" />

                <div class="button-group">
                    <button type="submit" name="book">Book</button>
                    <button type="submit" name="book" id="bookTodayBtn">Book Today</button>
                </div>
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

    vehicletypeSelect.addEventListener('change', updatePrice);
    updatePrice();

    const dateInput = document.getElementById('pickupdate');
    const timeInput = document.getElementById('time');
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;
    dateInput.setAttribute('min', todayStr);

    document.getElementById('bookTodayBtn').addEventListener('click', () => {
        dateInput.value = todayStr;
        if (!timeInput.value) {
            const hh = String(today.getHours()).padStart(2, '0');
            const min = String(today.getMinutes()).padStart(2, '0');
            timeInput.value = `${hh}:${min}`;
        }
    });
</script>
</body>
</html>
