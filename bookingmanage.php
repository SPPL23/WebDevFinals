<?php
    require_once "config.php";
    session_start();

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit;
    }

    $sql = "SELECT id, username, address, destination, vehicletype, notes, price, booking_date FROM users_bookings";
    $result = mysqli_query($db, $sql);

    mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="bookingmanage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu">
</head>
<body>
    <nav>
        <div class="navcontainer">
            <div class="logocontainer">
                <ul>
                    <li id="Logo">BookingName</li>
                </ul>
            </div>
            <div class="linkcontainer">
                <ul id="links">
                    <li id="link"><a href="usermanage.php">Users</a></li>
                    <li id="link"><a href="bookingmanage.php">Booking</a></li>
                    <li id="link"><a href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="mycontainer">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Address</th>
                        <th>Destination</th>
                        <th>Vehicle</th>
                        <th>Notes</th>
                        <th>Price</th>
                        <th>Booking Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) {?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['destination']; ?></td>
                        <td><?php echo $row['vehicletype']; ?></td>
                        <td><?php echo $row['notes']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['booking_date']; ?></td>
                    </tr>
                    <?php }?>

                </tbody>
            </table>
        </div>
    </main>
</body>
</html>