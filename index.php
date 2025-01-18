<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="x-icon" href="assets/BOT-ico.ico">
        <title>Home</title>
        <link rel="stylesheet" href="CSSOnly/index2.css">
    </head>
    <body>
        <header id="header">
            <div class="top-bar">
                <nav class="nav-bar">
                    <a href="index.php"><img src="assets/VHS4.png" class="logo" alt="User profile"></a>
                    <ul>
                        <li><a href="About Us.php">About Us</a></li>
                        <?php
                        if (!isset($_SESSION['email']) && !isset($_SESSION['tutoremail'])) {
                            echo "<li><a href='Loginform.php'>Sign In Here</a>";
                            
                            echo "</ul>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </header>
        <div>
            <h1 style="color: white;" class="center"><font color ="white" size="7" font-family="Poppins">Vehicle Management System</font></h1>
            <h1 style="color: white;" class="center">Welcome to Vehicle Management System Website <?php echo date("Y"); ?></h1>
            <div class="image-container">
                <center class="ads-container">
                <center><img src="assets/promote1.png" alt="" class="ads-container" ></center>
                <!-- <a href='Loginform.php'><button type='button' id='regist' class='rounded-button'>GET STARTED</button></a>; -->
                </center>
                
            </div>
        </div>
        <?php
            if (!isset($_SESSION['Username']) && !isset($_SESSION['Username'])) {
                echo "<div class='note-container'>";
                echo "<h3>Note* Please login to get into your dashboard!.</h3>";
                echo "<button type='button' class='X-button'>X</button>";
                echo "</div>";
            }
        ?>
        <div>
            <h1 style="color: white;" class="center">What Do We Offer?</h1>
            <div class="row" style="margin-bottom: 90px;">
                <div class="column">
                  <img src="assets/Fuel.png" alt="">
                  <h3 style="color:white">Track your fuel consumption</h3>
                  <p style="color:white">You can save your vehicle refueling information and see your fuel consumption</p>
                </div>
                <div class="column">
                  <img src="assets/Car2.png" alt="">
                  <h3 style="color:white">Maintenance Tracking</h3>
                  <p style="color:white">Save your maintenance data in the website for more organise data!</p>
                </div>
                <div class="column">
                  <img src="assets/Summary.png" alt="" >
                  <h3 style="color:white">Data summary of your vehicle</h3>
                  <p style="color:white">See a report and data summary of refueling and maintenance of your vehicle!</p>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('scroll', () => {
                const header = document.querySelector('header');
                if (window.scrollY > 0) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
            document.querySelector('.X-button').addEventListener('click', function() {
                document.querySelector('.note-container').style.display = 'none';
            });
        </script>
    </body>
    <footer>
        <hr>
        <div class="bottom-content">
            <div id="about-us" class="about-us">
                <h3>About us</h3>
                <hr>
                <a href="About Us.php"><p>About Us</p></a>
            </div>
            <div id="classes" class="classes">
                <h3>Login or Register</h3>
                <hr>
                <a href="Loginform.php"><p>Login Here</p></a>
                
            </div>
            <div id = "social-media" class="social-media">
                <h3>Follow us:</h3>
                <hr>
                <a href="" target="_blank" title="Go to my facebook website">
                    <img src="assets/facebook.png" width="20%" alt="facebook-icon">
                </a>
                <a href="" target="_blank" title="Go to my instagram website">
                    <img src="assets/instagram.png" width="20%" alt="instagram-icon">
                </a>
                <a href="" target="_blank" title="Go to my twitter website">
                    <img src="assets/twitter.png" width="20%" alt="twitter-icon">
                </a>
                <a href="" target="_blank" title="Go to my personal Youtube website">
                    <img src="assets/youtube.png" width="20%" alt="youtube-icon">
                </a>
            </div>
        </div>
        <div class="second-bottom-content">
            <h6 class="copyright">Copyright @  Vehicle Management System (Aniq Azfar) <?php echo date("Y"); ?></h6>
            <!-- <h6 class="policy">
                <a href="Terms-of-Service.php">Terms of service</a>
                <strong> | </strong>
                <a href="Privacy-Policy.php">Privacy Policy</a>
                <strong> | </strong>
                <a href="Refund-Policy.php">Refund Policy</a>
            </h6> -->
        </div>
    </footer>
</html>
    