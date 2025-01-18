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
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $accessRole = $row['AccessRole'];
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

$stmt->close();

// Query to retrieve total cost of refueling
$totalCostQuery = "SELECT SUM(Refulieng_Cost) AS totalCost FROM refueling WHERE VehicleID = ?";
$stmt = $conn->prepare($totalCostQuery);
$stmt->bind_param("i", $_SESSION['VehicleID']);
$stmt->execute();
$totalCostResult = $stmt->get_result();
$totalCostRow = $totalCostResult->fetch_assoc();
$totalCost = $totalCostRow['totalCost'] ? $totalCostRow['totalCost'] : 0; // Default to 0 if NULL

// Query to retrieve total cost of maintenance
$totalCostQuery2 = "SELECT SUM(Cost) AS totalCost FROM Expenses WHERE VehicleID = ?";
$stmt2 = $conn->prepare($totalCostQuery2);
$stmt2->bind_param("i", $_SESSION['VehicleID']);
$stmt2->execute();
$totalCostResult2 = $stmt2->get_result();
if ($totalCostResult2) {
    $totalCostRow2 = $totalCostResult2->fetch_assoc();
    $totalCost2 = isset($totalCostRow2['totalCost']) ? $totalCostRow2['totalCost'] : 0; // Default to 0 if NULL
} else {
    // Handle query error
    echo "Error: " . $conn->error;
}

// Query to retrieve refueling data ordered by refuel date or vehicle ID
$sql = "SELECT Mileage FROM refueling WHERE VehicleID = ? ORDER BY Date ASC"; // Make sure you have a column for the date, or use an appropriate column to sort by refuel order
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicleID);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables
$totalDistance = 0;
$previousMileage = 0;

while ($row = $result->fetch_assoc()) {
    $currentMileage = $row['Mileage'];
    
    // If there is previous data, calculate the distance traveled
    if ($previousMileage > 0) {
        $distanceTraveled = $currentMileage - $previousMileage;
        if ($distanceTraveled > 0) {
            $totalDistance += $distanceTraveled; // Add to total distance
        }
    }

    // Set current mileage as previous for next iteration
    $previousMileage = $currentMileage;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard | Aniq Azfar</title>
    <link rel="stylesheet" href="CSSOnly/Dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <li class="active">
                <a href="dashboard2.php">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="">
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

            <li>
                <a href="#" onclick="checkVehicle(this)" data-access-role="<?php echo $accessRole; ?>" data-vehicle-id="<?php echo $vehicleID; ?>">
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
                <h2><?php echo ("Welcome, " . $usernameee);?></h2>
                <h2><?php echo ("Vehicle Status: " . $accessRole);?></h2>
            </div>
            <div class="user--info"></div>
            
            <?php echo '<a href="#" onclick="checkAccess(' . $vehicleID . ')" class="styled-button">Edit Vehicle Details</a>';?>

            <script>
            function checkAccess(vehicleID) {
            <?php if ($accessRole !== 'Owner') { ?>
            Swal.fire({
            title: "NOT AUTHORIZED",
            text: "YOU ARE NOT THE VEHICLE OWNER",
            icon: "warning"
            });

            <?php } else { ?>
            window.location.href = 'updatevehicle.php?vehicleID=' + vehicleID;
            <?php } ?>
            }
            </script>   
            
            <?php echo '<a href="#" onclick="checkVehicleSummaryAccess(' . $vehicleID . ')" class="styled-button">Vehicle Summary</a>';?>
            <script>
            function checkVehicleSummaryAccess(vehicleID) {
            <?php if ($accessRole !== 'Owner') { ?>
                Swal.fire({
            title: "NOT AUTHORIZED",
            text: "YOU ARE NOT THE VEHICLE OWNER",
            icon: "warning"
            });
            <?php } else { ?>
            window.location.href = 'userdatasummary.php?vehicleID=' + vehicleID;
    <?php } ?>
}
</script>

            
            <!-- <img src="assets\dProfileImage.jpg" alt="#"> -->
        </div>

        <!-- CARD CONTAINER -->
        <div class="card--container">
            <h2 class="main--title">Account Summary</h2>
            <div class="card--wrapper">

                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">Total Spent on Refueling</span>
                            <span class="amount-value"><?php echo "RM" . number_format($totalCost, 2); ?></span>
                        </div>
                        <i class="fas fa-dollar-sign icon"></i>
                    </div>
                </div>

                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">Total Spent on Maintenance</span>
                            <span class="amount-value"><?php echo "RM". number_format($totalCost2, 2); ?></span>
                        </div>
                        <i class="fas fa-dollar-sign icon"></i>
                    </div>
                    <span class="card-detail"></span>
                </div>

                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">Total Distance Driven</span>
                            <span class="amount-value"><?php echo number_format($totalDistance, 2) . " km"; ?></span>
                        </div>
                        <i class="fas fa-road icon"></i>
                    </div>
                    <span class="card-detail"></span>
            </div>
        </div>

        <!-- Refueling Bar Chart -->
        <div class="tabular--wrapper">
            <h3 class="main--title2">Bar Report on Refueling</h3>
            <div>
                <label for="yearSelectRefuel">Select Year:</label>
                <select id="yearSelectRefuel">
                    <script>
                        const currentYear = new Date().getFullYear();
                        for (let year = currentYear; year >= currentYear - 10; year--) {
                            document.write(`<option value="${year}">${year}</option>`);
                        }
                    </script>
                </select>

                <label for="monthSelectRefuel">Select Month:</label>
                <select id="monthSelectRefuel">
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

                <button id="filterButtonRefuel" class="styled-button2">Filter</button>
            </div>

            <canvas id="refuelingChart" style="width: 100%; height: 300px;"></canvas>
        </div>

        <!-- Maintenance Bar Chart -->
        <div class="tabular--wrapper">
            <h3 class="main--title2">Bar Report on Maintenance Expenses</h3>
            <div>
            <label for="yearSelectMaintenance">Select Year:</label>
<select id="yearSelectMaintenance">
    <option value="">Select Year</option>
</select>

<script>
            document.addEventListener('DOMContentLoaded', () => {
            const yearSelect = document.getElementById('yearSelectMaintenance');
            const currentYear = new Date().getFullYear();

            // Add the last 10 years and current year to the dropdown
            for (let year = currentYear; year >= currentYear - 10; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }

            // Set the current year as the default selected year
            yearSelect.value = currentYear;
    });
</script>


                <label for="monthSelectMaintenance">Select Month:</label>
                <select id="monthSelectMaintenance">
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

                <button id="filterButtonMaintenance" class="styled-button2">Filter</button>
            </div>

            <canvas id="maintenanceChart" style="width: 100%; height: 300px;"></canvas>
        </div>
    </div>

    <script>
        // Refueling chart JavaScript
        document.addEventListener('DOMContentLoaded', () => {
            const ctxRefuel = document.getElementById('refuelingChart').getContext('2d');
            const refuelChart = new Chart(ctxRefuel, {
                type: 'bar',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Weekly Refueling Count',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value === 0 ? value : value + ' time';
                        }
                    }
                }
            }
        }
    });

    const fetchData = (year, month) => {
    fetch(`RefuelData.php?year=${year}&month=${month}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            refuelChart.data.datasets[0].data = data.data;
            refuelChart.data.datasets[0].totalCost = data.totalCost;
            refuelChart.options.title = {
                display: true,
                text: `Weekly Refueling Count for ${data.month} ${data.year}`
            };
            refuelChart.update();
        })
        .catch(error => console.error('Error fetching data:', error));
};

            const currentDate = new Date();
            fetchData(currentDate.getFullYear(), String(currentDate.getMonth() + 1).padStart(2, '0'));

            document.getElementById('filterButtonRefuel').addEventListener('click', () => {
                const selectedYear = document.getElementById('yearSelectRefuel').value;
                const selectedMonth = document.getElementById('monthSelectRefuel').value;
                fetchData(selectedYear, selectedMonth);
            });
        });

        // Maintenance chart JavaScript
        document.addEventListener('DOMContentLoaded', () => {
    const ctxMaintenance = document.getElementById('maintenanceChart').getContext('2d');
    const maintenanceChart = new Chart(ctxMaintenance, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Weekly Maintenance Expenses',
                data: [],
                backgroundColor: 'rgba(113, 99, 186, 0.6)',
                borderColor: 'rgba(113, 99, 186, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value === 0 ? value : value + ' time';
                        }
                    }
                }
            }
        }
    });

    const fetchMaintenanceData = (year, month) => {
        fetch(`MaintenanceData.php?year=${year}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                maintenanceChart.data.datasets[0].data = data.data;
                maintenanceChart.options.title = {
                    display: true,
                    text: `Weekly Maintenance Expenses for ${data.month} ${data.year}`
                };
                maintenanceChart.update();
            })
            .catch(error => console.error('Error fetching data:', error));
    };

    // Load data with the current year and month by default
    const currentDate = new Date();
    fetchMaintenanceData(currentDate.getFullYear(), String(currentDate.getMonth() + 1).padStart(2, '0'));

    // Set up event listener for filter button
    document.getElementById('filterButtonMaintenance').addEventListener('click', () => {
        const selectedYear = document.getElementById('yearSelectMaintenance').value;
        const selectedMonth = document.getElementById('monthSelectMaintenance').value;

        // Ensure that a year and month are selected
        if (selectedYear && selectedMonth) {
            fetchMaintenanceData(selectedYear, selectedMonth);
        } else {
            alert('Please select both a year and a month.');
        }
    });
});

    </script>
</body>
</html>
