<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicleID = $_SESSION['VehicleID'];
    $date = $_POST['date'];
    $mileage = $_POST['mileage'];
    $cost = $_POST['cost'];
    $pricePerLitre = $_POST['pricePerLitre'];
    $fuelType = $_POST['fuelType'];
    $userID = $_SESSION['UserID']; // Get UserID from session

    // Retrieve the last mileage and date for the vehicle
    $lastRecordQuery = "SELECT Mileage, Date FROM refueling WHERE VehicleID = '$vehicleID' ORDER BY Date DESC LIMIT 1";
    $lastRecordResult = $conn->query($lastRecordQuery);
    $lastMileage = null;
    $lastDate = null;

    if ($lastRecordResult && $lastRecordResult->num_rows > 0) {
        $lastRecord = $lastRecordResult->fetch_assoc();
        $lastMileage = $lastRecord['Mileage'];
        $lastDate = $lastRecord['Date'];
    }

    // Check if the new mileage is lower than the last mileage
    if ($lastMileage !== null && $mileage < $lastMileage) {
        // Check if the new date is earlier than the last date
        if ($lastDate !== null && $date > $lastDate) {
            echo "<script>alert('Warning: The new mileage cannot be lower than the previous mileage of $lastMileage unless the date is earlier.');</script>";
        } else {
            // Allow insertion if the date is earlier
            $amount = $cost / $pricePerLitre;
            $formattedAmount = number_format($amount, 2, '.', ''); // Format to 2 decimal places

            // Insert the new refueling record into the database
            $sql = "INSERT INTO refueling (VehicleID, Date, Mileage, Refulieng_Cost, Refueling_amount, Fuel_Type, priceperlitre, UserID) VALUES ('$vehicleID', '$date', '$mileage', '$cost', '$formattedAmount', '$fuelType', '$pricePerLitre', '$userID')";
            if ($conn->query($sql) === TRUE) {
                echo "New refueling record created successfully.";
                header("Location: RefuelingPage.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        // Calculate the amount of litres based on the total cost and price per litre
        $amount = $cost / $pricePerLitre;
        $formattedAmount = number_format($amount, 2, '.', ''); // Format to 2 decimal places

        // Insert the new refueling record into the database
        $sql = "INSERT INTO refueling (VehicleID, Date, Mileage, Refulieng_Cost, Refueling_amount, Fuel_Type, priceperlitre, UserID) VALUES ('$vehicleID', '$date', '$mileage', '$cost', '$formattedAmount', '$fuelType', '$pricePerLitre', '$userID')";
        if ($conn->query($sql) === TRUE) {
            echo "New refueling record created successfully.";
            header("Location: RefuelingPage.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Refueling Data</title>
    <link rel="stylesheet" href="CSSOnly/maintenance.css" />
    <link rel="stylesheet" href="CSSOnly/add_refueling.css" />
</head>
<body>
<div class="form-container">
  <h1>Add Refueling Data</h1>
  <form method="POST" action="add_refueling.php">
  <label for="date">Date:</label>
  <input type="date" id="date" name="date" required>

    <label for="mileage">Mileage:</label>
    <input type="number" name="mileage" required>

    <label for="cost">Total Cost (RM):</label>
    <input type="number" name="cost" required pattern="^\d+(\.\d{1,2})?$">

    <label for="pricePerLitre">Price per Litre (RM):</label>
    <input type="text" name="pricePerLitre" required pattern="^\d+(\.\d{1,2})?$" title="Enter a valid price (e.g., 2.15)">

    <label for="fuelType">Fuel Type:</label>
    <select name="fuelType" required>
        <option value="RON95">RON95</option>
        <option value="RON97">RON97</option>
        <option value="Diesel">Diesel</option>
    </select>

    <div style="display: flex; justify-content: space-between;">
      <input type="submit" name="submit" id="submit" value="Add Refueling Data">
      <input type="reset" name="reset" id="reset" value="CLEAR FORM">
    </div>
  </form>

  <script>
    // Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function () {
    // Get tomorrow's date in YYYY-MM-DD format
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowDate = tomorrow.toISOString().split("T")[0];
    // Set the max attribute for the date input
    document.getElementById("date").setAttribute("max", tomorrowDate);
});
</script>
</div>
</body>
</html>
