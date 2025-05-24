<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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
                        <!--echo "<li id="link><a href="admin.php>Admin</a></li>"-->
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
                        <!--echo "<li id="sidelink><a href="admin.php>Admin</a></li>"-->
                        <li id="sidelink">
                            <a href="logout.php">Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <div class="mycontainer">
            <!--Sample
            echo
            "<table>
                <tr>
                    Test Booked By: User; Booking Submitted Date or $variable
                </tr>
                <th>
                    Test Vehicle Type or $variable
                </th>
                <th>
                    Test PickUp or $variable
                </th>
                <th>
                    Test Destination or $variable
                </th>
                <th>
                    PickUp Date/ETA or $variable
                </th>
                <td>
                    <button type="submit" name="submit" class="button-81" role="button">Approve</button>
                    <button type="submit" name="submit" class="button-81" role="button">Reject</button>
                </td>
            </table>"
            -->
            <!--Placeholder-->
            <table>
                <tr>
                    <h1 id="trhead">Booked By: User; Date: mm/dd/yyyy</h1>
                </tr>
                <th>
                    <b>Vehicle Type:</b><p id="mbecho">echo</p>
                </th>
                <th>
                    <b>Pick Up/Address:</b><br> <p id="mbecho">echo</p>
                </th>
                <th>
                    <b>Destination:</b><br> <p id="mbecho">echo</p>
                </th>
                <th>
                    <b>Pick Up Date:</b><br> <p id="mbecho">echo</p>
                </th>
                <th>
                    <b>Pick Up Time:</b><br> <p id="mbecho">echo</p>
                </th>
                <td>
                    <button type="submit" name="approve" class="button-81" role="button">Approve</button>
                    <button type="submit" name="reject" class="button-81" role="button">Reject</button>
                </td>
            </table>
        </div>
    </main>
</body>
</html>