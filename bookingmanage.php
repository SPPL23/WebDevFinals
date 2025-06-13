<?php
    require_once "config.php";
    session_start();

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit;
    }

    $sql = "SELECT id, username, address, destination, vehicletype, notes, price, booking_date, driverstatus FROM users_bookings";
    $result = mysqli_query($db, $sql);

    mysqli_close($db);
?>

<!--isset Delete booking-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Booking Management</title>
    <link rel="stylesheet" type="text/css" href="universal.css" />
    <link rel="stylesheet" type="text/css" href="navbar.css" />
    <style>
        .mycontainer {
            margin-top: 9rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1200px;
        }

        h1 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background-color: #53a8b6;
            color: white;
            overflow: hidden;
        }

        thead {
            background-color: #3a7983;
            color: rgb(253, 245, 172);
        }

        th, td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #3a7983;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
            color: black;
        }

        tr:hover {
            background-color: #f1f1f1;
            color: black;
        }

        a {
            color: white;
            text-decoration: none;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 0.5rem; /* Adds spacing between buttons */
            background-color: #53a8b6;
        }

        .button-81 {
            background-color: #1e293b;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .button-81:hover {
            background-color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="dashbg"></div>
    <nav>
        <div class="grid1">
            <h1 class="logo">BookingName</h1>
        </div>
        <div class="grid2">
            <ul>
                <li><a href="usermanage.php">User Management</a></li>
                <li><a href="bookingmanage.php">Booking Management</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="grid3">
            <span class="material-symbols-outlined">menu</span>
            <div class="dropdowncontent">
                <ul>
                    <li><a href="usermanage.php">User Management</a></li>
                    <li><a href="bookingmanage.php">Booking Management</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="mycontainer">
            <h1>Booking List</h1>
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
                        <th>Driver Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['destination']; ?></td>
                            <td><?php echo $row['vehicletype']; ?></td>
                            <td><?php echo $row['notes']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['booking_date']; ?></td>
                            <td><?php echo $row['driverstatus']; ?></td>
                            <td class="actions">
                                <a href="booking_edit.php?username=<?php echo urlencode($row['username']); ?>&booking_date=<?php echo urlencode($row['booking_date']); ?>" class="button-81">Edit</a>
                                <a href="bookingmanage.php" class="button-81" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>