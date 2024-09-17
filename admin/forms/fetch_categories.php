<?php
header('Content-Type: application/json');
require_once '../includes/db_config.php';
require_once '../includes/db_connection.php';

$response = ['categories' => []];

try {
    if (!isset($_GET['db'])) {
        throw new Exception('No database selected.');
    }

    $selected_db = $_GET['db'];
    $database = new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, $selected_db);
    $conn = $database->connect();

    if (!$conn) {
        throw new Exception('Failed to connect to the database.');
    }

    // Recursive function to get categories with indentation
    function getCategories($conn, $parent_id = 0, $indent = '') {
        $categories = [];
        $stmt = $conn->prepare("SELECT id, name FROM categories WHERE id IN (SELECT descendant_id FROM category_closure WHERE ancestor_id = ?) ORDER BY name");
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $categories[] = ['id' => $row['id'], 'name' => $indent . $row['name']];
            $categories = array_merge($categories, getCategories($conn, $row['id'], $indent . '--')); // Recursive call to add children
        }

        $stmt->close();
        return $categories;
    }

    // Fetch categories with hierarchy
    $response['categories'] = getCategories($conn);
    $response['status'] = 'success';
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
