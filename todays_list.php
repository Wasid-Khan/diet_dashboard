<?php
// todays_list.php
include 'config.php';

$sql = "
SELECT foods.*, todays_foods.id AS todays_id 
FROM todays_foods 
JOIN foods ON todays_foods.food_id = foods.id
";
$result = mysqli_query($conn, $sql);
$todays_foods = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $todays_foods[] = $row;
    }
}

echo json_encode($todays_foods);
?>
