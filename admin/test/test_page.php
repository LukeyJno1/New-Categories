<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dynamic Category Loading</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
    <script src="test_script.js" defer></script>
</head>
<body>
    <h1>Test Dynamic Category Loading</h1>
    <form id="add-category-form">
        <label for="selected_db">Select Database:</label>
        <select name="selected_db" id="selected_db">
            <!-- Options will be dynamically populated by JavaScript -->
        </select><br>

        <label for="parent_category">Parent Categories:</label>
        <select name="parent_category[]" id="parent_category" multiple>
            <!-- Categories will be dynamically populated by JavaScript -->
        </select><br>
    </form>
    <div id="message"></div>
</body>
</html>
