<?php
session_start();
include 'connect.php';

$username = $_SESSION['username'];
$password = $_SESSION['password'];

$sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Invalid username or password";
    exit();
}

$maintainid = $_REQUEST['id'];

$sql_expense="DELETE FROM expenses WHERE expensesID='".$maintainid."'";
$result_expense=$conn->query($sql_expense);
if($conn->query($sql_expense) === TRUE){
    echo "<p style='text-align:center'>Data $maintainid Had Been Deleted!";
    echo"<p>";
    // Check user type and redirect accordingly
    if ($_SESSION['usertype'] == 'admin') {
        header("Location: adminmaintenanceInfo.php");
    } else {
        header("Location: maintenance.php");
    }
}else{
    echo "<p>";
    echo "<p style='text-align:center'>Error: " .$sql_expense . "<br>" . $conn->error;
    echo"<p>";
}

$conn->close();

?>
