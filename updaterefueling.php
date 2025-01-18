<?php 
session_start();    
include 'connect.php';

$username = $_SESSION['username'];
$password = $_SESSION['password'];

// Fetch expense types for the dropdown
$expenseTypesQuery = "SELECT Expense_Type_ID, Expenses_Name FROM expenses_type";
$expenseTypesResult = $conn->query($expenseTypesQuery);
$expenseTypes = [];
if ($expenseTypesResult && $expenseTypesResult->num_rows > 0) {
    while ($row = $expenseTypesResult->fetch_assoc()) {
        $expenseTypes[] = $row;
    }
}

if (isset($_REQUEST['RefuelingID'])) {
    $id = $_REQUEST['RefuelingID'];
    
    $stmt = $conn->prepare("SELECT * FROM refueling WHERE RefuelingID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result_expensesID = $stmt->get_result();

    if ($result_expensesID->num_rows > 0) {
        $row = $result_expensesID->fetch_assoc();
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Refueling Data</title>
    <link rel="stylesheet" href="CSSOnly/maintenance.css" />
    <link rel="stylesheet" href="CSSOnly/add_maintenance2.css" />
    <?php
    if ($_SESSION['usertype'] == 'admin') {
        echo '<a href="adminfuelInfo.php" class="styled-button">Back to Dashboard</a>';
    } else {
        echo '<a href="RefuelingPage.php" class="styled-button">Back to Dashboard</a>';
    }
?>
</head>
<body>
<div class="form-container">
  <h1>Edit Refueling Data</h1>
  <form method="POST" action="updaterefuelingfinal.php"> 
  <input type="hidden" name="u_RefuelingID" value="<?php echo $row['RefuelingID']; ?>">

    <label for="date">Date:</label>
    <input type='date' name='u_date'  value='<?php echo $row['Date']; ?>'></td>

    <label for="mileage">Mileage:</label>
    <input type='text' name='u_mileage'  value='<?php echo $row['Mileage']; ?>'></td>

    <label for="cost">Refueling Cost (RM):</label>
    <input type="number" name="u_cost" required value='<?php echo $row['Refulieng_Cost']; ?>'>

    <label for="pricePerLitre">Price per Litre (RM):</label>
    <input type="text" name="u_pricePerLitre" required pattern="^\d+(\.\d{1,2})?$" title="Enter a valid price (e.g., 2.15)"  value='<?php echo $row['priceperlitre']; ?>'>

    <label for="fuelType">Fuel Type:</label>
    <select name="u_fuelType" required>
        <option value="RON95">RON95</option>
        <option value="RON97">RON97</option>
        <option value="Diesel">Diesel</option>
    </select>

    <div style="display: flex; justify-content: space-between;">
      <input type="submit" name="submit" id="submit" value="Update Refuel Data">
      <input type="reset" name="reset" id="reset" value="CLEAR FORM">
    </div>
  </form>
</div>
</body>
</html>

        <?php
    } else {
        echo "0 results";
    }
    $stmt->close();
} else {
    echo "ExpensesID is missing.";
}

$conn->close();
?>


