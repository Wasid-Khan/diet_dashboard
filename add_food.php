<?php
// add_food.php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $fat = mysqli_real_escape_string($conn, $_POST['fat']);
    $carbohydrate = mysqli_real_escape_string($conn, $_POST['carbohydrate']);
    $protein = mysqli_real_escape_string($conn, $_POST['protein']);

    $sql = "INSERT INTO foods (name, fat, carbohydrate, protein) VALUES ('$name', '$fat', '$carbohydrate', '$protein')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Food item added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding food item: ' . mysqli_error($conn)]);
    }
}
?>
