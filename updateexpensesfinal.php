<?php
session_start();

include('connect.php');

$name = $_POST['name'];
$expensesid=$_POST['u_expense'];

// $sql = "UPDATE expenses SET ExpensesID='".$expensesid."',Date='".$date."' ,Mileage='".$mileage."', Cost='".$cost."',
// Description='".$desc."', Expense_Type_ID='".$ExpenseTypeID."'WHERE ExpensesID ='".$expensesid."'";
$sql = "UPDATE expenses_type 
        SET Expense_Type_ID = '$expensesid', 
            Expenses_Name = '$name'
        WHERE Expense_Type_ID = '$expensesid'";

$result = $conn->query($sql);

if($conn->query($sql) === TRUE) {
    echo "<p style='text-align:center'>Data $bus_id Has Been Updated";
    echo "<p>";
    
    // Check user type and redirect accordingly
    if ($_SESSION['usertype'] == 'admin') {
        header("Location: adminaddexpense.php");
    } else {
        header("Location: adminaddexpense.php");
    }
} else {
    echo "<p>";
    echo "<p style='text-align:center'>Error: " .$sql . "<br>" . $conn->error;
    echo "<p>";
}

$conn->close();


?>