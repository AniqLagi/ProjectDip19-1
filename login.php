<?php
session_start();

// Reset session variables if logging in
if (isset($_SESSION['username'])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
}

// Set new session variables after login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password']; 
}

include 'connect.php';

$sql = "SELECT * FROM user WHERE username='".$_SESSION['username']."'";
//echo "Debug: SQL Query = " . $sql . "<br>"; // Debugging output for SQL query
$result = $conn->query($sql);

if (!$result) {
    echo "Debug: Query Error = " . $conn->error . "<br>"; // Check for query errors
}

$row = mysqli_fetch_array($result);

// Debugging output
if ($row) {
    echo "Debug: Retrieved UserID: " . $row['UserID'] . "<br>";
    echo "Debug: Retrieved Password: " . $row['Password'] . "<br>";
    echo "Debug: Entered Password: " . $_SESSION['password'] . "<br>"; // Print entered password
    echo "Debug: Entered Password: " . $_SESSION['password'] . "<br>"; // Print entered password
} else {
    echo "<script>alert('There is no account under the username: " . $_SESSION['username'] . "');</script>";
    //echo "Debug: No user found with the username: " . $_SESSION['username'] . "<br>";
}

if ($row && strtolower($_SESSION['password']) === strtolower($row['Password'])) 
{
    $_SESSION['username'] = $row['Username'];
    $_SESSION['UserID'] = $row['UserID']; 
    $_SESSION['usertype'] = $row['usertype'];
    
    if ($row['usertype'] == 'user') {
        header("Location: vehicleselect.php");
        exit();
    } elseif ($row['usertype'] == 'admin') {
        header("Location: admin.php");
        exit();
    } 
} 
else 
{
    echo "<meta http-equiv=\"refresh\" content=\"3;URL=loginform.php\">";
}
?>
