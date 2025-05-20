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
    <div class="bookformcontainer">
        <form action="booking.php" method="POST">
            <h1>Book a Ride</h1>
            <p>Vehicle Type</p>
            <input type="radio" name="vehicle" value="Car" checked required>
            <label for="Car">Car</label>

            <input type="radio" name="vehicle" value="Tricycle">
            <label for="Tricycle">Tricycle</label>

            <input type="radio" name="vehicle" value="Motorcycle">
            <label for="Motorcycle">Motorcycle</label>

            <label for="pickuptime">Availability</label>
            <input type="date" name="pickuptime" required>

            <label for="location">Street</label>
            <input type="text" name="street" required>

            <label for="location">Subdivision</label>
            <input type="text" name="subdivision" required>
            
            <label for="location">City</label>
            <input type="text" name="city" required>

            <label for="location">Province</label>
            <input type="text" name="province" required>

            <label for="destination">Destination</label>
            <input type="text" name="destination" required>

            <label for="notes">Notes</label>
            <input type="text" name="notes">

            </select>
            <button type="submit" name="submit" class="button-81">Book a ride</button>
        </form>
    </div>
</body>
</html>