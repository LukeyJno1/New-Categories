<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
    <script src="../assets/js/admin_scripts.js"></script>
</head>
<body>
    <h1>Add New Category</h1>
    <div id="message"></div>
    <form id="add-category-form" action="process_add_category.php" method="post">
        <label for="selected_db">Select Database:</label>
        <select id="selected_db" name="db_name" required>
            <!-- Options will be populated by JavaScript -->
        </select><br><br>

        <label for="category_name">Category Name:</label>
        <input type="text" id="category_name" name="name" required><br><br>

        <label for="description">Category Description:</label>
        <textarea id="description" name="description"></textarea><br><br>

        <label for="parentCategoryList">Select Parent Categories (if any):</label>
        <div id="parentCategoryList"></div> <!-- Populated by JavaScript -->
        <input type="hidden" id="selected_parents" name="parent_ids"><br><br>

        <input type="submit" value="Add Category">
    </form>
</body>
</html>
