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

if (isset($_REQUEST['ExpensesID'])) {
    $id = $_REQUEST['ExpensesID'];
    
    $stmt = $conn->prepare("SELECT * FROM expenses WHERE ExpensesID = ?");
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
    <title>Add Maintenance Data</title>
    <link rel="stylesheet" href="CSSOnly/maintenance.css" />
    <link rel="stylesheet" href="CSSOnly/add_maintenance2.css" />
    <?php
    if ($_SESSION['usertype'] == 'admin') {
        echo '<a href="adminmaintenanceInfo.php" class="styled-button">Back to Dashboard</a>';
    } else {
        echo '<a href="maintenance.php" class="styled-button">Back to Dashboard</a>';
    }
?>
</head>
<body>
<div class="form-container">
  <h1>Add Maintenance Data</h1>
  <form method="POST" action="updatemaintenancefinal.php">
  <input type="hidden" name="u_ExpensesID" value="<?php echo $row['ExpensesID']; ?>">
    <label for="date">Date:</label>
    <input type='date' name='u_date'  value='<?php echo $row['Date']; ?>'></td>

    <label for="mileage">Mileage:</label>
    <input type='text' name='u_mileage'  value='<?php echo $row['Mileage']; ?>'></td>

    <label for="Expenses_name">Expenses Name:</label>
    <select name="u_Expense_Type_ID" required>
    <option value="">Select an expense type</option>
    <?php foreach ($expenseTypes as $type): ?>
        <option value="<?php echo $type['Expense_Type_ID']; ?>"><?php echo $type['Expenses_Name']; ?></option>
    <?php endforeach; ?>
    </select>

    <label for="expenses_cost">Expenses Cost (RM):</label>
    <input type="text" name="u_cost" required pattern="^\d+(\.\d{1,2})?$" title="Enter a valid price (e.g., 2.15)"  value='<?php echo $row['Cost']; ?>'>

    <label for="expenses_description">Expenses Description:</label>
    <input type="text" name="u_description" required  value='<?php echo $row['Description']; ?>'>

    <div style="display: flex; justify-content: space-between;">
      <input type="submit" name="submit" id="submit" value="Update Maintenance Data">
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


