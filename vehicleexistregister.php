<?php
// Start the session
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['UserID']) || !isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: loginform.php");
    exit();
}

// Get user data from session
$userid = $_SESSION['UserID'];
$username = $_SESSION['username']; // Corrected session variable name
$password = $_SESSION['password']; // Corrected session variable name

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $license_plate = $_POST['plate'];
    $input_password = $_POST['password']; // Password entered by the user

    // Step 1: Verify if the vehicle with the given License Plate exists
    $sql = "SELECT VehicleID FROM vehicle WHERE License_Plate = '$license_plate'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Vehicle found, get the VehicleID
        $vehicle = $result->fetch_assoc();
        $vehicle_id = $vehicle['VehicleID'];

        // Step 2: Get the correct AccessPassword for the vehicle owner
        $ownerSql = "SELECT AccessPassword FROM user_vehicle WHERE VehicleID = '$vehicle_id' AND AccessRole = 'Owner'";
        $ownerResult = $conn->query($ownerSql);

        if ($ownerResult && $ownerResult->num_rows > 0) {
            // Owner password found, verify it
            $ownerData = $ownerResult->fetch_assoc();
            $correct_password = $ownerData['AccessPassword'];

            if ($input_password === $correct_password) {
                // Step 3: Check if the user is already authorized for this vehicle
                $checkSql = "SELECT * FROM user_vehicle WHERE UserID = '$userid' AND VehicleID = '$vehicle_id'";
                $checkResult = $conn->query($checkSql);

                if ($checkResult && $checkResult->num_rows > 0) {
                    $_SESSION['error'] = "You are already authorized for this vehicle.";
                    header("Location: addexistvehicle.php");
                    exit();
                } else {
                    // Insert the user into the user_vehicle table with AccessRole 'Authorized'
                    $accessRole = 'Authorized'; // The user will be marked as an "Authorized" user
                    $insertSql = "INSERT INTO user_vehicle (UserID, VehicleID, AccessRole, AccessPassword) 
                                  VALUES ('$userid', '$vehicle_id', '$accessRole', '$input_password')";

                    if ($conn->query($insertSql) === TRUE) {
                        $_SESSION['success'] = "You have been successfully authorized to access this vehicle.";
                        header("Location: vehicleselect.php");
                        exit();
                    } else {
                        $_SESSION['error'] = "Error: " . $conn->error;
                        header("Location: addexistvehicle.php");
                        exit();
                    }
                }
            } else {
                // Incorrect password
                $_SESSION['error'] = "Incorrect password for this vehicle.";
                header("Location: addexistvehicle.php");
                exit();
            }
        } else {
            // No owner record found (this should not happen)
            $_SESSION['error'] = "This vehicle is not properly registered.";
            header("Location: addexistvehicle.php");
            exit();
        }
    } else {
        // Vehicle not found
        $_SESSION['error'] = "Vehicle with this License Plate does not exist.";
        header("Location: addexistvehicle.php");
        exit();
    }
}


$conn->close();
?>
