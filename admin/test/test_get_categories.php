<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once '../includes/db_config.php';
require_once '../includes/db_connection.php';

$response = [];

if (isset($_GET['selected_db'])) {
    $selected_db = $_GET['selected_db'];

    try {
        // Connect to the selected database
        $database = new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, $selected_db);
        $conn = $database->connect();

        if (!$conn) {
            throw new Exception("Failed to connect to database: " . $conn->connect_error);
        }

        // Fetch categories from the database
        $sql = "SELECT id, name FROM categories ORDER BY name";
        $result = $conn->query($sql);

        if (!$result) {
            throw new Exception("Failed to execute query: " . $conn->error);
        }

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = ['id' => $row['id'], 'name' => $row['name']];
        }

        $response['status'] = 'success';
        $response['categories'] = $categories;

    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Error fetching categories: ' . $e->getMessage();
        error_log("Error fetching categories: " . $e->getMessage());
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'No database selected.';
    error_log("No database selected.");
}

echo json_encode($response);
exit;
?>
