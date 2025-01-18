<?php
//Initialise the session
session_start();
if (isset($_SESSION['UserID']))
{
	//Destroy the whole session
	$_SESSION = array();
	session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Form</title>
<link rel="stylesheet" href="CSSOnly/registerform2.css">
</head>
<body>
<a href="loginform.php" class="styled-button" value="LOGIN">Return to Login</a>
<div class="form-container">
  <h1>Register</h1>
  <form id="form1" name="form1" method="post" action="register.php">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required minlength="3" maxlength="50" placeholder="Enter your Name">

    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required minlength="3" maxlength="20" placeholder="Choose a username">
    
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required placeholder="Enter your email address">
    
    <label for="phone">Phone Number:</label>
    <input type="tel" name="phone" id="phone" placeholder="Enter your phone number">
    
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required minlength="6" maxlength="20" placeholder="Create a password">

    <label for="password">Confirm Password:</label>
    <input type="password" name="repeat_password"  required minlength="6" maxlength="20" placeholder="Re-enter your password">
    
    <div style="display: flex; justify-content: space-between;">
      <input type="submit" name="submit" id="submit" value="REGISTER">
      <input type="reset" name="reset" id="reset" value="CLEAR FORM">
    </div>
  </form>
</div>
</body>
</html>
