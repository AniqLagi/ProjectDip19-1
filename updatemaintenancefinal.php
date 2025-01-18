<?php
session_start();

include('connect.php');

$date = $_POST['u_date'];
$mileage = $_POST['u_mileage'];
$ExpenseTypeID = $_POST['u_Expense_Type_ID'];
$cost = $_POST['u_cost'];
$desc = $_POST['u_description'];

$expensesid=$_POST['u_ExpensesID'];

// $sql = "UPDATE expenses SET ExpensesID='".$expensesid."',Date='".$date."' ,Mileage='".$mileage."', Cost='".$cost."',
// Description='".$desc."', Expense_Type_ID='".$ExpenseTypeID."'WHERE ExpensesID ='".$expensesid."'";
$sql = "UPDATE expenses 
        SET Date = '$date', 
            Mileage = '$mileage', 
            Cost = '$cost', 
            Description = '$desc', 
            Expense_Type_ID = '$ExpenseTypeID' 
        WHERE ExpensesID = '$expensesid'";

$result = $conn->query($sql);

if($conn->query($sql) === TRUE) {
    echo "<p style='text-align:center'>Data $bus_id Has Been Updated";
    echo "<p>";
    
    // Check user type and redirect accordingly
    if ($_SESSION['usertype'] == 'admin') {
        header("Location: adminmaintenanceInfo.php");
    } else {
        header("Location: maintenance.php");
    }
} else {
    echo "<p>";
    echo "<p style='text-align:center'>Error: " .$sql . "<br>" . $conn->error;
    echo "<p>";
}

$conn->close();


?>