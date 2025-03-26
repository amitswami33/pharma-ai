<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Update user information in the database
    $query = "UPDATE users SET username='$username', email='$email' WHERE user_id='$user_id'";

    if (mysqli_query($conn, $query)) {
        // Redirect back to the profile page after update
        header("Location: profile.php");
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>
