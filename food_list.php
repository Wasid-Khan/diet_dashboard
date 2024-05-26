<?php
// food_list.php
include 'config.php';

$sql = "SELECT * FROM foods";
$result = mysqli_query($conn, $sql);
$foods = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $foods[] = $row;
    }
}

echo json_encode($foods);
?>
