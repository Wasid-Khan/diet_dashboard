<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Your existing styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(to right, #ffccff, #ccccff); 
            color: white;
            padding: 10px;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            height: 50px;
        }

        .header .datetime {
            font-size: 18px;
            color: black;
        }

        .sidebar {
            background-color: #333;
            color: white;
            padding: 15px;
            width: 200px;
            flex-shrink: 0;
        }

        .sidebar a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }

        .container {
            display: flex;
            flex-direction: row;
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .button-container {
            margin-top: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="header">
        <img src="logo.jpg" alt="App Logo">
        <div class="datetime" id="datetime"></div>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="#" id="chart-link">Chart</a>
            <a href="#" id="food-list-link">Food List</a>
            <a href="#" id="today-link">Today</a>
        </div>
        <div class="content" id="content">
            <!-- Chart container -->
            <div id="chart-container" style="display: none;">
                <canvas id="macronutrient-chart"></canvas>
            </div>
            <!-- Content will be loaded here -->
        </div>
    </div>
    <div class="footer">
        &copy; All rights reserved, 2024
    </div>

    <script>
        var chartInstance = null;

        function updateDateTime() {
            var now = new Date();
            var datetimeString = now.toLocaleString();
            document.getElementById('datetime').innerText = datetimeString;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();

        document.getElementById('chart-link').addEventListener('click', function() {
            loadContent('chart');
        });

        document.getElementById('food-list-link').addEventListener('click', function() {
            loadContent('food_list');
        });

        document.getElementById('today-link').addEventListener('click', function() {
            loadContent('todays_list');
        });

        function loadContent(view) {
            if (view === 'chart') {
                displayChart();
                const chartTitle = '<div style="text-align: center;"><h2>Macro-Nutrients Chart</h2></div>';
                document.getElementById('content').insertAdjacentHTML('beforeend', chartTitle);
            } else {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', view + '.php', true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        document.getElementById('content').innerHTML = xhr.responseText;
                        if (view === 'food_list') {
                            loadFoodList();
                        } else if (view === 'todays_list') {
                            loadTodaysList();
                        }
                    }
                };
                xhr.send();
            }
        }

        function displayChart() {
            document.getElementById('content').innerHTML = '<div id="chart-container" style="width: 30vw; height: 30vh; margin: auto;"><canvas id="macronutrient-chart"></canvas></div>';
            drawPieChart();
        }

        function drawPieChart() {
            if (chartInstance) {
                chartInstance.destroy(); // Destroy the previous instance to prevent multiple charts
            }

            const ctx = document.getElementById('macronutrient-chart').getContext('2d');
            const chartData = <?php
                // Fetch chart data from the server-side if needed
                $chart_data = [
                    ['macro' => 'Fat', 'percentage' => 30],
                    ['macro' => 'Carbohydrate', 'percentage' => 50],
                    ['macro' => 'Protein', 'percentage' => 20],
                ];
                echo json_encode($chart_data);
            ?>;

            const labels = chartData.map(item => item.macro);
            const data = chartData.map(item => item.percentage);

            chartInstance = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Macronutrient Percentage',
                        data: data,
                        backgroundColor: ['#ffccff', '#ccccff', '#ccffcc'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        }

        function loadFoodList() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'food_list.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const foods = JSON.parse(xhr.responseText);
                    displayFoodList(foods);
                }
            };
            xhr.send();
        }

        function displayFoodList(foods) {
            let tableHtml = '<h2>Food List</h2>';
            tableHtml += '<table>';
            tableHtml += '<thead><tr><th>Name</th><th>Fat</th><th>Carbohydrate</th><th>Protein</th><th>Action</th></tr></thead>';
            tableHtml += '<tbody>';

            foods.forEach(function(food) {
                tableHtml += '<tr>';
                tableHtml += '<td>' + food.name + '</td>';
                tableHtml += '<td>' + food.fat + '</td>';
                tableHtml += '<td>' + food.carbohydrate + '</td>';
                tableHtml += '<td>' + food.protein + '</td>';
                tableHtml += '<td><button onclick="editFood(' + food.id + ')">Edit</button> <button onclick="deleteFood(' + food.id + ')">Delete</button></td>';
                tableHtml += '</tr>';
            });

            tableHtml += '</tbody>';
            tableHtml += '</table>';
            document.getElementById('content').innerHTML = tableHtml;
        }

        function editFood(id) {
    // Implement edit functionality
    console.log('Edit food item with ID: ' + id);
    // Redirect to edit page or open a modal for editing (implement as needed)
}

function deleteFood(id) {
    if (confirm('Are you sure you want to delete this food item?')) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_food.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                if (xhr.responseText === 'success') {
                    loadFoodList(); // Refresh the food list after deletion
                } else {
                    alert('Error deleting food item.');
                }
            }
        };
        xhr.send('id=' + id);
    }
}

function removeFromTodaysList(id) {
    if (confirm('Are you sure you want to remove this food item from today\'s list?')) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'remove_from_today.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                if (xhr.responseText === 'success') {
                    loadTodaysList(); // Refresh today's list after removal
                } else {
                    alert('Error removing food item from today\'s list.');
                }
            }
        };
        xhr.send('id=' + id);
    }
}

        function loadTodaysList() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'todays_list.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const todaysFoods = JSON.parse(xhr.responseText);
                    displayTodaysList(todaysFoods);
                }
            };
            xhr.send();
        }

        function displayTodaysList(todaysFoods) {
            let tableHtml = '<h2>Today\'s List</h2>';
            tableHtml += '<table>';
            tableHtml += '<thead><tr><th>Name</th><th>Fat</th><th>Carbohydrate</th><th>Protein</th><th>Action</th></tr></thead>';
            tableHtml += '<tbody>';

            todaysFoods.forEach(function(food) {
                tableHtml += '<tr>';
                tableHtml += '<td>' + food.name + '</td>';
                tableHtml += '<td>' + food.fat + '</td>';
                tableHtml += '<td>' + food.carbohydrate + '</td>';
                tableHtml += '<td>' + food.protein + '</td>';
                tableHtml += '<td><button onclick="removeFromTodaysList(' + food.id + ')">Remove</button></td>';
                tableHtml += '</tr>';
            });

            tableHtml += '<tr>';
            tableHtml += '<td>Total</td>';
            tableHtml += '<td>' + calculateTotal(todaysFoods, 'fat') + '</td>';
            tableHtml += '<td>' + calculateTotal(todaysFoods, 'carbohydrate') + '</td>';
            tableHtml += '<td>' + calculateTotal(todaysFoods, 'protein') + '</td>';
            tableHtml += '<td></td>';
            tableHtml += '</tr>';

            tableHtml += '</tbody>';
            tableHtml += '</table>';
            document.getElementById('content').innerHTML = tableHtml;
        }

        function calculateTotal(foods, nutrient) {
            return foods.reduce(function(total, food) {
                return total + food[nutrient];
            }, 0);
        }
    </script>
</body>
</html>
