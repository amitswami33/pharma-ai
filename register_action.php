<?php
// Include the database connection file
include('db_connection.php');

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Username already exists
        echo "Username already taken. Please choose another.";
    } else {
        // Insert user data into the users table
        $insert_query = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
        
        if (mysqli_query($conn, $insert_query)) {
            // Redirect to login page upon successful registration
            header('Location: index.php');
        } else {
            // Error in inserting data
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
