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
                        <select name="vehicle" style="text-align: center;">
                            <option value="car" selected>Car</option>
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
            </ul>
            <button type="submit" name="submit" class="button-81" role="button">Book</button>
        </form>
    </div>
</body>
</html>