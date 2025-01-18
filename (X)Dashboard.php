<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/logobaru.png">
    <title>Dashboard</title>
    <link rel="stylesheet" href="index.css"> <!-- Link to your CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>
    <!-- Header Section -->
    <header id="header">
        <div class="top-bar">
            <nav class="nav-bar">
                <!-- Logo -->
                <a href="Homepage.php">
                    <img src="assets/logobaru.png" class="logo" alt="Logo">
                </a>
                <!-- Navigation Links -->
                <ul>
                    <li><a href="FuelPage.php">Fuel Page</a></li>
                    <li><a href="MaintenancePage.php">Maintenance Page</a></li>
                </ul>
                <!-- Profile Picture -->
                <?php
                    session_start(); // Start the session
                    if (isset($_SESSION['email'])) {
                        echo "<a href='Profile.php'><img src='".$row['Image']."' class='user-pic' alt='User  profile' title='User  profile'></a>";
                    } elseif (isset($_SESSION["tutoremail"])) {
                        echo "<a href='Tutorprofile.php'><img src='".$row['Image']."' class='tutor-user-pic' alt='Tutor profile' title='Tutor User profile'></a>";
                    } else {
                        echo "<img src='assets/dProfileImage.jpg' alt='Default Profile' class='user-pic'>";
                    }
                ?>
            </nav>
        </div>
    </header>

    <!-- Dashboard Content -->
    <main>
        <section>
            <h1 class="left">DASHBOARD</h1>
            <h4 class="left">Welcome to your Dashboard</h4>
        </section>

        <!-- Summary Table -->
        <section class="summary-section">
            <h2>Summary of Refuel and Maintenance</h2>
            <table border="1" class="summary-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Refuel (Litres)</th>
                        <th>Refuel Cost</th>
                        <th>Maintenance Description</th>
                        <th>Maintenance Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-07-01</td>
                        <td>40</td>
                        <td>$60</td>
                        <td>Oil Change</td>
                        <td>$30</td>
                    </tr>
                    <tr>
                        <td>2024-07-10</td>
                        <td>35</td>
                        <td>$52</td>
                        <td>Tire Replacement</td>
                        <td>$200</td>
                    </tr>
                    <tr>
                        <td>2024-07-15</td>
                        <td>50</td>
                        <td>$75</td>
                        <td>Brake Pad Replacement</td>
                        <td>$150</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Charts Section -->
        <section class="charts-section">
            <h2>Expense Overview</h2>
            <div class="charts-container">
                <!-- Pie Chart for Refuel -->
                <div class="chart">
                    <h3>Refuel Summary</h3>
                    <canvas id="refuelChart" width="300" height="300"></canvas>
                </div>
                <!-- Pie Chart for Maintenance -->
                <div class="chart">
                    <h3>Maintenance Summary</h3>
                    <canvas id="maintenanceChart" width="300" height="300"></canvas>
                </div>
 </div>
        </section>
    </main>

    <!-- Chart.js Script -->
    <script defer>
        // Refuel Chart Data
        const refuelCtx = document.getElementById('refuelChart').getContext('2d');
        new Chart(refuelCtx, {
            type: 'pie',
            data: {
                labels: ['July 1 (40L)', 'July 10 (35L)', 'July 15 (50L)'],
                datasets: [{
                    label: 'Litres Refueled',
                    data: [40, 35, 50],
                    backgroundColor: ['#4CAF50', '#FFC107', '#2196F3'],
                }]
            }
        });

        // Maintenance Chart Data
        const maintenanceCtx = document.getElementById('maintenanceChart').getContext('2d');
        new Chart(maintenanceCtx, {
            type: 'pie',
            data: {
                labels: ['Oil Change ($30)', 'Tire Replacement ($200)', 'Brake Pad Replacement ($150)'],
                datasets: [{
                    label: 'Maintenance Cost',
                    data: [30, 200, 150],
                    backgroundColor: ['#FF5733', '#C70039', '#900C3F'],
                }]
            }
        });
    </script>

    <!-- Footer Section -->
    <footer>
        <hr>
        <div class="bottom-content">
            <div id="about-us" class="about-us">
                <h3>About us</h3>
                <hr>
                <a href="About Us.php"><p>About Us</p></a>
                <?php 
                    if (!isset($_SESSION["tutoremail"])) {
                        echo '<a href="Cart.php"><p>My Cart</p></a>';
                    }
                ?>
            </div>
            <div id="classes" class="classes">
                <h3>Classes</h3>
                <hr>
                <a href="Loginform.php"><p>Login</p></a>
            </div>
            <div id="social-media" class="social-media">
                <h3>Follow us:</h3>
                <hr>
                <a href="https://www.facebook.com/muhd.saiffuddin.9" target="_blank" title="Go to my facebook website">
                    <img src="assets/facebook.png" width="20%" alt="facebook-icon">
                </a>
                <a href="https://www.instagram.com/muhd_saiffuddin/" target="_blank" title="Go to my instagram website">
                    <img src="assets/instagram.png" width="20%" alt="instagram-icon">
                </a>
                <a href="https://twitter.com/SaiTheLimit" target="_blank" title="Go to my twitter website">
                    <img src="assets/twitter.png" width="20%" alt="twitter-icon">
                </a>
                <a href="https://www.youtube.com/channel/UCIUfqs_ONJLtFqjeNUERU_g" target="_blank" title="Go to my personal Youtube website">
                    <img src="assets/youtube.png" width="20%" alt="youtube-icon">
                </a>
            </div>
        </div>
        <div class="second-bottom-content">
            <h6 class="copyright">Copyright @ The Boundless Tuition UTeM <?php echo date("Y"); ?></h6>
            <h6 class="policy">
                <a href="Terms-of-Service.php">Terms of service</a>
                <strong> | </strong>
                <a href="Privacy-Policy.php">Privacy Policy</a>
                <strong> | </strong>
                <a href="Refund-Policy.php">Refund Policy</a>
            </h6>
        </div>
    </footer>
</body>
</html>