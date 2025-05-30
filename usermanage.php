<?php
    require_once "config.php";
    session_start();
    
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit;
    }

    $sql = "SELECT id, firstName, lastName, email, phone, username, role FROM users";
    $result = mysqli_query($db, $sql);

    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $delete_sql = "DELETE FROM users WHERE id = ?";
        $query = $db->prepare($delete_sql);
        $query->bind_param('i', $delete_id);
        
        if ($query->execute()) {
            header("Location: usermanage.php");
            exit;
        } else {
            echo "Failed to delete user.";
        }
    }

    mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Management</title>
    <link rel="stylesheet" type="text/css" href="BookingStyle2.css">
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
                    <li id="link"><a href="usermanage.php">Home</a></li>
                    <li id="link"><a href="bookingmanage.php">Booking</a></li>
                    <li id="link"><a href="profile.php">Profile</a></li>
                    <li id="link"><a href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="mycontainer">
            <h1>User List</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['firstName']; ?></td>
                            <td><?php echo $row['lastName']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                                <a href="usermanage.php?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                <!-- Suspend Button (Placeholder for logic) -->
                                <button type="button" class="btn-suspend" onclick="suspendUser(<?php echo $row['id']; ?>)">Suspend</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function suspendUser(userId) {
            if (confirm('Are you sure you want to suspend this user?')) {
                // Placeholder for suspension logic
                alert('User ' + userId + ' has been suspended!');
            }
        }
    </script>
</body>
</html>
