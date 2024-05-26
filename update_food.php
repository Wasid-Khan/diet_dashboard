<?php
// update_food.php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $fat = mysqli_real_escape_string($conn, $_POST['fat']);
    $carbohydrate = mysqli_real_escape_string($conn, $_POST['carbohydrate']);
    $protein = mysqli_real_escape_string($conn, $_POST['protein']);

    $sql = "UPDATE foods SET name='$name', fat='$fat', carbohydrate='$carbohydrate', protein='$protein' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Food item updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating food item: ' . mysqli_error($conn)]);
    }
}
?>
