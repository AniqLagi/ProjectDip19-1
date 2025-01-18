<?php
session_start();

include 'connect.php';

$usernameee = $_SESSION['username'];
$passworddd = $_SESSION['password'];
$userid = $_SESSION['UserID'];

$vehicleID = $_SESSION['VehicleID']; // Retrieve VehicleID from session
if ($vehicleID) {
    // Query to retrieve vehicle details
    $sql = "SELECT * FROM vehicle WHERE VehicleID = '$vehicleID'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $vehicle = $result->fetch_assoc();
    } else {
        echo 'No vehicle found with the selected ID.';
    }
} else {
    echo 'VEHICLEPLATENO';
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

$totalCostQuery = "SELECT SUM(Refulieng_Cost) AS totalCost FROM refueling WHERE VehicleID = '".$_SESSION['VehicleID']."'";
$totalCostResult = $conn->query($totalCostQuery);
$totalCostRow = $totalCostResult->fetch_assoc();
$totalCost = $totalCostRow['totalCost'] ? $totalCostRow['totalCost'] : 0; // Default to 0 if NULL

/// Query to retrieve refueling data
$sql = "SELECT * FROM refueling WHERE VehicleID = '$vehicleID' ORDER BY Date ASC";
$refuelingResult = $conn->query($sql);

$totalKmPerLiter = 0;
$count = 0;
$previousMileage = 0;
$previousRefuelingAmount = 0;

if ($refuelingResult && $refuelingResult->num_rows > 0) {
    while ($row = $refuelingResult->fetch_assoc()) {
        $currentMileage = $row['Mileage'];

        if ($previousMileage > 0 && $previousRefuelingAmount > 0) {
            // Calculate distance and KM/L
            $distanceDriven = $currentMileage - $previousMileage;
            $kmPerLiterForEvent = $distanceDriven / $previousRefuelingAmount; // Use previous fuel amount
            $totalKmPerLiter += $kmPerLiterForEvent;
            $count++;
        }

        // Update previous values for next iteration
        $previousMileage = $currentMileage;
        $previousRefuelingAmount = $row['Refueling_Amount'];
    }
}

$averageKmPerLiter = $count > 0 ? $totalKmPerLiter / $count : 0;

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
            <li>
                <a href="dashboard2.php">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="refuelingpage.php">
                    <i class="fas fa-gas-pump"></i>
                    <span>Refueling</span>
                </a>
            </li>
            <li>
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
                if (isset($vehicle)): 
                    echo $vehicle['License_Plate'] . ' - ' . $vehicle['Make'] . ' ' . $vehicle['Model'];
                else: 
                    echo 'VEHICLEPLATENO';
                endif; 
                ?>
                </h2>
            </div>
            <div class="user--info"> </div>
            
            <!-- <img src="assets\dProfileImage.jpg" alt="#"> -->
        </div>

        <!-- CARD CONTAINER -->
        <div class="card--container">
            <h2 class="main--title">Refueling Summary</h2>
            <div class="card--wrapper">
                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">Total Spent of Refueling</span>
                            <span class="amount-value"><?php echo "RM" . number_format($totalCost, 2); ?></span>
                        </div>
                        <i class="fas fa-dollar-sign icon"></i>
                    </div>
                </div>

                <div class="payment--card">
                <a href="add_refueling.php" class="styled-button">Add New Data</a>
                </div>

                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">Average KM per 1 Litre of Fuel</span>
                            <span class="amount-value"><?php echo number_format($averageKmPerLiter, 2) . "KM"; ?></span>
                        </div>
                        <i class="fas fa-road icon"></i>
                    </div>
                    <span class="card-detail"></span>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="tabular--wrapper">
            <h3 class="main--title2"> Refueling Data</h3>
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
                            <th>Refueling Cost (RM)</th>
                            <th>Refueling Amount (Litre)</th>
                            <th>Price Per Litre (RM)</th>
                            <th>Fuel Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            
                        $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
                        $selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';

                        $sql = "SELECT r.RefuelingID, r.Date, r.Mileage, r.Refulieng_Cost, r.priceperlitre, 
                                r.Refueling_Amount, r.Fuel_Type
                                FROM refueling r
                                WHERE r.VehicleID = ?"; // Limit to user's vehicle
                        
                        $params = [$vehicleID];
                        $types = 'i';

                        if (!empty($searchTerm)) {
                            $sql .= " AND (r.Fuel_Type LIKE ? OR r.Mileage LIKE ?)";
                            $params[] = "%$searchTerm%";
                            $params[] = "%$searchTerm%";
                            $types .= 'ss';
                        }

                        if (!empty($selectedMonth)) {
                            $sql .= " AND MONTH(r.Date) = ?";
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
                                        <td>{$row['Refulieng_Cost']}</td>
                                        <td>{$row['Refueling_Amount']}</td>
                                        <td>{$row['priceperlitre']}</td>
                                        <td>{$row['Fuel_Type']}</td>
                                        <td>
                                        <a href='updaterefueling.php?RefuelingID=" .$row['RefuelingID']."' class='styled-button'>Edit</a>
                                        <button type='button' class='styled-button' onclick='confirmDelete({$row['RefuelingID']})'>Delete</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No refueling data found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
</div>
<script>
function confirmDelete(refuelingId) {
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
            // Redirect to delete_refueling.php with the ID of the record to be deleted
            window.location.href = 'delete_refueling.php?id=' + refuelingId;
        }
    });
}
</script>
</body>
</html>
