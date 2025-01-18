<?php
session_start();
include 'connect.php';

$username = $_SESSION['username'];
$password = $_SESSION['password'];

$maintainid = $_REQUEST['id'];

$sql_expense="DELETE FROM expenses_type WHERE Expense_Type_ID='".$maintainid."'";
$result_expense=$conn->query($sql_expense);
if($conn->query($sql_expense) === TRUE){
    echo "<p style='text-align:center'>Data $maintainid Had Been Deleted!";
    echo"<p>";
    // Check user type and redirect accordingly
    if ($_SESSION['usertype'] == 'admin') {
        header("Location: adminaddexpense.php");
    } else {
        header("Location: loginform.php");
    }
}else{
    echo "<p>";
    echo "<p style='text-align:center'>Error: " .$sql_expense . "<br>" . $conn->error;
    echo"<p>";
}

$conn->close();

?>