<?php
require_once "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $address = trim($_POST['address']);
    $destination = trim($_POST['destination']);
    $vehicle = $_POST['vehicle'];
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
    $price = floatval($_POST['price']);

    if (empty($address) || empty($destination) || empty($vehicle) || empty($price)) {
        echo "All required fields must be filled.";
        exit();
    }

    $query = $db->prepare("INSERT INTO users_bookings (username, address, destination, vehicle, notes, price) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("sssssd", $username, $address, $destination, $vehicle, $notes, $price);

    if ($query->execute()) {
        header("Location: bookings.php");
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
    <meta charset="UTF-8">
    <meta name="viewport" width="device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" type="text/css" href="BookingStyle2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
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
                        <li id="sidelink">
                            <a href="logout.php">Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="bookingbg"></div>
    <div class="bookformcontainer">
        <form action="booking.php" method="POST">
            <ul>
                <li>
                    <div class="column1">
                        <label for="address">Address*</label>
                        <input type="text" name="address" required>
                        <br>
                        <label for="destination">Destination*</label>
                        <input type="text" name="destination" required>
                    </div>
                </li>
                <li>
                    <div class="column2">
                    <label for="vehicle">Vehicle*</label>
                        <select name="vehicle" id="vehicle" style="text-align: center;">
                            <option value="car4" selected>Car 4-Seater</option>
                            <option value="car6">Car 6-Seater</option>
                            <option value="car10">Car 10-Seater</option>
                            <option value="tricycle">Tricycle</option>
                            <option value="motorcycle">Motorcycle</option>
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
                        <input type="hidden" name="price" id="priceInput">
                </li>
            </ul>
            <button type="submit" name="submit" class="button-81" role="button">Book</button>
        </form>
    </div>
    <script>
    const priceMap = {
        car4: 500,
        car6: 700,
        car10: 1000,
        tricycle: 150,
        motorcycle: 100
    };

    const vehicleSelect = document.getElementById('vehicle');
    const priceDisplay = document.getElementById('priceDisplay');
    const priceInput = document.getElementById('priceInput');

    function updatePrice() {
        const selectedVehicle = vehicleSelect.value;
        const price = priceMap[selectedVehicle] || 0;
        priceDisplay.textContent = `₱${price.toFixed(2)}`;
        priceInput.value = price;
    }

    vehicleSelect.addEventListener('change', updatePrice);

    updatePrice();
</script>
</body>
</html>