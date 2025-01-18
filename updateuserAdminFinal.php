<?php
include('connect.php');

$userid = isset($_POST['UserID']) ? $_POST['UserID'] : null;
$usertype = isset($_POST['usertype']) ? $_POST['usertype'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$phone = isset($_POST['phone']) ? $_POST['phone'] : null;
$username = isset($_POST['username']) ? $_POST['username'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null; // Store the password in plain text

$checkemail = "SELECT * FROM user WHERE (email='$email' OR username='$username') AND UserID != '$userid'";
$result=$conn->query($checkemail);
if ($result->num_rows > 0) {
    echo "<script>alert('Username or email already exists. Please try again.'); window.location.href='admin.php';</script>";
}
else {
    $stmt = $conn->prepare("UPDATE user SET Name = ?, Username = ?, Password = ?, Email = ?, Phone = ?, usertype = ? WHERE UserID = ?");
    $stmt->bind_param("ssssssi", $name, $username, $password, $email, $phone, $usertype, $userid);

    $stmt->execute();

if($stmt->affected_rows > 0) {
    echo "<meta http-equiv=\"refresh\" content=\"2;URL=admin.php\">";
} else {
    echo "Error: " . $stmt->error;
}
}

//Closes specified connection
$conn->close();
?>
