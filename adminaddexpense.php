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

// Handle form submission
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'admin'; // Assuming 'admin' as the role

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $query = "INSERT INTO user (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "New admin user added successfully!";
    } else {
        echo "Error adding new admin user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="CSSOnly/adminexpensepage.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function confirmDelete(expensetype) {
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
            // Redirect to the delete page for refueling
            window.location.href = 'delete_refueling.php?id=' + expensetype;
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

            <li class="">
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

            <li class="active">
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
            <h2 class="main--title"></h2>
            <div class="form-container">
            <h1>Add New Expenses Type</h1>
                <form id="form1" name="form1" method="post" action="adminaddexpenseform.php">
                <label for="name">Expenses Name:</label>
                <input type="text" name="name" id="name" required minlength="3" maxlength="50" placeholder="Enter New Expenses">
    
                 <div style="display: flex; justify-content: space-between;">
                 <input type="submit" name="submit" id="submit" value="Add">
                <input type="reset" name="reset" id="reset" value="CLEAR FORM" class="clear-form">  </form>
            </div>
        </div>

        <div class="tabular--wrapper">
            <h3 class="main--title2">List of Expenses Type</h3>
            <div style="margin-bottom: 5px;">
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Expenses Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // Build the base query
                        $sql = "SELECT * FROM expenses_type";

                        // Execute the query
                        $expensesResult = mysqli_query($conn, $sql);

                            if ($expensesResult && $expensesResult->num_rows > 0) {
                            while ($row = $expensesResult->fetch_assoc()) {
                            echo "<tr>
                             <td>" . htmlspecialchars($row['Expenses_Name']) . "</td>
                            <td>
                            <a href='updateexpenses.php?expensesID=" . $row['Expense_Type_ID'] . "' class='styled-button'>Edit</a>
                            <button type='button' class='styled-button' onclick='confirmDelete(" . $row['Expense_Type_ID'] . ")'>Delete</button>
                            </td>
                            </tr>";
                            }
                            } else {
                            echo "<tr><td colspan='2'>No expenses data found.</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</body>
</html>