<?php
session_start();
include 'connect.php';

// Check if session variables are set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$usernameee = $_SESSION['username'];
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
    function confirmDelete(expenseID) {
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
            window.location.href = 'delete_maintenace.php?id=' + expenseID;
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

            <li class="active">
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
            <h2 class="main--title">Maintenance Summary</h2>
            <div class="card--wrapper">
                <div class="payment--card">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                            <div style="text-align: center;">
                                <form method="POST" action="">
                                    <input type="text" name="search" placeholder="Search Make, Model or Plate" style="width: 250px; padding: 15px;">
                                    <input type="submit" value="Search" class="styled-button">
                                </form>
                            </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Table -->
        <div class="tabular--wrapper">
            <h3 class="main--title2">List of Maintenance Data</h3>
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
                            <th>Cost</th>
                            <th>Description</th>
                            <th>Expense Type</th>
                            <th>License Plate</th>
                            <th>Owner</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Capture the search input and month filter
                        $searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
                        $selectedMonth = isset($_POST['month']) ? $_POST['month'] : '';

                        // Build the query
                        $sql = "SELECT e.ExpensesID, e.Date, e.Mileage, e.Cost, e.Description, 
                                       et.Expenses_name AS ExpenseType, v.License_Plate, u.Name AS Owner
                                FROM expenses e
                                JOIN expenses_type et ON e.Expense_Type_ID = et.Expense_Type_ID
                                JOIN vehicle v ON e.VehicleID = v.VehicleID
                                JOIN user u ON e.UserID = u.UserID
                                WHERE 1=1";

                        $params = [];
                        $types = '';

                        if (!empty($searchTerm)) {
                            $sql .= " AND (v.License_Plate LIKE ? OR v.Make LIKE ? OR v.Model LIKE ?)";
                            $params[] = "%$searchTerm%";
                            $params[] = "%$searchTerm%";
                            $params[] = "%$searchTerm%";
                            $types .= 'sss';
                        }

                        if (!empty($selectedMonth)) {
                            $sql .= " AND MONTH(e.Date) = ?";
                            $params[] = $selectedMonth;
                            $types .= 's';
                        }

                        $expensesStmt = $conn->prepare($sql);
                        if (!$expensesStmt) {
                            die("SQL Error: " . $conn->error);
                        }

                        if (!empty($params)) {
                            $expensesStmt->bind_param($types, ...$params);
                        }

                        $expensesStmt->execute();
                        $expensesResult = $expensesStmt->get_result();

                        if ($expensesResult && $expensesResult->num_rows > 0) {
                            while ($row = $expensesResult->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['Date']) . "</td>
                                        <td>" . htmlspecialchars($row['Mileage']) . "</td>
                                        <td>" . htmlspecialchars($row['Cost']) . "</td>
                                        <td>" . htmlspecialchars($row['Description']) . "</td>
                                        <td>" . htmlspecialchars($row['ExpenseType']) . "</td>
                                        <td>" . htmlspecialchars($row['License_Plate']) . "</td>
                                        <td>" . htmlspecialchars($row['Owner']) . "</td>
                                        <td>
                                            <a href='updatemaintenance.php?ExpensesID=" . $row['ExpensesID'] . "' class='styled-button'>Edit</a>
                                            <button type='button' class='styled-button' onclick='confirmDelete(" . $row['ExpensesID'] . ")'>Delete</button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No maintenance data found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
