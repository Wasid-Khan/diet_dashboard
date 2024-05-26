$(document).ready(function() {
    // Load user info
    loadUserInfo();

    // Load food items
    loadFoodItems();

    // Event listener for meal log form
    $('#meal-log').on('submit', function(e) {
        e.preventDefault();
        logMeal();
    });
});

function loadUserInfo() {
    $.ajax({
        url: 'functions.php',
        type: 'GET',
        data: { action: 'getUserInfo' },
        success: function(response) {
            $('#user-info').html(response);
        }
    });
}

function loadFoodItems() {
    $.ajax({
        url: 'functions.php',
        type: 'GET',
        data: { action: 'getFoodItems' },
        success: function(response) {
            $('#food-items').html(response);
        }
    });
}

function logMeal() {
    const mealData = {
        user_id: 1, // Hardcoded for now, replace with actual user id
        date: $('#meal-date').val(),
        meal_type: $('#meal-type').val(),
        food_item_id: $('#food-item-id').val()
    };

    $.ajax({
        url: 'functions.php',
        type: 'POST',
        data: { action: 'logMeal', mealData: mealData },
        success: function(response) {
            alert('Meal logged successfully!');
        }
    });
}
