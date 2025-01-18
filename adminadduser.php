<?php
session_start();

include 'connect.php';

// Check if session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}

$usernameee = $_SESSION['username'];
$passworddd = $_SESSION['password'];

// Define $vehicleID (assuming it's stored in the session or needs to be fetched)
$vehicleID = isset($_SESSION['vehicleID']) ? $_SESSION['vehicleID'] : null; // Adjust this line as necessary

// Handle form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'admin'; // Assuming 'admin' as the role

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $query = "INSERT INTO user (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "New admin user added successfully!";
    } else {
        echo "Error adding new admin user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="CSSOnly/adduseradmin2.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
        <li class="">
                <a href="admin.php">
                    <i class="fas fa-home"></i>
                    <span>List of User</span>
                </a>
            </li>

            <li class="">
                <a href="adminvehiclelist.php">
                    <i class="fas fas fa-car"></i>
                    <span>List of Vehicle</span>
                </a>
            </li>

            <li class="">
                <a href="adminfuelInfo.php">
                    <i class="fas fa-gas-pump"></i>
                    <span>Refueling Data</span>
                </a>
            </li>

            <li class="">
                <a href="adminmaintenanceInfo.php">
                    <i class="fas fa-wrench"></i>
                    <span>Maintenance Data</span>
                </a>
            </li>

            <li class="active">
                <a href="adminadduser.php">
                    <i class="fas fa-user"></i>
                    <span>Add New User</span>
                </a>
            </li>

            <li class="">
                <a href="adminaddexpense.php">
                    <i class="fas fa-money-bill-alt"></i>
                    <span>New Expenses</span>
                </a>
            </li>

            <li class="">
                <a href="adminChart.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Chart</span>
                </a>
            </li>

            
            <li class="logout">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

            
        </ul>
    </div>

    <!-- HEADER -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Dashboard / 
                <?php  
                    echo htmlspecialchars($usernameee); // Sanitize output
                ?>
                </h2>
            </div>
            <div class="user--info"></div>
            <img src="assets/dProfileImage.jpg" alt="#">
        </div>

        <!-- CARD CONTAINER -->
        <div class="card--container">
            <h2 class="main--title"></h2>
            <div class="form-container">
            <h1>Register</h1>
  <form id="form1" name="form1" method="post" action="Adminregister.php">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required minlength="3" maxlength="50" placeholder="Enter your Name">

    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required minlength="3" maxlength="20" placeholder="Choose a username">
    
    <label for="usertype">User  Type:</label>
        <select name="usertype" id="usertype" required>
            <option value="">Select User Type</option>
            <option value="admin">admin</option>
            <option value="user">user </option>
        </select>

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
      <input type="reset" name="reset" id="reset" value="CLEAR FORM" class="clear-form">  </form>
                    </div>
        </div>
    </div>
</body>
</html>