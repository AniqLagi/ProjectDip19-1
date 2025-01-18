<?php
session_start(); // Ensure session is started
include 'connect.php';

// Check for error message
if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']); // Clear the error message after displaying it
}

// Debugging: Check session variables
echo "Session ID: " . session_id() . "<br>";
echo "Username: " . $_SESSION['username'] . "<br>";
echo "Password: " . $_SESSION['password'] . "<br>";

if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo "Session variables are not set.";
    exit();
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];

echo "Debug: Username = $username, Password = $password<br>";

$sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['UserID'];
} else {
    echo "Invalid username or password";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging: Check the entire POST array
    echo "Debug: POST Data = ";
    print_r($_POST); // Debugging line

    // Check if required fields are set
    if (isset($_POST['make'], $_POST['model'], $_POST['plate'], $_POST['password'])) {
        $make = $_POST['make'];
        $model = $_POST['model'];
        $licensePlate = $_POST['plate'];
        $password = $_POST['password']; // Get password entered by user
        
        echo "Debug: Make = $make, Model = $model, License Plate = $licensePlate, Password = $password<br>"; // Debugging line

        // Check if the license plate already exists
        $checkSql = "SELECT * FROM vehicle WHERE License_Plate = '$licensePlate'";
        $checkResult = $conn->query($checkSql);
        
        // Debugging: Check the result of the query
        if ($checkResult === FALSE) {
            echo "Debug: SQL Error = " . $conn->error . "<br>";
        } else {
            echo "Debug: Number of rows found = " . $checkResult->num_rows . "<br>"; // Debugging line
            
            if ($checkResult->num_rows > 0) {
                // Redirect to addvehicle.php with an error message
                $_SESSION['error'] = "The license plate is already taken. Please use a different one.";
                header("Location: addvehicle.php");
                exit();
            } else {
                // Step 1: Insert the vehicle into the `vehicle` table
                $sql = "INSERT INTO vehicle (make, model, License_plate)
                        VALUES ('$make', '$model', '$licensePlate')";
                
                // Debugging: Check the SQL statement
                echo "Debug: SQL Insert = $sql<br>"; // Debugging line
                
                if ($conn->query($sql) === TRUE) {
                    // Step 2: Get the VehicleID of the newly inserted vehicle
                    $vehicleID = $conn->insert_id; // Get the last inserted VehicleID
                    
                    // Insert into the `user_vehicle` bridge table
                    // Inserting into user_vehicle table for a new vehicle (Owner Role)
                    $sql = "INSERT INTO user_vehicle (userID, VehicleID, AccessRole, AccessPassword) 
                    VALUES ('$user_id', '$vehicleID', 'Owner', '$password')";

                    if ($conn->query($sql) === TRUE) {
                    echo "Vehicle registered successfully!";
                    header("Location: vehicleselect.php");
                    exit();
                    } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    } else {
        echo "<script>alert('Make, model, license plate, and password are required.');</script>";
    }
}

$conn->close();
?>
