<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicleID = $_POST['VehicleID'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $licensePlate = $_POST['license_plate'];

    // Update vehicle details
    $updateSql = "UPDATE vehicle SET Make='$make', Model='$model', License_Plate='$licensePlate' WHERE VehicleID='$vehicleID'";
    if ($conn->query($updateSql) === TRUE) {
        echo "Vehicle details updated successfully.";
        header("Location: Dashboard2.php");
    } else {
        echo "Error updating vehicle: " . $conn->error;
    }
}
?>
