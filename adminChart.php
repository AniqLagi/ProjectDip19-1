<?php
session_start();

include 'connect.php';

// Check if session variables are set
if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['usertype'] !== 'admin') {
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            <li class="">
                <a href="adminaddexpense.php">
                    <i class="fas fa-money-bill-alt"></i>
                    <span>New Expenses</span>
                </a>
            </li>

            <li class="active">
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
        <!-- <div class="card--container">
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
        </div> -->

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
                    title: {
                        display: true,
                        text: 'Number of Refuelings'
                    },
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value === 0 ? value : value;
                        }
                    }
                }
            }
        }
    });

    const fetchData = (year, month) => {
    fetch(`AdminDataRefuel.php?year=${year}&month=${month}`)
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
        document.addEventListener("DOMContentLoaded", function() {
    // Initialize chart once
    let maintenanceChart;

    function initializeMaintenanceChart() {
        const ctx = document.getElementById('maintenanceChart').getContext('2d');
        maintenanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],  // Initially empty
                datasets: [{
                    label: 'Number of Users per Expense Type',
                    data: [],  // Initially empty
                    backgroundColor: '#9b59b6',
                    borderColor: '#36a2eb',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Users'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Expense Types'
                        }
                    }
                }
            }
        });
    }

    // Update chart data after fetching new data
    function updateMaintenanceChart(data) {
    maintenanceChart.data.labels = data.labels;
    maintenanceChart.data.datasets[0].data = data.data;
    maintenanceChart.update();
}

    // Fetch maintenance data for the selected year and month
    function fetchMaintenanceData(year, month) {
        fetch(`AdminDataMaintenance.php?year=${year}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                // Check if the response has valid data
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }

                // Update the chart with the fetched data
                updateMaintenanceChart(data);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    // Initialize the chart
    initializeMaintenanceChart();

    // Get the current month and year
    const currentYear = new Date().getFullYear();
    const currentMonth = String(new Date().getMonth() + 1).padStart(2, '0'); // Get the month (01-12)

    // Fetch and display the current month's data
    fetchMaintenanceData(currentYear, currentMonth);

    // Add event listener for the filter button
    document.getElementById('filterButtonMaintenance').addEventListener('click', function() {
        const selectedYear = document.getElementById('yearSelectMaintenance').value;
        const selectedMonth = document.getElementById('monthSelectMaintenance').value;

        // Fetch and display data for the selected year and month
        fetchMaintenanceData(selectedYear, selectedMonth);
    });
});



    </script>
</body>
</html>
