<?php
session_start();
include 'connect.php';

if (isset($_REQUEST['updateID'])) {
    $updateID = $_REQUEST['updateID'];

    // Fetch current vehicle details
    $sql = "SELECT * FROM user WHERE UserID = '$updateID'";
    $result = $conn->query($sql);
    $vehicle = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Form</title>
<link rel="stylesheet" href="CSSOnly/registerform2.css">
<a href="admin.php" class="styled-button">Back to Dashboard</a>
</head>
<body>
<div class="form-container">
  <h1>Register</h1>
  <form id="form1" name="form1" method="post" action="updateuserAdminFinal.php">

    <input type="hidden" name="UserID" value="<?php echo $vehicle['UserID']; ?>">
    <input type="hidden" name="usertype" value="<?php echo $vehicle['usertype']; ?>">

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $vehicle['Name']; ?>" required minlength="3" maxlength="50" placeholder="Enter your Name">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $vehicle['Username']; ?>" required minlength="3" maxlength="20" placeholder="Choose a username">
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $vehicle['Email']; ?>" required>
    
    <label for="phone">Phone Number:</label>
    <input type="tel" id="phone" name="phone" value="<?php echo $vehicle['Phone']; ?>" required>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo $vehicle['Password']; ?>" required minlength="6" maxlength="20" placeholder="Create a password">

    <label for="password">Confirm Password:</label>
    <input type="password" id="repeat_password" name="repeat_password"required minlength="6" maxlength="20" placeholder="Re-enter your password">
    
    <div style="display: flex; justify-content: space-between;">
      <input type="submit" name="submit" id="submit" value="UPDATE">
      <input type="reset" name="reset" id="reset" value="CLEAR FORM">
    </div>
  </form>
</div>
</body>
</html>

