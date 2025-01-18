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

$deleteid = $_REQUEST['deleteID'];

$sql_expense="DELETE FROM user WHERE UserID='".$deleteid."'";
$result_expense=$conn->query($sql_expense);
if($conn->query($sql_expense) === TRUE){
    echo "<p style='text-align:center'>Data $deleteid Had Been Deleted!";
    echo"<p>";
    header("Location: admin.php");
}else{
    echo "<p>";
    echo "<p style='text-align:center'>Error: " .$sql_expense . "<br>" . $conn->error;
    echo"<p>";
}

$conn->close();

?>
