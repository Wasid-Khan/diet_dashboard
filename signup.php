<?php
// signup.php
include 'config.php';
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Username already exists
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
    } else {
        if ($password === $confirm_password) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password_hash')";

            if (mysqli_query($conn, $sql)) {
                // Registration successful
                echo json_encode(['success' => true, 'message' => 'Registration successful. You can now login.']);
            } else {
                // Database error
                echo json_encode(['success' => false, 'message' => 'Error: ' . $sql . '<br>' . mysqli_error($conn)]);
            }
        } else {
            // Passwords do not match
            echo json_encode(['success' => false, 'message' => 'Passwords do not match. Please try again.']);
        }
    }

    mysqli_close($conn);
}
?>
