<?php
session_start();

include('connect.php');

$date = $_POST['u_date'];
$mileage = $_POST['u_mileage'];
$RefuelCost = $_POST['u_cost'];
$price = $_POST['u_pricePerLitre'];
$fueltype = $_POST['u_fuelType'];

$amount = $RefuelCost / $price;

$refuelid=$_POST['u_RefuelingID'];

// $sql = "UPDATE expenses SET ExpensesID='".$expensesid."',Date='".$date."' ,Mileage='".$mileage."', Cost='".$cost."',
// Description='".$desc."', Expense_Type_ID='".$ExpenseTypeID."'WHERE ExpensesID ='".$expensesid."'";
$sql = "UPDATE refueling 
        SET Date = '$date', 
            Mileage = '$mileage', 
            Refulieng_Cost = '$RefuelCost', 
            priceperlitre = '$price',
            Refueling_amount = '$amount', 
            Fuel_Type = '$fueltype' 
        WHERE RefuelingID = '$refuelid'";

$result = $conn->query($sql);

if($conn->query($sql) === TRUE) {
    echo "<p style='text-align:center'>Data $bus_id Has Been Updated";
    echo "<p>";
    
    // Check user type and redirect accordingly
    if ($_SESSION['usertype'] == 'admin') {
        header("Location: adminfuelInfo.php");
    } else {
        header("Location: refuelingpage.php");
    }
} else {
    echo "<p>";
    echo "<p style='text-align:center'>Error: " .$sql . "<br>" . $conn->error;
    echo "<p>";
}

$conn->close();


?>