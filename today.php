<?php
// today.php
include 'config.php';

$sql = "SELECT * FROM today";
$result = mysqli_query($conn, $sql);

$total_fat = 0;
$total_carbohydrate = 0;
$total_protein = 0;

if (mysqli_num_rows($result) > 0) {
    echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Today's Food List</title>
    <style>
        /* Paste the CSS code here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .no-items {
            font-style: italic;
            color: #999;
        }

        /* Additional styles for the pie chart */
        .chart-container {
            margin-top: 20px;
            text-align: center;
        }

        canvas#macronutrient-chart {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>";

    echo "<div class=\"container\">";
    echo "<h2>Today's Food List</h2>";
    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Fat (g)</th>
                <th>Carbohydrate (g)</th>
                <th>Protein (g)</th>
                <th>Actions</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $total_fat += $row['fat'];
        $total_carbohydrate += $row['carbohydrate'];
        $total_protein += $row['protein'];
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['fat']}</td>
                <td>{$row['carbohydrate']}</td>
                <td>{$row['protein']}</td>
                <td>
                    <button>Remove</button>
                </td>
              </tr>";
    }
    echo "<tr>
            <td><strong>Total</strong></td>
            <td><strong>{$total_fat}</strong></td>
            <td><strong>{$total_carbohydrate}</strong></td>
            <td><strong>{$total_protein}</strong></td>
            <td></td>
          </tr>";
    echo "</table>";

    echo "<button>Remove from today's list</button>";

    // Add the canvas element for the pie chart
    echo "<div class=\"chart-container\">
            <canvas id=\"macronutrient-chart\"></canvas>
          </div>";

    echo "</div>";

    echo "</body>
</html>";

} else {
    echo "No food items for today.";
}

mysqli_close($conn);
?>
