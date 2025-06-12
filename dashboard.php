<?php 
    require_once "config.php";
    session_start();

    if (!isset($_SESSION["username"])) {
        header("location: signin.php");
        exit;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .dashcontainer{
        margin-top: 0rem;
    }

    .homeimg{
        background-image: url(https://i.ytimg.com/vi/E7OLBAfSLp0/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLCTpfn3iEJvLFr6IQ9NjmRz8jpx5g);
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100%;
        padding: 30rem;
        overflow: hidden;
        align-items: center;
        justify-content: center;
    }

    .content{
        position: absolute;
        margin-top: 25rem;
        inset: 0;
        text-align: center;
    }

    .content h1, h2{
        color: white;
        text-shadow: white 0 0 5px;
    }

    .services{
        display: flex;
        flex-direction: column;
        margin-top: -8rem;
        color: white;
        align-items: center;
    }

    .how{
        display: flex;
        flex-direction: row;
    }

    .steps {
        display: flex;
        flex-direction: row;
        margin: 2rem;
        justify-content: center;
        align-items: center;
        gap: 2rem; /* Added spacing between steps */
        transition: transform 0.3s ease-in-out;
    }

    .step {
        text-align: center;
        background-color: #53a8b6;
        color: white;
        padding: 1.5rem;
        border-radius: 15px; /* Rounded corners for a modern look */
        width: 200px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Added shadow for depth */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .step:hover {
        transform: translateY(-10px); /* Lift effect on hover */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Enhanced shadow on hover */
    }

    .step h3 {
        background-color: white;
        color: #53a8b6;
        width: 50px;
        height: 50px;
        line-height: 50px;
        border-radius: 50%;
        margin: 0 auto 1rem;
        font-size: 1.5rem; /* Increased font size for better visibility */
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Added shadow for the number */
    }

    .step p {
        font-size: 1rem;
        margin: 0;
        font-weight: bold;
    }

    .subservices{
        display: flex;
        flex-direction: column;
    }

    .vehiclecontainer{
        display: flex;
        flex-direction: row;
    }

    .vehiclecontainer h1{
        position: relative;
        top: 10rem;
        right: 5rem;
        animation: Appear linear;
        animation-timeline: view(100% 0%);
    }

    .car{
        background-image: url("car.png");
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
        width: 500px;
        height: 500px;
        animation: Show;
        animation-timeline: view(40% auto);
    }

    .tricycle{
        background-image: url("tricycle.png");
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
        width: 500px;
        height: 500px;
        animation: Show;
        animation-timeline: view(40% auto);
    }

    .motorcycle{
        background-image: url("motor.png");
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
        width: 500px;
        height: 500px;
        animation: Show;
        animation-timeline: view(40% auto);
    }

    h1, h2, h3, h4, h5{
        color: white;
    }

    @keyframes Show {
        from{
            opacity: 0;
            transform: translateX(-50%);
        }
        to{
            opacity 1;
            transform: translateX(0%);
        }
    }

    @keyframes Appear {
        from{
            opacity: 0;
        }
        to{
            opacity: 1;
        }
    }

    #stepbooklink{
        padding: 0;
    }
    </style>
    <link rel="stylesheet" type="text/css" href="universal.css" />
    <link rel="stylesheet" type="text/css" href="navbar.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

</head>
<body>
    <div class="dashbg"></div>
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
        <div class="dashcontainer">
            <div class="homeimg"></div>
                <div class="content">
                    <h1>
                        BookingName
                    </h1>
                    <h2>
                        Book, Choose, Pickup, Drop
                    </h2>
                    <h1>
                        Book A Ride Anywhere
                    </h1>
                </div>
                <div class="services">
                    <h1>Available Pickup Services</h1>
                    <div class="subservices">
                        <div class="vehiclecontainer">
                            <h1>4 Wheeler</h1>
                            <div class="car"></div>
                        </div>
                        <div class="vehiclecontainer">
                            <h1>Tricycle</h1>
                            <div class="tricycle"></div>
                        </div>
                        <div class="vehiclecontainer">
                            <h1>Motorcycle</h1>
                            <div class="motorcycle"></div>
                        </div>
                    </div>
                </div>
                <h1 id="black" style="margin-top: 10rem;">How to book a ride</h1>
                <div class="how">
                    <div class="steps">
                        <div class="step">
                            <h3>1</h3>
                            <a href="booking.php" id="stepbooklink">Go to Booking</a>
                        </div>
                        <div class="step">
                            <h3>2</h3>
                            <p>Fill up the form</p>
                        </div>
                        <div class="step">
                            <h3>3</h3>
                            <p>Wait for approval</p>
                        </div>
                        <div class="step">
                            <h3>4</h3>
                            <p>Wait for pickup</p>
                        </div>
                    </div>
                </div>
            </div>
    </main>
</body>
</html>