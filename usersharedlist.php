<?php
session_start();
include 'connect.php';
// Check if the vehicleID is passed in the URL and store it in the session
if (isset($_GET['vehicleID'])) {
    $_SESSION['vehicleID'] = $_GET['vehicleID'];
} elseif (!isset($_SESSION['vehicleID'])) {
    echo "Vehicle ID is not set!";
    exit();
}
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

// $vehicleID = $_SESSION['vehicleID']; // Now the session has the vehicleID

// Check if session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    echo "Session variables are not set!";
    exit();
}

$usernameee = $_SESSION['username'];
$passworddd = $_SESSION['password'];

$accessRole = '';

// Get UserID from the username
$stmt = $conn->prepare("SELECT UserID FROM user WHERE username = ?");
$stmt->bind_param("s", $usernameee);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userID = $user['UserID'];

    // Fetch access role for the vehicle
    $stmt = $conn->prepare("SELECT AccessRole FROM user_vehicle WHERE UserID = ? AND VehicleID = ?");
    $stmt->bind_param("ii", $userID, $vehicleID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $accessRole = $row['AccessRole'];
    }
}
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
        function confirmDelete(userID) {
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
                    window.location.href = 'delete_shareuser.php?deleteID=' + userID;
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
                <a href="dashboard2.php">
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
            <li>
                <a href="maintenance.php">
                    <i class="fas fa-wrench"></i>
                    <span>Maintenance</span>
                </a>
            </li>

            <li class="active">
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
                <h2>Dashboard / <?php 
                if (isset($vehicle)): 
                    echo $vehicle['License_Plate'] . ' - ' . $vehicle['Make'] . ' ' . $vehicle['Model'];
                    
                else: 
                    echo 'VEHICLEPLATENO';
                endif; 
                ?></h2>
            </div>
            <div class="user--info"></div>
            <img src="assets/dProfileImage.jpg" alt="#">
        </div>

        <!-- SEARCH FORM -->
        <div class="card--container">
            <h2 class="main--title">Account Summary</h2>
            <div class="card--wrapper">
                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                                <form method="POST" action="">
                                    <input type="text" name="search" placeholder="Search users here..." style="width: 150px; padding: 15px;">
                                    <input type="submit" value="Search" class="styled-button">
                                </form>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- USER LIST -->
        <div class="tabular--wrapper">
            <h3 class="main--title2">List of User</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Capture the search input
                        $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

                        if (empty($searchTerm)) {
                            $stmt = $conn->prepare("SELECT u.Name, u.Email, u.Phone, uv.AccessRole, uv.UserID
                                                    FROM user u
                                                    JOIN user_vehicle uv ON u.UserID = uv.UserID
                                                    WHERE uv.VehicleID = ? AND uv.AccessRole = 'Authorized'");
                            $stmt->bind_param("i", $vehicleID);
                        } else {
                            $searchWildcard = "%$searchTerm%";
                            $stmt = $conn->prepare("SELECT u.Name, u.Email, u.Phone, uv.AccessRole, uv.UserID
                                                    FROM user u
                                                    JOIN user_vehicle uv ON u.UserID = uv.UserID
                                                    WHERE (u.Name LIKE ? OR u.Email LIKE ? OR u.Phone LIKE ?)
                                                    AND uv.VehicleID = ? AND uv.AccessRole = 'Authorized'");
                            $stmt->bind_param("sssi", $searchWildcard, $searchWildcard, $searchWildcard, $vehicleID);
                        }

                        $stmt->execute();
                        $userResult = $stmt->get_result();

                        if ($userResult && $userResult->num_rows > 0) {
                            while ($row = $userResult->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['Name']}</td>
                                        <td>{$row['Email']}</td>
                                        <td>+60{$row['Phone']}</td>
                                        <td>{$row['AccessRole']} User</td>
                                        <td>
                                            <button type='button' class='styled-button' onclick='confirmDelete(" . $row['UserID'] . ")'>Remove User</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
