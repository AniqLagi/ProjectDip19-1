<?php
// Initialise the session
session_start();
include 'connect.php';

// Check for error message
if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']); // Clear the error message after displaying it
}

$usernameee = $_SESSION['username'];
$passworddd = $_SESSION['password'];

echo "Debug: Username = $usernameee, Password = $passworddd<br>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Registration Form</title>
  <link rel="stylesheet" href="CSSOnly/registerform2.css">
</head>
<body>
    <div class="form-container">
      <h1>Register Existing Vehicle</h1>
      <form id="form1" name="form1" method="post" action="vehicleexistregister.php">
    
        <label for="plate">License Plate:</label>
        <input type="text" name="plate" id="plate" required placeholder="Enter the car license plate">

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required minlength="6" maxlength="20" placeholder="Create a password">
    
        <div style="display: flex; justify-content: space-between;">
            <input type="submit" name="submit" id="submit" value="ENTER">
            <input type="reset" name="reset" id="reset" value="CLEAR FORM">
        </div>
        <div>
        <a href="vehicleselect.php" class="styled-button">Back</a>
        </div>
      </form>
    </div>

    <script>
    function goBack() {
        window.history.back();
    }
    </script>

</body>
</html>
