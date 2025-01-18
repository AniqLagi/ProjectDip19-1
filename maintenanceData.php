<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['VehicleID'])) {
    echo json_encode(['error' => 'VehicleID is not set in session.']);
    exit;
}

$vehicleID = $_SESSION['VehicleID'];

// Get the selected year and month or default to the current month
$selectedYear = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : date('Y');
$selectedMonth = isset($_GET['month']) && !empty($_GET['month']) ? str_pad($_GET['month'], 2, '0', STR_PAD_LEFT) : date('m');

// Combine year and month for filtering
$selectedYearMonth = "$selectedYear-$selectedMonth";

// Prepared statement to fetch weekly data based on custom week ranges
$sql = "
    SELECT 
        CASE 
            WHEN DAY(Date) BETWEEN 1 AND 7 THEN 1
            WHEN DAY(Date) BETWEEN 8 AND 14 THEN 2
            WHEN DAY(Date) BETWEEN 15 AND 21 THEN 3
            ELSE 4
        END AS weekNumber,
        COUNT(*) AS value
    FROM 
        expenses
    WHERE 
        VehicleID = ? 
        AND DATE_FORMAT(Date, '%Y-%m') = ?
    GROUP BY 
        weekNumber
    ORDER BY 
        weekNumber
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $vehicleID, $selectedYearMonth);
$stmt->execute();

$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . $stmt->error]);
    exit;
}

$data = [];
$weeks = [];

// Initialize all weeks to 0
for ($i = 1; $i <= 4; $i++) {
    $weeks[$i] = 0;
}

// Populate weeks with actual data
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $weekNumber = $row['weekNumber'];
        $weeks[$weekNumber] = (int)$row['value'];
    }
}

$data = [
    'data' => array_values($weeks),
    'month' => date('F', strtotime("$selectedYearMonth-01")),
    'year' => $selectedYear
];

header('Content-Type: application/json');
echo json_encode($data);
?>
