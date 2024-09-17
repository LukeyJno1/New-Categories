<?php
include '../includes/db_config.php';

// Get the database name and reinstall option from the form
$db_name = $_POST['db_name'];
$reinstall_option = $_POST['reinstall_option'];

// Connect to MySQL server
$conn = new mysqli($host, $user, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to backup database
function backup_database($db_name) {
    global $host, $user, $password;
    $backup_file = '../backups/' . $db_name . '_' . date('Ymd_His') . '.sql';
    $command = "mysqldump -h $host -u $user --password=$password $db_name > $backup_file";
    system($command);
}

// Function to restore database
function restore_database($db_name) {
    global $host, $user, $password;
    $backup_file = '../backups/' . $db_name . '_' . date('Ymd_His') . '.sql';
    $command = "mysql -h $host -u $user --password=$password $db_name < $backup_file";
    system($command);
}

// Create or reinstall database based on user selection
if ($reinstall_option == 'delete_empty') {
    $conn->query("DROP DATABASE IF EXISTS $db_name");
    $conn->query("CREATE DATABASE $db_name");
    echo "Database $db_name has been recreated with empty tables.";
} elseif ($reinstall_option == 'backup_reinstall') {
    backup_database($db_name);
    $conn->query("DROP DATABASE IF EXISTS $db_name");
    $conn->query("CREATE DATABASE $db_name");
    restore_database($db_name);
    echo "Database $db_name has been backed up, deleted, and restored.";
} else {
    $conn->query("CREATE DATABASE IF NOT EXISTS $db_name");
}

// Select the newly created or existing database
$conn->select_db($db_name);

// Create the 'categories' table if it doesn't exist
$create_categories_table = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($create_categories_table) === TRUE) {
    echo "Table 'categories' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

// Create any other necessary tables here (e.g., category_closure for hierarchical data)
$create_category_closure_table = "CREATE TABLE IF NOT EXISTS category_closure (
    ancestor_id INT NOT NULL,
    descendant_id INT NOT NULL,
    depth INT NOT NULL,
    path_length INT NOT NULL,
    PRIMARY KEY (ancestor_id, descendant_id),
    FOREIGN KEY (ancestor_id) REFERENCES categories(id),
    FOREIGN KEY (descendant_id) REFERENCES categories(id)
)";

if ($conn->query($create_category_closure_table) === TRUE) {
    echo "Table 'category_closure' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
