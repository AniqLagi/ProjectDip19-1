<?php
include('connect.php');

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password']; // Store the password in plain text

//generate userID
$userid = "userid" . uniqid();
// usertype
$usertype = "user";

$checkemail = "SELECT * FROM user WHERE email='$email' OR username='$username'";
$result=$conn->query($checkemail);
if ($result->num_rows > 0) {
    // echo "<style>body { background-color: rgba(113, 99, 186, 255); }</style>";
    // echo "<div style='background-color: white; color: black; padding: 10px; border-radius: 5px; text-align: center; margin: 20px auto; width: 50%;'>
    // Email Address or Username Already Exists</div>";
    echo "<script>alert('Email Address or Username Already Exists');</script>";
    echo "<meta http-equiv=\"refresh\" content=\"2;URL=registerform.php\">";
}
else {
$sql = "INSERT INTO user (userid, name, username, password, phone, email, usertype)
VALUES ('$userid','$name', '$username','$password', '$phone', '$email', '$usertype')" or die("Error inserting data into table");

if($conn->query($sql) === TRUE) {
    // echo "<style>body { background-color: rgba(113, 99, 186, 255); }</style>";
    // echo "<div style='background-color: white; color: black; padding: 10px; border-radius: 5px; text-align: center; margin: 20px auto; width: 50%;'>
    // Sign Up Successfully</div>";
    echo "<script>alert('User Registered Successfuly!');</script>";
    echo "<meta http-equiv=\"refresh\" content=\"2;URL=loginform.php\">";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

//Closes specified connection
$conn->close();
?>
