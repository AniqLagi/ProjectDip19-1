<?php
session_start();

include 'connect.php';

// Check if session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}

$usernameee = $_SESSION['username'];
$passworddd = $_SESSION['password'];

// Define $vehicleID (assuming it's stored in the session or needs to be fetched)
$vehicleID = isset($_SESSION['vehicleID']) ? $_SESSION['vehicleID'] : null; // Adjust this line as necessary

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="CSSOnly/maintenancess2.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function confirmDelete(vehicleID) {
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
            // Redirect to the delete page for vehicle
            window.location.href = 'delete_vehicleAdmin.php?vehicleID=' + vehicleID;
        }
        });
    }
    </script>

</head>
<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
        <li class="">
                <a href="admin.php">
                    <i class="fas fa-home"></i>
                    <span>List of User</span>
                </a>
            </li>

            <li class="active">
                <a href="adminvehiclelist.php">
                    <i class="fas fas fa-car"></i>
                    <span>List of Vehicle</span>
                </a>
            </li>

            <li class="">
                <a href="adminfuelInfo.php">
                    <i class="fas fa-gas-pump"></i>
                    <span>Refueling Data</span>
                </a>
            </li>

            <li class="">
                <a href="adminmaintenanceInfo.php">
                    <i class="fas fa-wrench"></i>
                    <span>Maintenance Data</span>
                </a>
            </li>

            <li class="">
                <a href="adminadduser.php">
                    <i class="fas fa-user"></i>
                    <span>Add New User</span>
                </a>
            </li>

            <li class="">
                <a href="adminaddexpense.php">
                    <i class="fas fa-money-bill-alt"></i>
                    <span>New Expenses</span>
                </a>
            </li>

            <li class="">
                <a href="adminChart.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Chart</span>
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
                    echo htmlspecialchars($usernameee); // Sanitize output
                ?>
                </h2>
            </div>
            <div class="user--info"></div>
            <img src="assets/dProfileImage.jpg" alt="#">
        </div>

        <!-- CARD CONTAINER -->
        <div class="card--container">
            <h2 class="main--title">Vehicle Summary</h2>
            <div class="card--wrapper">
                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                            <div style="text-align: center;">
                                <form method="POST" action="">
                                    <input type="text" name="search" placeholder="Search by make, model, or license plate..." style="width: 250px; padding: 15px;">
                                    <input type="submit" value="Search" class="styled-button">
                                </form>
                            </div>
                            </span>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Table -->
                <div class="tabular--wrapper">
                    <h3 class="main--title2"> List of  Vehicle</h3>
                    <div class="table-container">
                    <table>
                            <thead>
                                <tr>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>License Plate</th>
                                    <th>Owner</th>
                                    <th>Ownership Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Capture the search input
                                $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
                                
                                if (empty($searchTerm)) {
                                    // If search term is empty, select all vehicles
                                    $vehicleStmt = $conn->prepare("
                                    SELECT v.VehicleID, v.Make, v.Model, v.License_Plate, u.Name AS Owner, uv.AccessRole as Status
                                    FROM vehicle v
                                    JOIN user_vehicle uv ON v.VehicleID = uv.VehicleID
                                    JOIN user u ON uv.UserID = u.UserID
                                    ");
                                } else {
                                    // Use prepared statements to prevent SQL injection
                                    $vehicleStmt = $conn->prepare("
                                    SELECT v.VehicleID, v.Make, v.Model, v.License_Plate, u.Name AS Owner
                                    FROM vehicle v
                                    JOIN user_vehicle uv ON v.VehicleID = uv.VehicleID
                                    JOIN user u ON uv.UserID = u.UserID
                                    WHERE v.Make LIKE ? OR v.Model LIKE ? OR v.License_Plate LIKE ?
                                    ");
                                    $searchWildcard = "%$searchTerm%";
                                    $vehicleStmt->bind_param("sss", $searchWildcard, $searchWildcard, $searchWildcard);
                                }
                                
                                // Check if the query preparation was successful
                                if ($vehicleStmt === false) {
                                    // Output error message if query preparation fails
                                    echo "Error preparing SQL query: " . $conn->error;
                                } else {
                                    // Execute the query
                                    $vehicleStmt->execute();
                                    $vehicleResult = $vehicleStmt->get_result();
                                
                                    if ($vehicleResult && $vehicleResult->num_rows > 0) {
                                        while ($row = $vehicleResult->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$row['Make']}</td>
                                                    <td>{$row['Model']}</td>
                                                    <td>{$row['License_Plate']}</td>
                                                    <td>{$row['Owner']}</td>
                                                    <td>{$row['Status']}</td>
                                                    <td>                  
                                                        <a href='updatevehicle.php?vehicleID=" . $row['VehicleID'] . "' class='styled-button'>Edit</a>
                                                        <button type='button' class='styled-button' onclick='confirmDelete(" . $row['VehicleID'] . ")'>Delete</button>                                                    </td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No vehicles found.</td></tr>";
                                    }
                                }
                                ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
             
        
    </div>
</body>
</html>
