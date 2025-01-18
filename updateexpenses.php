<?php 
session_start();    
include 'connect.php';

$username = $_SESSION['username'];
$password = $_SESSION['password'];


if (isset($_REQUEST['expensesID'])) {
    $id = $_REQUEST['expensesID'];
    
    $stmt = $conn->prepare("SELECT * FROM Expenses_Type WHERE Expense_Type_ID = ?");
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
    <title>Edit Expenses Type Data</title>
    <link rel="stylesheet" href="CSSOnly/maintenance.css" />
    <link rel="stylesheet" href="CSSOnly/add_maintenance.css" />
    <link rel="stylesheet" href="CSSOnly/registerform2.css" />

</head>
<body>
<div class="form-container">
  <h1>Edit Expenses Type Data</h1>
  <form method="POST" action="updateexpensesfinal.php"> 
  <input type="hidden" name="u_expense" value="<?php echo $row['Expense_Type_ID']; ?>">

    <label for="name">Name:</label>
    <input type='text' name='name'  value='<?php echo $row['Expenses_Name']; ?>'></td>

    <div style="display: flex; justify-content: space-between;">
      <input type="submit" name="submit" id="submit" value="Update Expenses Type">
      <?php
        if ($_SESSION['usertype'] == 'admin') {
            echo '<a href="adminvehiclelist.php" class="styled-button">Back to Dashboard</a>';
    } else {
        echo '<a href="Dashboard2.php" class="styled-button">Back to Dashboard</a>';
    }
        ?>
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


