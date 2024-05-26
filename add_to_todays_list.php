<?php
// add_to_todays_list.php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_ids = $_POST['food_ids']; // Expecting an array of food IDs
    $errors = [];

    foreach ($food_ids as $food_id) {
        $food_id = mysqli_real_escape_string($conn, $food_id);
        $sql = "INSERT INTO todays_foods (food_id) VALUES ('$food_id')";

        if (!mysqli_query($conn, $sql)) {
            $errors[] = "Error adding food ID $food_id: " . mysqli_error($conn);
        }
    }

    if (empty($errors)) {
        echo json_encode(['success' => true, 'message' => 'Food items added to today\'s list.']);
    } else {
        echo json_encode(['success' => false, 'message' => $errors]);
    }
}
?>
