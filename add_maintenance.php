<?php
session_start();
include 'connect.php';

// Fetch expense types for dropdown
$expenseTypesQuery = "SELECT Expense_Type_ID, Expenses_Name FROM expenses_type";
$expenseTypesResult = $conn->query($expenseTypesQuery);
$expenseTypes = [];
if ($expenseTypesResult && $expenseTypesResult->num_rows > 0) {
    while ($row = $expenseTypesResult->fetch_assoc()) {
        $expenseTypes[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicleID = $_SESSION['VehicleID'];
    $userID = $_SESSION['UserID'];
    $date = $_POST['date'];
    $mileage = $_POST['mileage'];
    $expenses_cost = $_POST['expenses_cost'];
    $expenses_description = $_POST['expenses_description'];
    $expensesTypeID = $_POST['Expense_Type_ID']; // Single selected expense type

    // Check if an expense type was selected
    if (!empty($expensesTypeID)) {
        // Insert a new record for the selected expense type
        $sql = "INSERT INTO expenses (VehicleID, UserID, Date, Mileage, Cost, Description, Expense_Type_ID) 
                VALUES ('$vehicleID', '$userID', '$date', '$mileage', '$expenses_cost', '$expenses_description', '$expensesTypeID')";
        
        if ($conn->query($sql) === TRUE) {
            error_log("Record inserted successfully for Expense_Type_ID: $expensesTypeID");
            echo "New maintenance record created successfully.";
            header("Location: Maintenance.php");
            exit; // Ensure no further code is executed after redirection
        } else {
            error_log("SQL Error: " . $conn->error); // Log SQL error if the query fails
        }
    } else {
        echo "No expense type selected.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Maintenance Data</title>
    <link rel="stylesheet" href="CSSOnly/maintenance.css" />
    <link rel="stylesheet" href="CSSOnly/add_maintenance.css" />
</head>
<body>
<div class="form-container">
  <h1>Add Maintenance Data</h1>
  <form method="POST" action="add_maintenance.php">
  <label for="date">Date:</label>
<input type="date" id="date" name="date" required>
    <label for="mileage">Mileage:</label>
    <input type="number" name="mileage" required>

    <label for="Expenses_name">Expenses Name:</label>
<select name="Expense_Type_ID" required>
    <option value="">Select an expense type</option>
    <?php foreach ($expenseTypes as $type): ?>
        <option value="<?php echo $type['Expense_Type_ID']; ?>"><?php echo $type['Expenses_Name']; ?></option>
    <?php endforeach; ?>
</select>

    <label for="expenses_cost">Expenses Cost (RM):</label>
    <input type="text" name="expenses_cost" required pattern="^\d+(\.\d{1,2})?$" title="Enter a valid price (e.g., 2.15)">

    <label for="expenses_description">Expenses Description:</label>
    <input type="text" name="expenses_description" required>

    <div style="display: flex; justify-content: space-between;">
      <input type="submit" name="submit" id="submit" value="Add Maintenance Data">
      <input type="reset" name="reset" id="reset" value="CLEAR FORM">
    </div>
  </form>

  <script>
    // Wait for the DOM to load
document.addEventListener("DOMContentLoaded", function () {
    // Get tomorrow's date in YYYY-MM-DD format
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowDate = tomorrow.toISOString().split("T")[0];
    // Set the max attribute for the date input
    document.getElementById("date").setAttribute("max", tomorrowDate);
});
</script>
</div>
</body>
</html>
