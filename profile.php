<?php
require_once "config.php";
session_start();

$fname = $_SESSION['fname'] ?? '';
$lname = $_SESSION['lname'] ?? '';
$email = $_SESSION['email'] ?? '';
$phone = $_SESSION['phone'] ?? '';
$username = $_SESSION['username'] ?? '';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $new_fname = trim($_POST['fname']);
    $new_lname = trim($_POST['lname']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']);
    $new_password = trim($_POST['password']);

    if (!empty($new_password)) {
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $query = $db->prepare("UPDATE users SET firstName=?, lastName=?, email=?, phone=?, password=? WHERE userName=?");
        $query->bind_param("ssssss", $new_fname, $new_lname, $new_email, $new_phone, $password_hash, $username);
    } else {
        $query = $db->prepare("UPDATE users SET firstName=?, lastName=?, email=?, phone=? WHERE userName=?");
        $query->bind_param("sssss", $new_fname, $new_lname, $new_email, $new_phone, $username);
    }

    if ($query->execute()) {
        $_SESSION['fname'] = $new_fname;
        $_SESSION['lname'] = $new_lname;
        $_SESSION['email'] = $new_email;
        $_SESSION['phone'] = $new_phone;

        $fname = $new_fname;
        $lname = $new_lname;
        $email = $new_email;
        $phone = $new_phone;

        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile.";
    }
    $query->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="profile.css">
    <script>
        function toggleEditMode() {
            const inputs = document.querySelectorAll('.form-input');
            const isDisabled = inputs[0].disabled;
            inputs.forEach(input => input.disabled = !isDisabled);

            document.getElementById("submitBtn").style.display = isDisabled ? "inline-block" : "none";
            document.getElementById("cancelBtn").style.display = isDisabled ? "inline-block" : "none";
            document.getElementById("editBtn").style.display = isDisabled ? "none" : "inline-block";
        }

        function cancelEdit() {
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => input.disabled = true);

            document.getElementById("submitBtn").style.display = "none";
            document.getElementById("cancelBtn").style.display = "none";
            document.getElementById("editBtn").style.display = "inline-block";
        }
    </script>
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
    <div class="progressbar"></div>
<main>
    <div class="profilecontainer">
        <h1 id="profileheader">Welcome <?php echo htmlspecialchars($username); ?></h1>

        <div class="profilecontainer2">
            <div class="profilepic">
                <img src="#" alt="Profile Picture">
            </div>
            <div class="aboutuser">

            </div>
        </div>

        <h1 id="profileheader">Account Settings</h1>
        <?php
        if (!empty($success)) echo "<p class='success'>$success</p>";
        if (!empty($error)) echo "<p class='error'>$error</p>";
        ?>
        <div class="profilecontainer2">
            <div class="accountsettings">
                <i>Submitting this form will apply changes to your user information</i>
                <form method="POST" action="">
                    <label for="fname">First Name:</label>
                    <input type="text" id="fname" name="fname" class="form-input" value="<?php echo htmlspecialchars($fname); ?>" disabled>
                    <br>

                    <label for="lname">Last Name:</label>
                    <input type="text" id="lname" name="lname" class="form-input" value="<?php echo htmlspecialchars($lname); ?>" disabled>
                    <br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($email); ?>" disabled>
                    <br>

                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" class="form-input" value="<?php echo htmlspecialchars($phone); ?>" disabled>
                    <br>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Leave blank to keep current password" disabled>
                    <br>

                    <input type="submit" id="submitBtn" name="submit" class="button-81" value="Save Changes" style="display: none;">
                    <button type="button" id="editBtn" class="button-81" onclick="toggleEditMode()">Edit</button>
                    <button type="button" id="cancelBtn" class="button-81" onclick="cancelEdit()" style="display: none;">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</main>
</body>
</html>