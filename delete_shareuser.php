<?php
session_start();
include 'connect.php';

// Check if session variables are set (UserID and VehicleID)
if (!isset($_SESSION['UserID']) || !isset($_SESSION['vehicleID'])) {
    header("Location: vehicleselect.php");
    exit();
}

$userID = $_SESSION['UserID'];
$vehicleID = $_SESSION['vehicleID'];

// Check if deleteID is passed
if (isset($_GET['deleteID'])) {
    $deleteUserID = $_GET['deleteID'];

    // Ensure the user is authorized to delete the shared user
    $checkRoleQuery = "SELECT AccessRole FROM user_vehicle WHERE UserID = ? AND VehicleID = ?";
    $stmt = $conn->prepare($checkRoleQuery);
    $stmt->bind_param("ii", $userID, $vehicleID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $accessRole = $row['AccessRole'];

        if ($accessRole === 'Owner') {
            // Perform the deletion if the current user is the vehicle owner
            $deleteQuery = "DELETE FROM user_vehicle WHERE UserID = ? AND VehicleID = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("ii", $deleteUserID, $vehicleID);

            if ($stmt->execute()) {
                echo "<script>alert('User has been removed successfully');</script>";
                echo "<script>window.location.href = 'usersharedlist.php';</script>"; // Redirect back to the page
            } else {
                echo "<script>alert('Error in deleting the user.');</script>";
            }
        } else {
            // The user is not authorized to delete other users
            echo "<script>alert('You are not authorized to delete this user.');</script>";
        }
    }
} else {
    echo "No user to delete.";
}
?>
