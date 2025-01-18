<?php
session_start();

if (isset($_POST['vehicleId'])) {
    $_SESSION['VehicleID'] = $_POST['vehicleId']; // Set VehicleID in the session
    // Debugging output for AJAX success
    echo json_encode(["status" => "success", "message" => "Vehicle ID set successfully."]);
} else {
    // Debugging output for missing vehicle ID
    echo json_encode(["status" => "error", "message" => "No Vehicle ID provided."]);
}
?>

