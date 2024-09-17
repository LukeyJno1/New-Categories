<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require_once '../../admin/includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create_tables') {
    $host = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $dbname = filter_input(INPUT_POST, 'dbname', FILTER_SANITIZE_STRING);

    // Instantiate the Database class
    $database = new Database($host, $username, $password, $dbname);
    $conn = $database->connect();

    $sql = "
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS category_closure (
        ancestor_id INT NOT NULL,
        descendant_id INT NOT NULL,
        depth INT NOT NULL,
        path_length INT NOT NULL,
        PRIMARY KEY (ancestor_id, descendant_id, depth),
        FOREIGN KEY (ancestor_id) REFERENCES categories(id) ON DELETE CASCADE,
        FOREIGN KEY (descendant_id) REFERENCES categories(id) ON DELETE CASCADE
    );";

    if ($conn->multi_query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Tables created successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error creating tables: ' . $conn->error]);
    }

    $conn->close();
    exit;
}
?>
