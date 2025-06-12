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

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table th, .table td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #3a7983;
            color: white;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        .button-81 {
            margin: 0.5rem;
            color: #1e293b;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="dashbg"></div>
    <nav>
        <div class="grid1">
            <h1 style="color: white;">Admin Panel</h1>
        </div>
        <div class="grid2">
            <ul>
                <li><a href="usermanage.php">User Management</a></li>
                <li><a href="bookingmanage.php">Booking Management</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="grid3">
            <span>Welcome, Admin</span>
            <div class="dropdowncontent">
                <ul>
                    <li><a href="usermanage.php">User Management</a></li>
                    <li><a href="bookingmanage.php">Booking Management</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="mycontainer">
            <h1>User List</h1>
            <table class="table">
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
                            <td class="actions">
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="button-81">Edit</a>
                                <a href="usermanage.php?delete_id=<?php echo $row['id']; ?>" class="button-81" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                <button type="button" class="button-81" onclick="suspendUser(<?php echo $row['id']; ?>)">Suspend</button>
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
                alert('User ' + userId + ' has been suspended!');
            }
        }
    </script>
</body>
</html>
