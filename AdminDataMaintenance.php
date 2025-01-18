<?php
session_start();
include 'connect.php';

// Check if the user is an admin
if ($_SESSION['usertype'] !== 'admin') {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Get the selected year and month or default to the current month
$selectedYear = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : date('Y');
$selectedMonth = isset($_GET['month']) && !empty($_GET['month']) ? str_pad($_GET['month'], 2, '0', STR_PAD_LEFT) : date('m');

// Combine year and month for filtering
$selectedYearMonth = "$selectedYear-$selectedMonth";

// SQL query to count how many distinct users used each expenses_type in the selected month
$sql = "SELECT 
        et.Expenses_Name AS expenses_type, 
        COUNT(DISTINCT e.UserID) AS user_count
    FROM 
        expenses_type et
    LEFT JOIN 
        expenses e ON et.Expense_Type_ID = e.Expense_Type_ID AND DATE_FORMAT(e.Date, '%Y-%m') = ?
    GROUP BY 
        et.Expenses_Name
    ORDER BY 
        user_count DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $selectedYearMonth);
$stmt->execute();

$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . $stmt->error]);
    exit;
}

$data = [];
$expenses_types = [];
$user_counts = [];

// Populate data arrays with expenses types and corresponding user counts
while ($row = $result->fetch_assoc()) {
    $expenses_types[] = $row['expenses_type'];
    $user_counts[] = (int)$row['user_count'];
}

// Get all expenses types from the database
$all_expenses_types = [];
$sql = "SELECT Expenses_Name FROM expenses_type";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $all_expenses_types[] = $row['Expenses_Name'];
}

// Merge the two arrays, using the all_expenses_types array as the base
$merged_data = [];
foreach ($all_expenses_types as $expense_type) {
    $index = array_search($expense_type, $expenses_types);
    if ($index !== false) {
        $merged_data[] = ['expenses_type' => $expense_type, 'user_count' => $user_counts[$index]];
    } else {
        $merged_data[] = ['expenses_type' => $expense_type, 'user_count' => 0];
    }
}

$data = [
    'data' => array_column($merged_data, 'user_count'),
    'labels' => array_column($merged_data, 'expenses_type'),
    'month' => date('F', strtotime("$selectedYearMonth-01")),
    'year' => $selectedYear
];

header('Content-Type: application/json');
echo json_encode($data);
?>
