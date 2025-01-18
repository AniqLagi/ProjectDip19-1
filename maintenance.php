<?php
session_start();

//echo 'Debug: Session UserID = ' . $_SESSION['UserID'] . '<br>'; // Debug statement to check session UserID

include 'connect.php';

$usernameee = $_SESSION['username'];
$passworddd = $_SESSION['password'];
$userid = $_SESSION['UserID'];

//echo 'Debug: Username = ' .$usernameee. ' Password = '. $passworddd. 'lol ';

$vehicleID = $_SESSION['VehicleID']; // Retrieve VehicleID from session
if ($vehicleID) {
    //echo 'Debug: Selected Vehicle ID = ' . $vehicleID . '<br>'; // Debug statement to check selected vehicle ID

    // Query to retrieve vehicle details
    $sql = "SELECT * FROM vehicle WHERE VehicleID = '$vehicleID'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
        // Vehicle details will be displayed in the header section
    } else {
        echo 'No vehicle found with the selected ID.';
    }
}

// Query to fetch AccessRole
$checkrole = "SELECT AccessRole FROM user_vehicle WHERE UserID = ? AND VehicleID = ?";
$stmt = $conn->prepare($checkrole);
$stmt->bind_param("ii", $userid, $vehicleID);
$stmt->execute();
$resultrole = $stmt->get_result();

if ($resultrole && $resultrole->num_rows > 0) {
    $rowrole = $resultrole->fetch_assoc();
    $accessRole = $rowrole['AccessRole'];
    if ($accessRole === 'Owner' || $accessRole === 'Authorized') {
    } else {
        echo "You are not authorized to access this vehicle.";
        header("Refresh:3; url=vehicleselect.php"); // Redirect after 3 seconds
        exit();
    }
} else {
    header("Refresh:3; url=vehicleselect.php"); // Redirect after 3 seconds
    exit();
}


$totalCostQuery = "SELECT SUM(Cost) AS totalCost FROM Expenses WHERE VehicleID = '".$_SESSION['VehicleID']."'";
$totalCostResult = $conn->query($totalCostQuery);
$totalCostRow = $totalCostResult->fetch_assoc();
$totalCost = $totalCostRow['totalCost'] ? $totalCostRow['totalCost'] : 0; // Default to 0 if NULL

// Query to retrieve total distance driven and total maintenance cost
$sql2 = "SELECT SUM(Mileage) AS totalDistance, SUM(Cost) AS totalMaintenance FROM expenses WHERE VehicleID = '$vehicleID'";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

$totalDistance = $row2['totalDistance'] ? $row2['totalDistance'] : 0; // Default to 0 if NULL
$totalMaintenance = $row2['totalMaintenance'] ? $row2['totalMaintenance'] : 0; // Default to 0 if NULL

// Calculate KM per Maintenance
$kmPerMaintenance = $totalMaintenance > 0 ? $totalDistance / $totalMaintenance : 0; // Avoid division by zero

// Query to retrieve total maintenance data count
$sql3 = "SELECT COUNT(ExpensesID) AS totalMaintenanceData FROM Expenses WHERE VehicleID = '$vehicleID'";
$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();
$totalMaintenanceData = $row3['totalMaintenanceData'] ? $row3['totalMaintenanceData'] : 0; // Default to 0 if NULL
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Design | By Code Info</title>
    <link rel="stylesheet" href="CSSOnly/maintenancess2.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function checkVehicle(linkElement) {
        var accessRole = linkElement.getAttribute('data-access-role');
        var vehicleID = linkElement.getAttribute('data-vehicle-id');
        
        if (accessRole !== 'Owner') {
            Swal.fire({
                title: "NOT AUTHORIZED",
                text: "YOU ARE NOT THE VEHICLE OWNER",
                icon: "warning"
            });
        } else {
            window.location.href = 'usersharedlist.php?vehicleID=' + vehicleID;
        }
    }
    </script>


</head>
<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li class="" >
                <a href="dashboard2.php" >
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="refuelingpage.php">
                    <i class="fas fa-gas-pump"></i>
                    <span>Refueling</span>
                </a>
            </li>
            <li class="active">
                <a href="maintenance.php">
                    <i class="fas fa-wrench"></i>
                    <span>Maintenance</span>
                </a>
            </li>

            <li class="">
                <a href="#" onclick="checkVehicle(this)" data-access-role="<?php echo htmlspecialchars($accessRole); ?>" data-vehicle-id="<?php echo htmlspecialchars($vehicleID); ?>">
                    <i class="fas fa-share-alt"></i>
                    <span>Shared Vehicle</span>
                </a>
            </li>

            <li>
                <a href="vehicleselect.php">
                    <i class="fas fa-car"></i>
                    <span>Select Vehicle</span>
                </a>
            </li>

            <li class="logout">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>


    <!-- HEADER -->
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Dashboard / 
                <?php 
                $vehicleID = $_SESSION['VehicleID']; // Retrieve VehicleID from session
                if ($vehicleID) {
                    $sql = "SELECT * FROM vehicle WHERE VehicleID = '$vehicleID'";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        $vehicle = $result->fetch_assoc();
                        echo $vehicle['License_Plate'] . ' - ' . $vehicle['Make'] . ' ' . $vehicle['Model'];
                    } else {
                        echo 'No vehicle found';
                    }
                } else {
                    echo 'VEHICLEPLATENO';
                }
                ?>
                </h2>
            </div>
        <div class="user--info"> </div>
    
            <!-- <img src="assets\dProfileImage.jpg" alt="#"> -->
       </div>

        <!-- CARD CONTAINER -->
        <div class="card--container">
        <h2 class="main--title">Maintenance Summary</h2>
        <div class="card--wrapper">
            <div class="payment--card">
                <div class="card--header">
                    <div class="amount">
                        <span class="title">Total Spent of Maintenance</span>
                        <span class="amount-value"><?php echo "RM" . number_format($totalCost, 2); ?></span>
                    </div>
                    <i class="fas fa-dollar-sign icon"></i>
                </div>
                <span class="card-detail"></span>
            </div>

            <div class="payment--card">
                <a href="add_maintenance.php" class="styled-button">Add New Data</a>
                </div>

            <div class="payment--card">
                <div class="card--header">
                    <div class="amount">
                        <span class="title">Total of maintenance undergo</span>
                        <span class="amount-value"><?php echo number_format($totalMaintenanceData) . " Time"; ?></span>
                    </div>
                    <i class="fas fa-wrench icon"></i>
                </div>
                <span class="card-detail"></span>
            </div>

        </div>
    </div>
       

    <!-- Table -->
    <div class="tabular--wrapper">
        <h3 class="main--title2"> Maintenance Data</h3>
        <div style="margin-bottom: 20px;">
                <form method="POST" action="">
                    <select name="month" style="padding: 8px; border-radius: 4px;">
                        <option value="">Select Month</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <input type="submit" value="Filter" style="padding: 8px 16px; border-radius: 4px; background-color: rgba(113, 99, 186, 255); color: white; border: none; cursor: pointer;">
                </form>
            </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Mileage</th>
                        <th>Maintenance Type</th>
                        <th>Maintenance Cost (RM)</th>
                        <th>Maintenance Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
                    $selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';
                    
                    $sql = "SELECT A.Date, A.Mileage, B.Expenses_Name, A.Cost, A.Description, A.ExpensesID
                            FROM Expenses A
                            JOIN expenses_type B ON A.Expense_Type_ID = B.Expense_Type_ID
                            WHERE A.VehicleID = ?";
    
                    $params = [$vehicleID];
                    $types = 'i';
    
                    // Add search condition
                    if (!empty($searchTerm)) {
                        $sql .= " AND (B.Expenses_Name LIKE ? OR A.Description LIKE ?)";
                        $params[] = "%$searchTerm%";
                        $params[] = "%$searchTerm%";
                        $types .= 'ss';
                    }
    
                    // Add month filter condition
                    if (!empty($selectedMonth)) {
                        $sql .= " AND MONTH(A.Date) = ?";
                        $params[] = $selectedMonth;
                        $types .= 's';
                    }
    
                    $stmt = $conn->prepare($sql);
                    if (!$stmt) {
                        die("SQL Error: " . $conn->error);
                    }
    
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $result = $stmt->get_result();

                        if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['Date']}</td>
                                        <td>{$row['Mileage']}</td>
                                        <td>{$row['Expenses_Name']}</td>
                                        <td>{$row['Cost']}</td>
                                        <td>{$row['Description']}</td>
                                        <td>
                                        <a href='updatemaintenance.php?ExpensesID=" .$row['ExpensesID']."' class='styled-button'>Edit</a>
                                        <button type='button' class='styled-button' onclick='confirmDelete(" . $row['ExpensesID'] . ")'>Delete</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No maintenance data found.</td></tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>
    </div>


</div> 

</div>
<script>
    function confirmDelete(expensesID) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete page for maintenance
                window.location.href = 'delete_maintenace.php?id=' + expensesID;
            }
        });
    }
</script>


</body>
</html>