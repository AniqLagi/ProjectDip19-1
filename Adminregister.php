<?php
include('connect.php');

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password']; 
$userid = $_POST['usertype'];
// usertype
$usertype = $_POST['usertype'];

$checkemail = "SELECT * FROM user WHERE email='$email' OR username='$username'";
$result=$conn->query($checkemail);
if ($result->num_rows > 0) {
    echo "<script>alert('User  already exists!');</script>";
    echo "<meta http-equiv=\"refresh\" content=\"2;URL=adminadduser.php\">";
}
else {
$sql = "INSERT INTO user (userid, name, username, password, phone, email, usertype)
VALUES ('$userid','$name', '$username','$password', '$phone', '$email', '$usertype')" or die("Error inserting data into table");

if($conn->query($sql) === TRUE) {
    echo "<script>alert('User added successfully!');</script>";
    echo "<meta http-equiv=\"refresh\" content=\"2;URL=adminadduser.php\">";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

//Closes specified connection
$conn->close();
?>
