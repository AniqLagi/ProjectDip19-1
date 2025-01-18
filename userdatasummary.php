<?php
session_start();

include 'connect.php';

// Check if session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}

$usernameee = $_SESSION['username'];
$passworddd = $_SESSION['password'];

// Fetch user data from the database
$UserID = $_SESSION['UserID']; // Assuming userID is stored in the session
$query = "SELECT * FROM user WHERE UserID = '$UserID'"; // Adjust the query based on your table structure
$result = mysqli_query($conn, $query);

if ($result) {
    $userData = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user data: " . mysqli_error($conn);
}

// Fetch Vehicle data from the database
$VehicleID = $_SESSION['VehicleID']; // Assuming userID is stored in the session
$query2 = "SELECT * FROM vehicle WHERE VehicleID = '$VehicleID'"; // Adjust the query based on your table structure
$result2 = mysqli_query($conn, $query2);

if ($result2) {
    $vehicleData = mysqli_fetch_assoc($result2);
} else {
    echo "Error fetching Vehicle data: " . mysqli_error($conn);
}

// Correct $query3 execution
$query3 = "SELECT AccessPassword FROM user_vehicle WHERE UserID = '$UserID' AND VehicleID = '$VehicleID'"; // Correct the typo in $VehicleID
$result3 = mysqli_query($conn, $query3); // Correctly execute $query3

if ($result3) {
    $uservehicleData = mysqli_fetch_assoc($result3);
} else {
    echo "Error fetching user vehicle data: " . mysqli_error($conn);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="CSSOnly/UserdataSummary.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- HEADER -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2><?php
        if ($_SESSION['usertype'] == 'admin') {
            echo '<a href="adminvehiclelist.php" class="styled-button">Back to Dashboard</a>';
        } else {
            echo '<a href="Dashboard2.php" class="styled-button">Back to Dashboard</a>';
        }
        ?>
                </h2>
            </div>
            <div class="user--info"></div>
            <!-- <a href="updatevehicle.php?vehicleID=<?php echo $vehicle['VehicleID']; ?>" class="styled-button">Edit Car Details</a>
            <a href="userdatasummary.php?vehicleID=<?php echo $vehicle['VehicleID']; ?>" class="styled-button">View Report Summary</a> -->
            
            <!-- <img src="assets\dProfileImage.jpg" alt="#"> -->
        </div>
    <!-- CARD CONTAINER -->
    <div class="form-container">
    <h1>User Information</h1>
    <div class="profile-section">
        <div class="info-box">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($userData['Name']); ?></p>
        </div>
        <div class="info-box">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['Username']); ?></p>
        </div>
        <div class="info-box">
            <p><strong>User Type:</strong> <?php echo htmlspecialchars($userData['usertype']); ?></p>
        </div>
        <div class="info-box">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['Email']); ?></p>
        </div>
        <div class="info-box">
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($userData['Phone']); ?></p>
        </div>
    </div>
    <div style="display: flex; justify-content: space-between;"></div>

    <h1>Vehicle Information</h1>
    <div class="profile-section">
        <div class="info-box">
            <p><strong>Vehicle Brand:</strong> <?php echo htmlspecialchars($vehicleData['Make']); ?></p>
        </div>
        <div class="info-box">
            <p><strong>Vehicle Model:</strong> <?php echo htmlspecialchars($vehicleData['Model']); ?></p>
        </div>
        <div class="info-box">
            <p><strong>License Plate:</strong> <?php echo htmlspecialchars($vehicleData['License_Plate']); ?></p>
        </div>
        <div class="info-box">
            <p><strong>Vehicle Password:</strong> <?php echo htmlspecialchars($uservehicleData['AccessPassword']); ?></p>
        </div>
    </div>
</div>


</body>
</html>