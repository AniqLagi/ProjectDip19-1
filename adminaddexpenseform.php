<?php
include('connect.php');

// Validate user input
if (empty($_POST['name'])) {
    echo "Error: Expense name is required.";
    exit();
}

$name = $_POST['name'];

// Use prepared statements to prevent SQL injection attacks
$stmt = $conn->prepare("SELECT * FROM expenses_type WHERE Expenses_Name = ?");
if (!$stmt) {
    echo "Error preparing SQL statement: " . $conn->error;
    exit();
}

$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Expense already exists!');</script>";
    echo "<meta http-equiv=\"refresh\" content=\"2;URL=adminaddexpense.php\">";
} else {
    $stmt = $conn->prepare("INSERT INTO expenses_type (Expenses_Name) VALUES (?)");
    if (!$stmt) {
        echo "Error preparing SQL statement: " . $conn->error;
        exit();
    }

    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        echo "<meta http-equiv=\"refresh\" content=\"2;URL=adminaddexpense.php\">";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Close the connection
$conn->close();
?>