<?php
session_start();
include 'connect.php';

// Check if session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}

// Fetch the VehicleID from the query parameter
$vehicleID = isset($_GET['vehicleID']) ? $_GET['vehicleID'] : null;

if ($vehicleID) {
    // Fetch the vehicle details from the database
    $stmt = $conn->prepare("SELECT * FROM vehicle WHERE VehicleID = ?");
    $stmt->bind_param("i", $vehicleID);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();
} else {
    echo "No vehicle ID provided.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $license_plate = $_POST['license_plate'];

    // Update the vehicle details in the database
    $updateStmt = $conn->prepare("UPDATE vehicle SET Make = ?, Model = ?, License_Plate = ? WHERE VehicleID = ?");
    $updateStmt->bind_param("sssi", $make, $model, $license_plate, $vehicleID);
    if ($updateStmt->execute()) {
        if ($_SESSION['usertype'] == 'admin') {
            header("Location: adminvehiclelist.php");
        } else {
            header("Location: Dashboard2.php");
        }
        exit();
    } else {
        echo "Error updating vehicle details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Vehicle</title>
    <link rel="stylesheet" href="CSSOnly/registerform2.css" />
</head>
<body>
<div class="form-container">
    <h2>Edit Vehicle</h2>
    
    <form method="POST" action="">
        <label for="make">Make:</label>
        <input type="text" name="make" value="<?php echo htmlspecialchars($vehicle['Make']); ?>" required>
        
        <label for="model">Model:</label>
        <input type="text" name="model" value="<?php echo htmlspecialchars($vehicle['Model']); ?>" required>
        
        <label for="license_plate">License Plate:</label>
        <input type="text" name="license_plate" value="<?php echo htmlspecialchars($vehicle['License_Plate']); ?>" required>
        
        <input type="submit" value="Update Vehicle">
        <?php
        if ($_SESSION['usertype'] == 'admin') {
            echo '<a href="adminvehiclelist.php" class="styled-button">Back to Dashboard</a>';
    } else {
        echo '<a href="Dashboard2.php" class="styled-button">Back to Dashboard</a>';
    }
        ?>
    </form>
    </div>
</body>
</html>
