<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create or Reinstall Database</title>
    <link rel="stylesheet" href="assets/css/admin_styles.css">
</head>
<body>
    <h1>Create or Reinstall Database</h1>
    <form action="process_create_tables.php" method="post">
        <label for="db_name">Database Name:</label>
        <input type="text" id="db_name" name="db_name" required><br><br>

        <label for="reinstall_option">Reinstall Options:</label>
        <select name="reinstall_option" id="reinstall_option">
            <option value="delete_empty">Delete and Recreate Empty Tables</option>
            <option value="backup_reinstall">Backup Data and Reinstall</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
