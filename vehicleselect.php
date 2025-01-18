<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserID']) || !isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: loginform.php"); // Redirect to login if not logged in
    exit();
}

include 'connect.php';

$userid = $_SESSION['UserID']; // Get the user ID from the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select or Add Vehicle</title>
    <link rel="stylesheet" href="CSSOnly/vehicleselect.css">
    <script>
        // Function to set the vehicle ID
        function setVehicleId(event) {
    event.preventDefault();  // Prevent the form from submitting before AJAX

    var vehicleSelect = event.target.querySelector('select');
    var vehicleId = vehicleSelect.options[vehicleSelect.selectedIndex].value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "set_vehicle_id.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
                // If successful, submit the form after a delay
                setTimeout(function () {
                    event.target.submit(); // Submit the form that triggered the event
                }, 500);
            } else {
                alert(response.message); // Show an error message if something goes wrong
            }
        }
    };
    
    xhr.send("vehicleId=" + vehicleId);
}



    </script>
</head>

<body>
        <div class="logout-container">
        <a href="logout.php" class="styled-button">Logout</a>
        </div>
        <div class="content-wrapper">
        <div class="container">
        <h1>Manage Your Vehicles</h1>

        <!-- Form to select owner vehicles -->
        <form id="owner-vehicle-form" action="Dashboard2.php" method="post" onsubmit="setVehicleId(event)">
        <h2>Owner Vehicles</h2>
        <label for="owner-vehicle">Select Owner Vehicle:</label>
        <select name="vehicle" id="owner-vehicle">
                <?php
                // Query to retrieve vehicles where the user is the owner
                $sql_owner = "SELECT vehicle.VehicleID, vehicle.License_Plate, vehicle.Make, vehicle.Model
                              FROM vehicle
                              INNER JOIN user_vehicle ON vehicle.VehicleID = user_vehicle.VehicleID
                              WHERE user_vehicle.UserID = '$userid' AND user_vehicle.AccessRole = 'Owner'";

                $ownerVehicles = $conn->query($sql_owner);

                if (!$ownerVehicles) {
                    die("Query failed: " . $conn->error);
                }

                if ($ownerVehicles->num_rows > 0) {
                    while ($row = $ownerVehicles->fetch_assoc()) {
                        echo "<option value='" . $row['VehicleID'] . "'>" . $row['License_Plate'] . " (" . $row['Make'] . " " . $row['Model'] . ")</option>";
                    }
                } else {
                    echo "<option>No vehicles found. Please add a new one.</option>";
                }
                ?>
             </select>
            <button type="submit" class="button">Select Vehicle</button>
            <button type="button" class="button" onclick="window.location.href='addvehicle.php';">Add New Vehicle</button>

            </form>

        <!-- Form to select authorized vehicles -->
            <form id="authorized-vehicle-form" action="Dashboard2.php" method="post" onsubmit="setVehicleId(event)">
            <h2>Authorized Vehicles</h2>
            <label for="authorized-vehicle">Select Authorized Vehicle:</label>
            <select name="vehicle" id="authorized-vehicle">
                <?php
                // Query to retrieve vehicles where the user is authorized
                $sql_authorized = "SELECT vehicle.VehicleID, vehicle.License_Plate, vehicle.Make, vehicle.Model
                                   FROM vehicle
                                   INNER JOIN user_vehicle ON vehicle.VehicleID = user_vehicle.VehicleID
                                   WHERE user_vehicle.UserID = '$userid' AND user_vehicle.AccessRole = 'Authorized'";

                $authorizedVehicles = $conn->query($sql_authorized);

                if (!$authorizedVehicles) {
                    die("Query failed: " . $conn->error);
                }

                if ($authorizedVehicles->num_rows > 0) {
                    while ($row = $authorizedVehicles->fetch_assoc()) {
                        echo "<option value='" . $row['VehicleID'] . "'>" . $row['License_Plate'] . " (" . $row['Make'] . " " . $row['Model'] . ")</option>";
                    }
                } else {
                    echo "<option>No vehicles found. Please add a new one.</option>";
                }
                ?>
            </select>
            <button type="submit" class="button">Select Vehicle</button>
            </form>

        <!-- Link to add a new vehicle -->
        <button type="button" class="button" onclick="window.location.href='addexistvehicle.php';">Add New Vehicle</button>
    </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>
