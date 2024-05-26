<?php
// food-list.php
include 'config.php';

$sql = "SELECT * FROM food";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h2>Food List</h2>";
    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Fat (g)</th>
                <th>Carbohydrate (g)</th>
                <th>Protein (g)</th>
                <th>Actions</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['fat']}</td>
                <td>{$row['carbohydrate']}</td>
                <td>{$row['protein']}</td>
                <td>
                    <button>Edit</button>
                    <button>Delete</button>
                </td>
              </tr>";
    }
    echo "</table>";
    echo "<button>Add Food Item</button>";
    echo "<button>Add to today's list</button>";
} else {
    echo "No food items found.";
}

mysqli_close($conn);
?>
